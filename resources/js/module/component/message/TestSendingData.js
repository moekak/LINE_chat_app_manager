import socket from "../../util/socket.js"
import SendingDataServiceInterface from "./SendingDataServiceInterface.js"

export default class TestSendingData extends SendingDataServiceInterface{
      constructor(url){
            const baseUrl = url
            const operationType = "test"
            const modal = document.getElementById("js_test_sender")
            super(baseUrl, operationType, modal)
      }


      /**
      * 非同期処理成功時処理
      * @override
      */
      successOperator(){
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

}     