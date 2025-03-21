import socket from "../../util/socket.js";
import formDataStateManager from "../../util/state/FormDataStateManager.js"
import indexStateManager from "../../util/state/IndexStateManager.js"
import { toggleDisplayButtonState } from "../accountUIOperations.js";
import { createBroadcastMessageRow } from "../elementTemplate.js";
import { open_modal } from "../modalOperation.js";
import FormController from "../ui/FormController.js";

const MAX_LENGTH = 20


/**
 * @param {string} message - 一斉送信メッセージ
 * @param {object} src - 画像の切り取り領域ジ
 * @param {string} className - メッセージ表示リストの親要素のクラス名
 * @param {HTMLElement} accordionId - 一斉メッセージまたは画像を挿入する要素
 * @param {string} index - メッセージまたは画像を一意に識別するためのインデックス
 * @param {string} formData - フォームデータの配列、送信内容（画像やテキスト）が含まれる

*/
class BroadcastMessageOperator{

    // 静的プロパティでインスタンスを保持
    static #instance = null;
    /**
     * シングルトンパターンのためのインスタンス取得メソッド
     * @param {string} className - メッセージ表示リストの親要素のクラス名
     * @param {HTMLElement} accordionId - 一斉メッセージまたは画像を挿入する要素
     * @returns {BroadcastMessageOperator} インスタンス
     */
    static getInstance(className, accordionId, baseUrl, isGreeting = false) {
        if (!BroadcastMessageOperator.#instance) {
            BroadcastMessageOperator.#instance = new BroadcastMessageOperator(className, accordionId, baseUrl,isGreeting);
        }
        return BroadcastMessageOperator.#instance;
    }

    constructor(className, accordionId, baseUrl,  isGreeting = false) {
        
        this.message = "";
        this.className = className;
        this.accordionId = accordionId;
        this.newBtn = null
        this.baseUrl = baseUrl
        this.isGreeting = isGreeting

        
        // 必要な要素を取得
        this.broadcastMessageInput = document.querySelector(".js_message_input");
        this.displayBtn = document.querySelector(".js_message_display_btn")
        this.submitBtn = document.querySelector(".js_message_submit_btn")

        // イベントを初期化
        this.initializeEvents();
    }

    initializeEvents() {
        BroadcastMessageOperator.deleteList("accordion")

        // メソッドをバインドしてイベントを登録
        this.broadcastMessageInput.addEventListener("input", this.handleMessageInput.bind(this));
        this.newBtn = this.displayBtn.cloneNode(true)
        this.displayBtn.parentNode.replaceChild(this.newBtn, this.displayBtn)
        this.newBtn.addEventListener("click", this.handleDisplayClick.bind(this));
        this.submitBtn.addEventListener("click",this.emitBroadcastMessageToSocket.bind(this))
    }
    
    /**
     * テキストメッセージをリストに表示 (インスタンスメソッド)
     */
    displayMessageToList(messageObj = []) {
        // 親要素を取得
        const parentElement = document.querySelector(`.${this.className}`);
        if (!parentElement) {
            console.error(`親要素が見つかりません: ${this.className}`);
            return;
        }

        if(messageObj.hasOwnProperty("message")){
            const data = {"type" : "text", "data" : messageObj["message"]}
            formDataStateManager.setItem(messageObj["message_order"], data)
        }

        const elementLength = parentElement.querySelectorAll(".js_card").length;
        const data = this.#prepareTextMessageData(elementLength, messageObj);

        const template = createBroadcastMessageRow(data, this.accordionId);
        parentElement.insertAdjacentHTML("beforeend", template);

        if(messageObj.hasOwnProperty("message")){
            indexStateManager.setState()  
        }
        
    }

    /**
     * 画像メッセージをリストに表示 (静的メソッド)
     * @param {string} src - 画像データのソース
     * @param {string} className - 親要素のクラス名
     * @param {string} accordionId - アコーディオンのID
     * @param {number} index - 表示順のインデックス
     */
    static displayImageMessageToList(src, className, accordionId, index) {

        // 親要素を取得
        const parentElement = document.querySelector(`.${className}`);
        if (!parentElement) {
            console.error(`親要素が見つかりません: ${className}`);
            return;
        }

        if(!src){
            alert("一斉配信画像設定でエラーが発生しました。再度お試しください。--")
        }


        const elementLength = parentElement.querySelectorAll(".js_card").length;
        const data = BroadcastMessageOperator.prepareImageMessageData(src, elementLength);

        const template = createBroadcastMessageRow(data, accordionId);
        parentElement.insertAdjacentHTML("beforeend", template);
    }

