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
    constructor(className, accordionId) {
        
        this.message = "";
        this.className = className;
        this.accordionId = accordionId;
        this.sendMessage = []

        
        // 必要な要素を取得
        this.broadcastMessageInput = document.querySelector(".js_message_input");
        this.displayBtn = document.querySelector(".js_message_display_btn")
        this.submitBtn = document.querySelector(".js_message_submit_btn")

        // イベントを初期化
        this.initializeEvents();
    }

    initializeEvents() {
        // メソッドをバインドしてイベントを登録
        this.handleMessageInput = this.handleMessageInput.bind(this);
        this.broadcastMessageInput.addEventListener("input", this.handleMessageInput);

        this.handleDisplayClick = this.handleDisplayClick.bind(this);
        this.displayBtn.addEventListener("click", this.handleDisplayClick);

        this.emitBroadcastMessageToSocket = this.emitBroadcastMessageToSocket.bind(this)
        this.submitBtn.addEventListener("click", this.emitBroadcastMessageToSocket)
    }
    
    /**
     * テキストメッセージをリストに表示 (インスタンスメソッド)
     */
    displayMessageToList() {

        // 親要素を取得
        const parentElement = document.querySelector(`.${this.className}`);
        if (!parentElement) {
            console.error(`親要素が見つかりません: ${this.className}`);
            return;
        }

        const elementLength = parentElement.querySelectorAll(".js_card").length;
        const data = this.#prepareTextMessageData(elementLength);

        const template = createBroadcastMessageRow(data, this.accordionId);
        parentElement.insertAdjacentHTML("beforeend", template);
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
        const data = BroadcastMessageOperator.prepareImageMessageData(src, elementLength, index);

        const template = createBroadcastMessageRow(data, accordionId);
        parentElement.insertAdjacentHTML("beforeend", template);
    }

    /**
     * テキストメッセージデータを準備
     * @param {number} elementLength - 親要素の子要素数
     */
    #prepareTextMessageData(elementLength) {
        const heading = this.#truncateText(this.message);
        return {
            heading,
            display: this.message,
            type: "text",
            elementLength,
            index: indexStateManager.getState(),
        };
    }

    /**
     * 画像メッセージデータを準備 (静的メソッド)
     * @param {string} src - 画像のデータソース
     * @param {number} elementLength - 親要素の子要素数
     * @param {number} index - 表示順のインデックス
     */
    static prepareImageMessageData(src, elementLength, index) {
        return {
            heading: "画像",
            display: src,
            type: "img",
            elementLength,
            index,
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
    
        delete_btns.forEach((btn)=>{
            btn.addEventListener("click", (e)=>{
                const target_id = e.currentTarget.parentElement.getAttribute("data-id")
                formDataStateManager.removeItem(target_id); // データを削除
    
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

        const index = indexStateManager.getState()
        const data = {"type" : "text", "data" : this.message}

        formDataStateManager.setItem(index, data)

        this.displayMessageToList()
        BroadcastMessageOperator.deleteList("accordion")
        BroadcastMessageOperator.#toggleSubmitButtonState()

        this.message = ""
        FormController.initializeInput()

        indexStateManager.setState()
    }

    handleMessageInput(e){

        BroadcastMessageOperator.hideErrorMsg()
        this.message = e.currentTarget.value
        toggleDisplayButtonState(this.displayBtn, this.message)
    }


    prepareBroadcastFormData(){
        if(!BroadcastMessageOperator.hasValue("accordion")){
            const error_el = document.querySelector(".js_broadcast_error")
            const errorTxt = document.querySelector(".js_error_txt")

            errorTxt.innerHTML = `メッセージを入力して追加ボタンを押してください。<br> または画像を選択してください。`
            error_el.classList.remove("hidden")
            return
        }

        const data = document.querySelectorAll(".js_data")
        // 順番通りに並べ替え
        const formDataArray = formDataStateManager.getState()
        
        for(let i = 0; i < data.length; i ++){
            this.sendMessage[i] = formDataArray[Array.from(data)[i].getAttribute("data-file-index")]
        }

        const formData = new FormData();

        // sendMessage のデータを FormData に追加
        this.sendMessage.forEach((item, index) => {
            if (item.type === 'image') {
                // FormDataから画像を取得
                const imageFile = item.formData.get('image');
                if (imageFile) {
                    formData.append(`images[${index}]`, imageFile, item.fileName);
                }
            } else if (item.type === 'text') {
                // テキストデータを追加
                formData.append(`messages[${index}]`, item.data);
            }
        });

        return formData
    }

    async submitBroadcastMessageToServer(){
        const admin_id = document.getElementById("js_account_id").value
        const loader = document.querySelector(".loader")
        const modal = document.querySelector(".broadcasting_message_modal")
        modal.classList.add("hidden")
        open_modal(loader)

        const formData = this.prepareBroadcastFormData()

        const response = await fetch(`/api/broadcast_message/store/${admin_id}`, {
            method: 'POST',
            body: formData,
        })

        if (!response.ok) {
            throw new Error("一斉送信の作成でエラーが発生しました。もう一度お試しください");
        }

        const data = await response.json(); // レスポンスをJSONに変換
        return data; // JSONデータを返す
    }

    async emitBroadcastMessageToSocket(){
        const response = await this.submitBroadcastMessageToServer()

        if(!response["created_at"]){
            return
        }


        // モーダルをloaderを閉じる処理
        document.getElementById("js_boradcasting_modal").classList.add("hidden")
        document.querySelector(".bg").classList.add("hidden")
        const admin_id = document.getElementById("js_account_id").value
        const loader = document.querySelector(".loader")
        loader.classList.add("hidden")

        // 成功メッセージを出す処理
        const success_el = document.getElementById("js_alert_success")
        success_el.style.display = "block";
        success_el.innerHTML = "一斉送信に成功しました"
        document.querySelector(".js_message_input").value = ""
        document.querySelector(".js_upload").value = ""
        document.querySelector(".js_accordion_wrapper").innerHTML = ""

        // 成功メッセージを出して2秒後に批評にする
        setTimeout(() => {
            success_el.style.display = "none"
        }, 2000);

        const created_at = response["created_at"]
        const sendingDatatoBackEnd = response["data"];
        socket.emit("broadcast message", {
            sendingDatatoBackEnd: sendingDatatoBackEnd,
            admin_id: admin_id,
            created_at: created_at
        });
    }
}

export default BroadcastMessageOperator;