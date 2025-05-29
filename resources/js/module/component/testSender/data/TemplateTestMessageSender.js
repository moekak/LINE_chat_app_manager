
import AbstractTestMessageSender from "./AbstractTestMessageSender.js";
export default class TemplateTestMessageSender extends AbstractTestMessageSender{
      constructor(isUpdate){
            super("template", isUpdate); // 親クラスのコンストラクタを呼び出す
            this.previosModal = document.getElementById("js_template_modal")
            this.openTestSenderModalButton = isUpdate ? document.getElementById('js_sender_list_edit_template'): document.getElementById('js_sender_list_template') //テスト送信者モーダルを表示するボタン
            
            this.initialize()
      }
}