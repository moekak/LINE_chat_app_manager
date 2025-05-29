
import AbstractTestMessageSender from "./AbstractTestMessageSender.js";

export default class BroadcastTestMessageSender extends AbstractTestMessageSender{
      constructor(){
            super("broadcast"); // 親クラスのコンストラクタを呼び出す
            this.previosModal = document.getElementById("js_messageSetting_modal")
            this.openTestSenderModalButton = document.getElementById('js_sender_list') //テスト送信者モーダルを表示するボタン

            this.initialize()
      }
}