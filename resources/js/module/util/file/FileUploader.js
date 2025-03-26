
import { SYSTEM_URL } from "../../../config/config.js";
import { toggleDisplayButtonState } from "../../component/accountUIOperations.js"
import BroadcastMessageOperator from "../../component/broadcast/BroadcastMessageOperator.js";
import { open_modal } from "../../component/modalOperation.js";
import ButtonController from "../../component/ui/ButtonController.js";
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

    constructor(file, errorTxtElement, errorElement, imageErrorElement, isTemplate, inputElement, modal = null){
        this.file = file
        this.errorTxtElement = errorTxtElement   
        this.newconfirmBtn = null
        this.errorElement = errorElement
        this.imageErrorElement = imageErrorElement
        this.modal = modal
        this.imageEditModal = document.getElementById("js_image_edit_modal")
        this.urlErrorElement = document.querySelector(".js_url_error")
        this.urlInput = document.getElementById("js_url_input")
        this.isTemplate = isTemplate
        this.cropArea = []
        this.url = ""
        this.inputElement = inputElement

        console.log(this.inputElement);
        

        // イベントを初期化
        this.initializeEvents();
    }

    initializeEvents() {
        // 画像切り取りモーダルの画像変更ボタン
        const button = ButtonController.replaceButton("js_changeImg_btn")
        button.addEventListener("click", ()=>{
            FormController.initializeFileUpload() //ファイルアップロードの初期化
        })
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

            if(this.imageEditModal.classList.contains("hidden")){
                this.#toggleLoader(true)
            }else{
                this.#toggleLoaderforChangeImg(true)
            }
            
            // 画像リンクモーダル表示
            this.urlErrorElement.classList.add("hidden")
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
            container.appendChild(newImage); // 新しい画像を保存

            if(this.imageEditModal.classList.contains("hidden")){
                this.#toggleLoader(false)
            }else{
                this.#toggleLoaderforChangeImg(false)
            }
    
            open_modal(this.imageEditModal)

            // 画像切り取りモーダルが表示されるときに前に出ているモーダルを非表示にする
            if(this.modal) this.modal.classList.add("hidden");

            // URLの設定
            this.urlInput.addEventListener("input", (e)=>{
                this.url = e.target.value
            })


            // 画像切り取りが完了して送信ボタンを押した後の処理
            this.newconfirmBtn = ButtonController.replaceButton("js_preview_submit_btn")
            this.newconfirmBtn.addEventListener("click", ()=>{
                // URL形式チェック

                FormController.initializeFileUpload()
                const regex = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([/\w .-]*)*(\?.*)?$/;
                const choices = document.getElementsByName('choice'); // ラジオボタン要素を取得
                const selectedChoices = Array.from(choices).find(choice => choice.checked);


                if(selectedChoices.value === "on" && !regex.test(this.urlInput.value)){
                        this.urlErrorElement.classList.remove("hidden")
                        return 
                }


                this.imageEditModal.classList.add("hidden")
                this.cropArea = this.cropper.getCropperArea()


                // ラジオボタンの切り替え
            
                if(selectedChoices.value === "off"){
                    this.cropArea = []
                    this.url = ""
                }


                if(this.isTemplate){
                    this.modal.classList.remove("hidden")
                    this.inputElement.parentElement.dataset.url = this.url
                    this.inputElement.parentElement.dataset.cropArea = JSON.stringify(this.cropArea)
                }else{
                    const index = document.querySelectorAll(".js_headings").length
                    BroadcastMessageOperator.displayImageMessageToList(newImage.src,"js_accordion_wrapper", "accordion", index);
                    // 不要なリストを削除
                    BroadcastMessageOperator.deleteList("accordion")
                    // // ボタン状態を更新
                    toggleDisplayButtonState(document.querySelector(".js_message_submit_btn "), document.querySelectorAll(".js_headings"))
                    reader.readAsDataURL(compressedFile);
                    // 新しいファイル名を生成し、FormDataArrayに保存
                    const newFileName = this.generateOriginalFileName()
                    FileUploader.setImageDataToFormDataArray(compressedFile, newFileName, index, this.url, this.cropArea)
                }
    

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

    static setImageDataToFormDataArray(compressedFile, newFileName, index,url, cropArea){
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
        indexStateManager.setState()  
    }

    getCropperData(){
        return {"url": this.url, "cropArea": this.cropArea}
    }

    /**
     * ファイルの形式とサイズを検証
     * - 許可されていない形式やサイズの場合にエラー文言を表示させる
     */
    validateFile(){
        let hasModal = true
        if(this.imageEditModal.classList.contains("hidden")) hasModal = false

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
        const confirmBtn = document.getElementById("js_change_area");

        // ボタンの状態を更新する関数
        const updateButtonState = () => {
                const isChoiceOn = [...choices].some(choice => choice.checked && choice.value === "on");
                const hasUrl = this.urlInput.value.length > 0;
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
    
        this.urlInput.addEventListener("input", () => {
                this.actionUrl = this.urlInput.value; // 必要なら保持
                this.urlErrorElement.classList.add("hidden")
                updateButtonState();
        });
    

        confirmBtn.addEventListener("click", () => {
                updateButtonState();
        });
    }


    static convertFileNameToFile = async (fileName) =>{
        const baseUrl = SYSTEM_URL.IMAGE_URL
        const url = `${baseUrl}/${fileName}`; // フルURLを生成
        const response = await fetch(url); // 画像を取得
        const blob = await response.blob(); // Blobに変換
    
        // BlobからFileオブジェクトを作成して返す
        return new File([blob], fileName, { type: blob.type });
    }
    
}

export default FileUploader;