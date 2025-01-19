
import { toggleDisplayButtonState } from "../../component/accountUIOperations.js"
import BroadcastMessageOperator from "../../component/broadcast/BroadcastMessageOperator.js";
import { open_modal } from "../../component/modalOperation.js";
import ButtonController from "../../component/ui/ButttonController.js";
import FormController from "../../component/ui/FormController.js";
import Cropper from "../cropper/Cropper.js";
import CropperEventHandler from "../cropper/CropperEventHandler.js";
import formDataStateManager from "../state/FormDataStateManager.js"
import indexStateManager from "../state/IndexStateManager.js"
import imageCompression from 'browser-image-compression';

const MAX_SIZE = 5 * 1024 * 1024

/**
 * FileUploader
 * ファイルアップロードとその操作を管理するクラス
 * 
 * @param {File} file - 選択された画像ファイル
 * @param {HTMLElement} errorTxtElement - エラー文を表示するための要素
 */
class FileUploader{

    constructor(file, errorTxtElement){
        this.file = file
        this.errorTxtElement = errorTxtElement   
        this.newconfirmBtn = null
        this.errorElement = document.querySelector(".js_broadcast_error")
        this.imageErrorElement = document.querySelector(".js_image_error")

        // イベントを初期化
        this.initializeEvents();
    }

    initializeEvents() {
        this.changeImageBtn = document.getElementById("js_changeImg_btn")
        this.newChangeImageBtn = this.changeImageBtn.cloneNode(true)
        this.changeImageBtn.parentNode.replaceChild(this.newChangeImageBtn, this.changeImageBtn)

        this.newChangeImageBtn.addEventListener("click", this.handleDisplayClick.bind(this));
    }

    handleDisplayClick(){
        FormController.initializeFileUpload()
    }

    /**
     * ファイル操作のメイン処理
     * - ファイルの検証
     * - 圧縮処理
     * - ファイルの表示および保存
     */
    async fileOperation(){

        try{
            if(!this.validateFile()) return

            if(document.querySelector(".image_edit_modal").classList.contains("hidden")){
                console.log("contain");
                
                this.#toggleLoader(true)
            }else{
                console.log("not contain");
                
                this.#toggleLoaderforChangeImg(true)
            }
            
            // 画像リンクモーダル表示
            document.querySelector(".js_url_error").classList.add("hidden")

            const compressedFile = await this.#compressedFile()
            await this.handleFileUpload(compressedFile)
        }catch(error){
            console.error(error)
            alert('ファイル操作中にエラーが発生しました。再度実行してください')
        }
    }

