
import { getCurrentTimeFormatted } from "../../../util/formatDate.js";
import UserSelectionManager from "../uiController/UserSelectionManager .js";
import DataGeneratorInterface from "./DataGeneratorInterface.js";

export default class GreetingMessageGenerator extends DataGeneratorInterface{
      constructor(){
            super("greeting"); // 親クラスのコンストラクタを呼び出す
            this.previosModal = document.getElementById("js_messageSetting_modal")
            this.openTestSenderModalButton = document.getElementById('js_sender_list') //テスト送信者モーダルを表示するボタ

            this.sendingDataToBackEnd = []
            this.initialize()
      }

      initialize(){
            this.openTestSenderModalButton.addEventListener("click", ()=>{
                  this.resetData()
                  this.dislpayTestSenderModal();
                  this.getSendingData();
            })

            // 送信アイコンを押してのテスト送信処理
            this.individualSendBtns.forEach((btn)=>{
                  btn.addEventListener("click", (e)=>{
                        this.userCheckList = [e.currentTarget.dataset.userId]
                        UserSelectionManager.checkStyle(btn.closest(".user-item"))
                        this.sendTestMessages()
                  })
            })

            // テストユーザー削除処理
            this.deleteUserBtns.forEach((btn)=>{
                  btn.addEventListener("click", ()=>{
                        this.displayConfirmationModal()
                  })
            })

            // 
            this.testSenderButtons.forEach((btn)=>{
                  btn.addEventListener("click", ()=>{
                        this.sendTestMessages()
                  }) 
            })
            
            this.userCheckListElement.forEach((element)=>{
                  element.addEventListener("change", (event)=>{
                        this.getSenderUserIds(event)
                        this.toggleSendingBtn()
                  })
            })

            this.returnBtn.addEventListener("click", ()=>{
                  this.cancelTestSendingProcess()
            })

            this.selectAllUsers.addEventListener("change", (e)=>{
                  if(e.target.checked == true){
                        this.userCheckList = Array.from(this.userCheckListElement).map(element => element.dataset.userId) 
                  }else{
                        this.userCheckList = []
                  }
                  this.toggleSendingBtn()
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