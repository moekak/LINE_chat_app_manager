import { toggleDisplayButtonState } from "../../component/accountUIOperations.js"
import BroadcastMessageOperator from "../../component/broadcast/BroadcastMessageOperator.js";
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
    }

    /**
     * ファイル操作のメイン処理
     * - ファイルの検証
     * - 圧縮処理
     * - ファイルの表示および保存
     */
    async fileOperation(){

        try{
            this.validateFile()
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
        reader.onload = e =>{
            BroadcastMessageOperator.displayImageMessageToList(e.target.result,"js_accordion_wrapper", "accordion", index);
            // 不要なリストを削除
            BroadcastMessageOperator.deleteList("accordion")

            // インデックスをインクリメント
            indexStateManager.setState()

            // ボタン状態を更新
            toggleDisplayButtonState(document.querySelector(".js_message_submit_btn "), document.querySelectorAll(".js_headings"))
        }

        reader.readAsDataURL(compressedFile);

        // 新しいファイル名を生成し、FormDataArrayに保存
        const newFileName = this.generateOriginalFileName()
        this.setImageDataToFormDataArray(compressedFile, newFileName, index)
    }

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

    setImageDataToFormDataArray(compressedFile, newFileName, index){
        const formData = new FormData();
        formData.append('image', compressedFile); // ファイル名も保持

        const data = {
            formData: formData,
            fileName: newFileName,  // ファイル名を保存
            type: 'image'        // タイプも保存しておくと便利
        }

        formDataStateManager.setItem(index, data)
    }


    /**
     * ファイルの形式とサイズを検証
     * - 許可されていない形式やサイズの場合にエラー文言を表示させる
     */
    validateFile(){
        if(!FileUploader.isAllowedType(this.file.type)){
            this.errorTxtElement.innerHTML = "許可されているファイル形式は JPG, PNG, GIF, WEBP のみです。"
            return;
        }
        if(!FileUploader.isCorrectSize(this.file.size)){
            this.errorTxtElement.innerHTML = "画像サイズが大きすぎます。5MB以内で指定してください。"
            return
        }
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
            'image/gif',
            'image/webp'
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
}

export default FileUploader;