    /**
     * テキストメッセージデータを準備
     * @param {number} elementLength - 親要素の子要素数
     */
    #prepareTextMessageData(elementLength, messageObj) {

        if(messageObj.hasOwnProperty("message")){
            const heading = this.#truncateText(messageObj["message"]);
            return {
                heading,
                display: messageObj["message"],
                type: "text",
                elementLength: messageObj["message_order"],
                index: indexStateManager.getState(),
            };
        }else{
            const heading = this.#truncateText(this.message);
            return {
                heading,
                display: this.message,
                type: "text",
                elementLength,
                index: indexStateManager.getState(),
            };
        }
        
    }

    /**
     * 画像メッセージデータを準備 (静的メソッド)
     * @param {string} src - 画像のデータソース
     * @param {number} elementLength - 親要素の子要素数
     * @param {number} index - 表示順のインデックス
     */
    static prepareImageMessageData(src, elementLength) {
        return {
            heading: "画像",
            display: src,
            type: "img",
            elementLength,
            index: elementLength,
        };
    }

    /**
     * テキストが最大文字数を超える場合、末尾に "..." を付加
     * @param {string} text - 元のテキスト
     * @param {number} maxLength - 最大文字数 (デフォルト: 20)
     * @returns {string} - トランケートされたテキスト
     */
    #truncateText(text) {
        return text.length > MAX_LENGTH ? `${text.substr(0, MAX_LENGTH)}...` : text;
    }


    /**
     *  プレビューを表示してるDOM要素からメッセージリストを削除
    */
    static deleteList(id){
        let delete_btns = document.querySelectorAll(".js_deleteList")
        const accordion = document.getElementById(id)

        indexStateManager.setSpecificNumber(delete_btns.length)

        delete_btns.forEach((btn)=>{
            const newBtn = btn.cloneNode(true)
            btn.parentNode.replaceChild(newBtn, btn)

            newBtn.addEventListener("click", (e)=>{

                indexStateManager.setSpecificNumber(document.querySelectorAll(".js_deleteList").length)
                const target_id = e.currentTarget.parentElement.getAttribute("data-id")

                formDataStateManager.removeItem(target_id); // データを削除
                indexStateManager.setMinusState()
                const list_el = e.currentTarget.parentElement.parentElement
                if(accordion.contains(list_el)){
                    accordion.removeChild(list_el) 
                }

                this.#updateElementIndexes()
                this.#toggleSubmitButtonState()
            })
        })
    }

    /**
     *  要素のインデックスを更新
    */
    static #updateElementIndexes() {
        const elements = document.querySelectorAll(".js_data");
        const headings = document.querySelectorAll(".js_headings");

        elements.forEach((el, index) => el.setAttribute("data-id", index));
        headings.forEach((el, index) => el.setAttribute("data-id", index));
    }

    /**
     *  送信ボタンを押す前の値があるかのチェック
     * @param {String} id - データを表示させてる要素のID
     * @return {boolean} -リストが一つでもあればtrue,それ以外はfalse
    */
    static hasValue(id){
        const accordion = document.getElementById(id)
        const lists = accordion.querySelectorAll(".js_card")
        return lists.length > 0
    }

    static hideErrorMsg = () =>{
        const error_el = document.querySelector(".js_broadcast_error")
        if(!error_el.classList.contains("hidden")) error_el.classList.add("hidden")
    }

    // 送信ボタンの状態を切り替える
    static #toggleSubmitButtonState() {
        toggleDisplayButtonState(
            document.querySelector(".js_message_submit_btn"),
            document.querySelectorAll(".js_headings")
        );
        toggleDisplayButtonState(
            document.querySelector(".js_message_display_btn"),
            document.querySelectorAll(".js_headings")
        );
    }


    handleDisplayClick(){

        indexStateManager.setSpecificNumber(document.querySelectorAll(".js_headings").length)
        const index = indexStateManager.getState()
        const data = {"type" : "text", "data" : this.message}

        this.newBtn.classList.add("disabled_btn")
        formDataStateManager.setItem(index, data)

        this.displayMessageToList()
        BroadcastMessageOperator.deleteList("accordion")
        toggleDisplayButtonState(document.querySelector(".js_message_submit_btn"),document.querySelectorAll(".js_headings"));

        this.message = ""
        FormController.initializeInput()

        indexStateManager.setState()
    }

    handleMessageInput(e){

        BroadcastMessageOperator.hideErrorMsg()
        this.message = e.currentTarget.value
        toggleDisplayButtonState(this.newBtn, this.message)
    }


    prepareBroadcastFormData(){
        if(!BroadcastMessageOperator.hasValue("accordion")){
            const error_el = document.querySelector(".js_broadcast_error")
            const errorTxt = document.querySelector(".js_error_txt")

            errorTxt.innerHTML = `メッセージを入力して保存ボタンを押してください。<br> または画像を選択してください。`
            error_el.classList.remove("hidden")
            return
        }

        const data = document.querySelectorAll(".js_data")
        // 順番通りに並べ替え
        const formDataArray = formDataStateManager.getState()
        const formData = new FormData();


        // sendMessage のデータを FormData に保存
        formDataArray.forEach((item, index) => {

            if(item !== undefined && item.type !== undefined){
                if (item.type === 'image') {
                    // FormDataから画像を取得
                    const imageFile = item.formData.get('image');
                    if (imageFile) {
                        formData.append(`images[${index}]`, imageFile, item.fileName);
                    }
                    if(item.url && item.cropArea){
                        formData.append(`images[${index}][meta]`, JSON.stringify({ url: item.url, cropArea: item.cropArea }));
                    }
                } else if (item.type === 'text') {
                    // テキストデータを保存
                    formData.append(`messages[${index}]`, item.data);
                }
            }
            
        });

        return formData
    }

    async submitBroadcastMessageToServer(){
        try{
            const admin_id = document.getElementById("js_account_id").value
            const loader = document.querySelector(".loader")
            const modal = document.querySelector(".broadcasting_message_modal")
            modal.classList.add("hidden")
            open_modal(loader)

            const formData = this.prepareBroadcastFormData()

            const response = await fetch(`${this.baseUrl}/${admin_id}`, {
                method: 'POST',
                body: formData,
            })

            if (!response.ok) {
                alert("一斉送信の作成でエラーが発生しました。もう一度お試しください");
            }

            const data = await response.json(); // レスポンスをJSONに変換
            return data; // JSONデータを返す
        }catch(error){
            console.log(error);
            
        }

    }

    async emitBroadcastMessageToSocket(){
        try{
            const response = await this.submitBroadcastMessageToServer()

            // モーダルをloaderを閉じる処理
            document.getElementById("js_messageSetting_modal").classList.add("hidden")
            document.querySelector(".bg").classList.add("hidden")
            const admin_id = document.getElementById("js_account_id").value
            const loader = document.querySelector(".loader")
            loader.classList.add("hidden")
            
            // 成功メッセージを出す処理
            const success_el = document.getElementById("js_alert_success")
            success_el.style.display = "block";
            success_el.innerHTML = this.isGreeting ? "初回挨拶メッセージの設定に成功しました。" : "一斉送信に成功しました"
            document.querySelector(".js_message_input").value = ""
            FormController.initializeFileUpload()
            document.querySelector(".js_accordion_wrapper").innerHTML = ""
    
            // 成功メッセージを出して2秒後に批評にする
            setTimeout(() => {
                success_el.style.display = "none"
            }, 2000);
    
    
            if(this.isGreeting){
                return
            }
            const {created_at, data} = response
    
            // formDataをリセットする
            formDataStateManager.resetItem()
    
            indexStateManager.resetState()


            socket.emit("broadcast message", {
                sendingDatatoBackEnd: data,
                admin_id: admin_id,
                created_at: created_at,
            });
    
            
        }catch(error){
            console.log(error);
            
        }


    }

}

export default BroadcastMessageOperator;