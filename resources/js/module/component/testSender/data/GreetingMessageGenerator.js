
import { getCurrentTimeFormatted } from "../../../util/formatDate.js";
import DataGeneratorInterface from "./DataGeneratorInterface.js";

export default class GreetingMessageGenerator extends DataGeneratorInterface{
      constructor(){
            super(); // 親クラスのコンストラクタを呼び出す
            this.openTestSenderModalButton = document.getElementById('js_sender_list') //テスト送信者モーダルを表示するボタン
            this.testSenderButton = document.getElementById("js_sending_btn") //テスト送信ボタン
            this.previosModal = document.getElementById("js_messageSetting_modal")
            this.testSenderModal = document.getElementById("js_test_sender")

            this.sendingDataToBackEnd = []
            this.initialize()
      }

      initialize(){
            this.openTestSenderModalButton.addEventListener("click", ()=>{
                  this.dislpayTestSenderModal();
                  this.getSendingData();
            })

            this.testSenderButton.addEventListener("click", ()=>{
                  this.sendTestMessages()
            })
            

            this.userCheckListElement.forEach((element)=>{
                  element.addEventListener("change", this.getSenderUserIds.bind(this))
            })
      }

      /**
      * テスト送信するメッセージを取得し、sendingDataToBackEndオブジェクトに格納する
      * @override
      */
      getSendingData(){
            const allDataElements = document.querySelectorAll(".js_data")
            this.sendingDataToBackEnd["admin_id"] = this.#getAdminID()
            this.sendingDataToBackEnd["created_at"] = getCurrentTimeFormatted()

            allDataElements.forEach((element, index)=>{
                  if(element.querySelector(".js_img")){
                        this.#formatImageMessage(element, index)
                  }else{
                        this.#formatTextMessage(element, index)
                  }
            })
      }

      /**
      * テスト送信するメッセージを取得し、sendingDataToBackEndオブジェクトに格納する
      * @override
      */
      getSendingData(){
            const allDataElements = document.querySelectorAll(".js_data")
            this.sendingDataToBackEnd["admin_id"] = this.#getAdminID()
            this.sendingDataToBackEnd["created_at"] = getCurrentTimeFormatted()

            allDataElements.forEach((element, index)=>{
                  if(element.querySelector(".js_img")){
                        this.#formatImageMessage(element, index)
                  }else{
                        this.#formatTextMessage(element, index)
                  }
            })
      }

      /**
      * テスト送信する画像メッセージを取得し、データを成型
      * @override
      */
      #formatImageMessage(element, index){
            const imageUrl = element.querySelector(".js_img").src
            this.sendingDataToBackEnd[index] = {
                  cropArea: [],
                  resource: imageUrl,
                  type: "test_sending_img",
                  order: index
            }
      }

      /**
      * テスト送信するテキストメッセージを取得し、データを成型
      * @override
      */
      #formatTextMessage(element, index){
            const text = element.innerHTML
            this.sendingDataToBackEnd[index] = {
                  resource: text,
                  type: "test_sending_txt",
                  order: index
            }
      }

      /**
      * 管理者IDを取得を取得する
      *  @override
      */
      #getAdminID(){
            return  document.getElementById("js_account_id").value
      }

}