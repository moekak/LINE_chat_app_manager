
import formDataStateManager from "../../util/state/FormDataStateManager.js"
import { close_loader, hide_bg } from "../modalOperation.js"
import FormController from "../ui/FormController.js"
import SendingDataServiceInterface from "./SendingDataServiceInterface.js"

export default class GreetingSendingData extends SendingDataServiceInterface{
      constructor(url){
            const baseUrl = url
            const operationType = "greeting"
            const modal = document.getElementById("js_messageSetting_modal")
            super(baseUrl, operationType, modal)
      }

      /**
      * エラーハンドリング処理
      * @override
      */
      errorHandle(){
            const error_el = document.querySelector(".js_broadcast_error")
            const errorTxt = document.querySelector(".js_error_txt")

            errorTxt.innerHTML = `メッセージを入力して保存ボタンを押してください。<br> または画像を選択してください。`
            error_el.classList.remove("hidden")
            return
      }


      modalOperator(){
            this.modal.classList.add("hidden")
            this.loader.classList.remove("hidden")
      }


      
      async emitBroadcastMessageToSocket(userIds = []){
            try{
                  const response = await this.submitBroadcastMessageToServer(userIds)
                  close_loader()
                  hide_bg()
                  
                  // 成功メッセージを出す処理
                  this.successOperator()
                  this.sendMessageToSocket(response)
            }catch(error){
                  console.log(error);
                  
            }
      }

      /**
      * 非同期処理成功時処理
      * @override
      */
      successOperator(){
            const success_el = document.getElementById("js_alert_success")
            success_el.style.display = "block";
            success_el.innerHTML = "初回メッセージの設定に成功しました"
            document.querySelector(".js_message_input").value = ""
            FormController.initializeFileUpload()
            document.querySelector(".js_accordion_wrapper").innerHTML = ""

            // 成功メッセージを出して2秒後に非表示にする
            setTimeout(() => {
                  success_el.style.display = "none"
            }, 2000);
      }


      prepareBroadcastFormData(userIds){
            if(!SendingDataServiceInterface.hasValue("accordion")){
                  this.errorHandle()
            }

            const formDataArray = formDataStateManager.getState()
            // sendMessage のデータを FormData に保存
            if (userIds.length > 0) {
                  // 配列全体を一つのキーで追加する方法
                  this.formData.append('userIds', JSON.stringify(userIds)); 
            }

            formDataArray.forEach((item, index) => {
                  if(item !== undefined && item.type !== undefined){
                        if (item.type === 'image') {
                              this.operateImageData(item, index)
                        } else if (item.type === 'text') {
                              this.operateTextData(item, index)
                        }
                  }
            });
      }


}     