    /**
     * 圧縮されたファイルを処理
     * - ファイルをリストに表示
     * - FormDataArrayに保存
     * 
     * @param {File} compressedFile - 圧縮済みのファイル
     */
    async handleFileUpload(compressedFile){
        const reader = new FileReader();
        const index = indexStateManager.getState()
        const newImage = this.#createImageElement(this.file);

        newImage.onload = e =>{
            const newImageButton = ButtonController.replaceButton("js_change_area")
            this.cropper = new Cropper(this.imageElement, newImageButton)


            const cropperHandler = new CropperEventHandler(newImageButton,this.cropper)
            cropperHandler.changeBtnEvent()
            this.#changeSubmitBtn()
            // 新しい画像要素を Cropper に更新
            this.cropper.updateImage(newImage);

            // 古い画像を置き換え
            const container = document.getElementById("image-container");
            container.innerHTML = ""; // 古い画像を削除
            container.appendChild(newImage); // 新しい画像を追加

            // TODODODO

            if(document.querySelector(".image_edit_modal").classList.contains("hidden")){
                this.#toggleLoader(false)
            }else{
                this.#toggleLoaderforChangeImg(false)
            }
    
            const imageEditModal = document.getElementById("js_image_edit_modal")
            open_modal(imageEditModal)

            // URLの設定
            const urlInput = document.getElementById("js_url_input")
            let url = ""
            urlInput.addEventListener("input", (e)=>{
                url = e.target.value
            })


            // 画像切り取りが完了して送信ボタンを押した後の処理
            const confirmBtn = document.getElementById("js_preview_submit_btn")
            this.newconfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(this.newconfirmBtn, confirmBtn);
            const modal = document.getElementById("js_image_edit_modal")

            this.newconfirmBtn.addEventListener("click", ()=>{
                // URL形式チェック

                FormController.initializeFileUpload()
                const regex = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([/\w .-]*)*(\?.*)?$/;
                const urlInput= document.getElementById("js_url_input")

                const choices = document.getElementsByName('choice'); // ラジオボタン要素を取得
                const selectedChoices = Array.from(choices).find(choice => choice.checked);


                if(selectedChoices.value === "on" && !regex.test(urlInput.value)){
                        const urlError = document.querySelector(".js_url_error")
                        urlError.classList.remove("hidden")
                        return 
                }


                modal.classList.add("hidden")

                let cropArea = this.cropper.getCropperArea()


                // ラジオボタンの切り替え
            
                if(selectedChoices.value === "off"){
                    cropArea = []
                    url = ""
                }
    
                
                BroadcastMessageOperator.displayImageMessageToList(newImage.src,"js_accordion_wrapper", "accordion", index);
                // 不要なリストを削除
                BroadcastMessageOperator.deleteList("accordion")

                // インデックスをインクリメント
                indexStateManager.setState()

                // // ボタン状態を更新
                toggleDisplayButtonState(document.querySelector(".js_message_submit_btn "), document.querySelectorAll(".js_headings"))

                reader.readAsDataURL(compressedFile);

                // 新しいファイル名を生成し、FormDataArrayに保存
                const newFileName = this.generateOriginalFileName()
                this.setImageDataToFormDataArray(compressedFile, newFileName, index, url, cropArea)
            })
        }


    }

