import socket from "../../util/socket.js"
import MessageHandlerFactory from "./handler/MessageHandlerFactory.js"
import SendingDataServiceInterface from "./SendingDataServiceInterface.js"

export default class TestSendingData extends SendingDataServiceInterface{
      constructor(url, type){
            const baseUrl = url
            const operationType = "test"
            const modal = document.getElementById("js_test_sender")
            super(baseUrl, operationType, modal)
            this.testSenderLoader = document.querySelector(".loader-wrapper")
            this.classType = type
      }

      
      async emitBroadcastMessageToSocket(userIds = []){
            try{
                  const response = await this.submitBroadcastMessageToServer(userIds)
                  this.formData = new FormData()
                  // 成功メッセージを出す処理
                  this.successOperator()
                  this.sendMessageToSocket(response)
            }catch(error){
                  console.log(error);
                  
            }
      }


      modalOperator(){
            this.testSenderLoader.classList.remove("hidden")
      }


      /**
      * 非同期処理成功時処理
      * @override
      */
      successOperator(){

            this.testSenderLoader.classList.add("hidden")
            const success_el = document.getElementById("js_alert_success")
            success_el.style.display = "block";
            success_el.innerHTML = "メッセージのテスト送信に成功しました"

            // 成功メッセージを出して2秒後に非表示にする
            setTimeout(() => {
                  success_el.style.display = "none"
            }, 2000);
      }


      /**
       * socketサーバーにデータを送信する処理
       * @override
       */
      sendMessageToSocket(response){
            const {created_at, data, userIds} = response
            socket.emit("test message", {
                  sendingDatatoBackEnd: data,
                  userIds: userIds,
                  created_at: created_at,
            });
      }


      prepareBroadcastFormData(userIds){

            console.log(this.classType);
            
            const handler = MessageHandlerFactory.getHandler(this.classType, this)
            handler.handle(userIds)

      }

}     