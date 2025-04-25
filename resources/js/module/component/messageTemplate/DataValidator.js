import { ERROR_TEXT } from "../../../config/config.js";

class DataValidator{
    constructor(templateName = null, categoryId = null, hasContent = null){
        this.templateName = templateName
        this.categoryId = categoryId
        this.hasContent = hasContent
        this.errorListWrapper = document.getElementById("js_error_list")
        this.formError = document.getElementById("form-errors")
        this.successMessageElement = document.getElementById("js_alert_success")
        this.errors = []
    }

    /**
     * 送信ボタンを押した後に必須のデータがあるかチェック
     * エラーがあれば内部のerrors配列に追加する
     * @return {boolean} -エラーメッセージが一つでもあればtrue、なければfalse
     */
    hasInvalidData(){
        if(this.templateName.value == "") this.errors.push(ERROR_TEXT.TEMPLATE_NAME_EMPTY_ERROR)
        if(this.categoryId.value == "" || this.categoryId.value == "カテゴリーを選択") this.errors.push(ERROR_TEXT.CATEGORY_EMPTY_ERROR)
        if(!this.hasContent) this.errors.push(ERROR_TEXT.CONTENTS_EMPTY_ERROR)
        
        return this.errors.length > 0
    }


    displayErrorList(errorMsgs){
        errorMsgs.forEach((errorMsg)=>{
            const li = this.#createErrorElement()
            li.innerHTML = errorMsg
            this.errorListWrapper.append(li)
        })
        this.formError.classList.remove("hidden")
        const modalContent = document.getElementById('js_template_modal');
        modalContent.scrollTop = 0;

    }

    /**
     * 検出したエラーメッセージを画面に表示する
     * エラーリストを作成し、エラー表示領域の非表示を解除する
     */
    displayErrorMessages(){
        this.errors.forEach(error => {
            const li = this.#createErrorElement()
            li.innerHTML = error
            this.errorListWrapper.append(li)
        });
        this.formError.classList.remove("hidden")

        const modalContent = document.getElementById('js_template_modal');
        modalContent.scrollTop = 0;
    }

    static displayCategorySuccessMessage(successMessage){
        const successMsg = document.getElementById("form-success")
        const message = document.querySelector(".js_success_msg")

        message.innerHTML = successMessage
        successMsg.classList.remove("hidden")

        const modalContent = document.getElementById('js_template_modal');
        modalContent.scrollTop = 0;

        // 成功メッセージを出して2秒後に批評にする
        setTimeout(() => {
            successMsg.classList.add("hidden")
        }, 2000);
    }
    
    displaySuccessMessage(msg){
        this.successMessageElement.style.display = "block"
        this.successMessageElement.innerHTML = msg
        
        // 成功メッセージを出して2秒後に批評にする
        setTimeout(() => {
            this.successMessageElement.style.display = "none"
        }, 2000);

        const modalContent = document.getElementById('js_template_modal');
        modalContent.scrollTop = 0;
    }
    /**
     *  エラー文を表示するためのリスト項目要素を作成する
     * @return {HTMLElement} -エラーメッセージを表示するためのli要素（error-itemクラス付き）
    */
    #createErrorElement(){
        const li = document.createElement("li")
        li.classList.add("error-item")

        return li
    }

    static getAllValidationErrorMessages(response) {
        const allErrorMessages = [];
        
        if (response && response.error) {
            // すべてのフィールド名を取得
            const errorFields = Object.keys(response.error);
            
            // 各フィールドのすべてのエラーメッセージを配列に追加
            errorFields.forEach(field => {
                const fieldErrors = response.error[field];
                fieldErrors.forEach(errorMsg => {
                    allErrorMessages.push(errorMsg);
                });
            });
        }
        
        return allErrorMessages;
    }
}


export default DataValidator;