     // 新しい画像要素を作成
    #createImageElement(){
        const newImage = document.createElement("img");
        newImage.src = URL.createObjectURL(this.file);
        newImage.id = "image"; // IDを付加
        return newImage;
    };

    /**
     * ファイルを圧縮
     * @returns {Promise<File>} - 圧縮済みのファイル
     */
    async #compressedFile(){
        return await imageCompression(this.file, {
            maxSizeMB: 1,
            maxWidthOrHeight: 1024,
            useWebWorker: true
        });
    }

    /**
     * オリジナルのファイル名を生成
     * @returns {string} - 新しいファイル名
     */

    generateOriginalFileName(){
        const originalName = this.file.name;
        const extension = originalName.split('.').pop();  // 拡張子を取得
        return  `${Date.now()}_${Math.random().toString(36).substr(2, 9)}.${extension}`;
    }

    /**
     * 圧縮ファイルをFormDataArrayに保存
     * @param {File} compressedFile - 圧縮済みのファイル
     * @param {string} newFileName - 新しいファイル名
     * @param {number} index - 保存先のインデックス
     */

    setImageDataToFormDataArray(compressedFile, newFileName, index,url, cropArea){
        const formData = new FormData();
        formData.append('image', compressedFile); // ファイル名も保持

        const data = {
            formData: formData,
            fileName: newFileName,  // ファイル名を保存
            type: 'image',        // タイプも保存しておくと便利
            cropArea: JSON.stringify(cropArea),
            url: url
        }


        formDataStateManager.setItem(index, data)
    }

    /**
     * ファイルの形式とサイズを検証
     * - 許可されていない形式やサイズの場合にエラー文言を表示させる
     */
    validateFile(){
        let hasModal = true
        if(document.getElementById("js_image_edit_modal").classList.contains("hidden")) hasModal = false

        if(!FileUploader.isAllowedType(this.file.type)){

            return this.#validationError("許可されているファイル形式は JPG, PNGのみです。", hasModal)
        }
        if(!FileUploader.isCorrectSize(this.file.size)){
            return this.#validationError("画像サイズが大きすぎます。5MB以内で指定してください。", hasModal)
        }
        return true
    }


    /**
     * 許可されているファイル形式かを判定
     * @param {string} fileType - ファイルのMIMEタイプ
     * @returns {boolean} - 許可されている形式ならtrue
     */
    static isAllowedType(fileType){
        const allowedTypes = [
            'image/jpeg',
            'image/png',
        ];
        return allowedTypes.includes(fileType)
    }
    
    
    /**
     * ファイルサイズが許可範囲内かを判定
     * @param {number} fileSize - ファイルサイズ (バイト単位)
     * @returns {boolean} - 許可されているサイズならtrue
     */
    static isCorrectSize(fileSize){
        return fileSize < MAX_SIZE
    }

    #validationError(txt, hasModal){
        FormController.initializeFileUpload()

        if(hasModal){
            document.querySelector(".js_image_error").classList.remove("hidden")
            document.querySelector(".js_image_error").innerHTML = txt
        }else{
            document.querySelector(".js_broadcast_error").classList.remove("hidden")
            document.querySelector(".js_error_txt").innerHTML = txt
        }
        
        return false
    }

    #toggleLoader(isLoading){

        const messageModal = document.getElementById("js_messageSetting_modal")
        const loader = document.querySelector(".loader")
        const fixed_bg = document.querySelector(".fixed_bg")

        if(isLoading){
            messageModal.style.zIndex = 0
            loader.classList.remove("hidden")
            fixed_bg.classList.remove("hidden")
        }else{
            messageModal.style.zIndex = 999
            loader.classList.remove("add")
            fixed_bg.classList.add("hidden")
        }
    }

    #toggleLoaderforChangeImg(isLoading){
        const modal = document.querySelector(".image_edit_modal")
        const settingModal = document.getElementById("js_messageSetting_modal")
        const loader = document.querySelector(".loader")
        const bg = document.querySelector(".fixed_bg")

        if(isLoading){
            modal.style.zIndex = "997"
            settingModal.style.zIndex="997"
            loader.classList.remove("hidden")
            bg.classList.remove("hidden")
        }else{
            modal.style.zIndex = "999"
            settingModal.style.zIndex="999"
            loader.classList.add("hidden")
            bg.classList.add("hidden")
        }
    }


    // 送信ボタンの色を変更する
    // 送信ボタンの色を変更する
    #changeSubmitBtn() {

        const choices = document.querySelectorAll('input[name="choice"]');
        const urlInput = document.getElementById("js_url_input");
        const confirmBtn = document.getElementById("js_change_area");
        const urlError = document.querySelector(".js_url_error")

        // ボタンの状態を更新する関数
        const updateButtonState = () => {
                const isChoiceOn = [...choices].some(choice => choice.checked && choice.value === "on");
                const hasUrl = urlInput.value.length > 0;
                const isConfirmed = confirmBtn.innerHTML !== "選択範囲確定";

                if(isChoiceOn){
                    if (hasUrl && isConfirmed) {
                        this.newconfirmBtn.classList.remove("disabled_btn");
                    } else {
                        this.newconfirmBtn.classList.add("disabled_btn");
                    }
                }else{

                    this.newconfirmBtn.classList.remove("disabled_btn");
                }
        };
    
        // 各イベントリスナーでボタン状態を更新
        choices.forEach(choice => {
                choice.addEventListener("change", updateButtonState);
        });
    
        urlInput.addEventListener("input", () => {
                this.actionUrl = urlInput.value; // 必要なら保持
                urlError.classList.add("hidden")
                updateButtonState();
        });
    

        confirmBtn.addEventListener("click", () => {
                updateButtonState();
        });
    }

    
}

export default FileUploader;