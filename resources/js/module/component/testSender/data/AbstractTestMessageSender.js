import { API_ENDPOINTS } from "../../../../config/apiEndPoint.js";
import { fetchGetOperation } from "../../../util/fetch.js";
import TestSendingData from "../../message/TestSendingData.js";
import ButtonController from "../../ui/ButtonController.js";
import FetchData from "../fetch/FetchData.js";
import ProcessingManager from "../uiController/ProcessingManager.js";
import { TestUserDeleteManager } from "../uiController/TestUserDeleteManager.js";
import UserSelectionManager from "../uiController/UserSelectionManager .js";

/**
 * 項目作成のインターフェース
 * @interface
 */
export default class AbstractTestMessageSender {
      constructor(type, isUpdate = false) {
            if (new.target === AbstractTestMessageSender) {
                  throw new Error('インターフェースのインスタンスは作成できません');
            }



            this.testSenderModal = document.getElementById("js_test_sender")
            this.testSenderButtons = document.querySelectorAll(".js_sending_btn") //テスト送信ボタン
            this.userCheckListElement = document.querySelectorAll(".user-select")
            this.selectAllUsers = document.getElementById("select-all-users") //「すべて選択」チェックボックス
            this.individualSendBtns = document.querySelectorAll(".js_send_individual_btn")
            this.deleteUserBtns = document.querySelectorAll(".js_delete_user-item")
            this.deleteConfirmationModal = document.getElementById("js_delete_test_user")
            this.returnBtn = document.getElementById("js_return_btn")
            this.deleteTestUserBtn = document.querySelector(".delete_test-account-btn")
            this.updateButton = document.querySelector(".js_process")
            this.fixedBg = document.querySelector(".fixed_bg")
            this.cancelButton = document.querySelector(".js_cancel_test_user_delete")
            this.count = document.querySelector(".js_count")
            this.userCheckList = []
            this.previosModal = null;
            this.openTestSenderModalButton = null;

            this.sendingData = new TestSendingData(this, API_ENDPOINTS.FETCH_TEST_MESSAGE_STORE, type, isUpdate)
            this.userSelectionManager = new UserSelectionManager(this)
            this.testUserDeleteManager = new TestUserDeleteManager()
            this.fetchData = new FetchData(this)

            this.updateButton.addEventListener("click", this.#handleUpdateProcess.bind(this))

      }

      initialize(){
            // this.userSelectionManager.initialize()
            this.setupModalButtons(); //モーダル表示ボタンの設定
            this.setupIndividualSendButtons(); // 個別送信ボタンの設定
            this.setupDeleteUserButtons(); //ユーザー削除ボタンの設定
            this.setupTestSendButtons(); //テスト送信ボタンの設定
            this.setupCheckboxListeners(); //チェックボックスリスナーの設定
            this.setupReturnButton(); //戻るボタンの設定
            this.setupSelectAllCheckbox(); // 全選択チェックボックスの設定
            this.setupCancelDelete()
      }

      // ############################################################################
      // ############################## 共通メソッド #################################
      // ############################################################################

      
      setupEventListenersAfterFetch() {
            // DOM要素の再取得
            this.testSenderButtons = document.querySelectorAll(".js_sending_btn");
            this.userCheckListElement = document.querySelectorAll(".user-select");
            this.individualSendBtns = document.querySelectorAll(".js_send_individual_btn");
            this.deleteUserBtns = document.querySelectorAll(".js_delete_user-item");
            this.selectAllUsers = document.getElementById("select-all-users")

            this.setupIndividualSendButtons(); // 個別送信ボタンの設定
            this.setupDeleteUserButtons(); //ユーザー削除ボタンの設定
            this.setupTestSendButtons(); //テスト送信ボタンの設定
            this.setupCheckboxListeners(); //チェックボックスリスナーの設定
            this.setupSelectAllCheckbox()

      }

      /**
       * モーダル表示ボタンの設定
       * @returns {void}
       * @param {HTMLElement} button 
       */
      setupModalButtons(){
            this.openTestSenderModalButton.addEventListener("click", ()=>{
                  this.resetData()
                  this.dislpayTestSenderModal();
            })
      }
      /**
       * 個別送信ボタンの設定
       * @returns {void}
       */
      setupIndividualSendButtons(){
            this.individualSendBtns.forEach((btn)=>{
                  const newButton = ButtonController.replaceButton(btn)
                  newButton.addEventListener("click", (e)=>{
                        this.userCheckList = [e.currentTarget.dataset.userId]
                        UserSelectionManager.checkStyle(newButton.closest(".user-item"))
                        this.sendTestMessages()
                  })
            })
      }

      /**
       * テスト送信ユーザー削除ボタンの設定
       * @returns {void}
       */
      setupDeleteUserButtons(){
            this.deleteUserBtns.forEach((btn)=>{
                  btn.addEventListener("click", (e)=>{
                        const id = e.currentTarget.dataset.userId
                        this.displayConfirmationModal(id)
                  })
            })
      }

      
      /**
       * テスト送信ボタンの設定
       * @returns {void}
       */
      setupTestSendButtons(){
            this.testSenderButtons.forEach((btn)=>{
                  const newButton = ButtonController.replaceButton(btn)
                  newButton.addEventListener("click", ()=>{
                        this.sendTestMessages()
                  }) 
            })
      }


      /**
       * チェックボックスリスナーの設定
       * @returns {void}
       */
      setupCheckboxListeners(){
            this.userCheckListElement.forEach((element)=>{
                  element.addEventListener("change", (event)=>{
                        this.getSenderUserIds(event)
                        this.toggleSendingBtn()
                        this.userSelectionManager.updateSelectedCount()
                  })
            })
      }

      /**
       * 戻るボタンの設定
       * @returns {void}
       */
      setupReturnButton(){
            const newReturnBtn = ButtonController.replaceButton(this.returnBtn)
            newReturnBtn.addEventListener("click", ()=>{
                  console.log("clickされましたん");
                  
                  this.fixedBg.classList.add("hidden")
                  this.cancelTestSendingProcess()
            })
      }

      /**
       * 全選択チェックボックスの設定
       * @returns {void}
       */
      setupSelectAllCheckbox(){
            const userCheckListElement = document.querySelectorAll(".user-select")
            this.selectAllUsers.addEventListener("change", (e)=>{
                  if(e.target.checked == true){
                        this.userCheckList = Array.from(userCheckListElement).map(element => element.dataset.userId) 
                  }else{
                        this.userCheckList = []
                  }
                  this.toggleSendingBtn()
                  this.userSelectionManager.handleInput()
            })
      }

      /**
       * テスト送信ユーザー削除キャンセル処理
       * @returns {void}
       */
      setupCancelDelete(){
            this.cancelButton.addEventListener("click", ()=>{
                  this.fixedBg.classList.add("hidden")
                  this.deleteConfirmationModal.classList.add("hidden")
                  this.testSenderModal.classList.remove("hidden")
            })
      }

      /**
       * すべてのテストユーザーを一覧に表示する
       * 更新ボタンを押すと非同期で新しく作成したテスト送信ユーザーを取得し表示する
       * @returns {void}
       */
      #handleUpdateProcess(){
            if(this.updateButton.classList.contains("done")){
                  const processingManager = ProcessingManager.getInstance();
                  processingManager.onProcess()
                  this.fetchData.fetchTestUsers(processingManager)
            }
      }

      /**
       * 該当するテスト送信ユーザーを削除する処理
       * @returns {void}
       * @param {string} id //テスト送信ユーザーID
       */
      async #handleDeleteTestUser(id){
            const response = await fetchGetOperation(`${API_ENDPOINTS.FETCH_DELETE_TEST_USER}/${id}`)
            console.log(response);
            

            if(response["status"] === 201){
                  this.testUserDeleteManager.deleteTestUserFromDom(response["userId"]) //DOMから該当するテスト送信ユーザーを取り除く
                  this.count.innerHTML = Number(this.count.innerHTML) - 1
                  this.resetData()
            }
            
      }

      /**
       * テスト送信ユーザーを選択するモーダルを表示する
       * @returns {void}
       */
      dislpayTestSenderModal(){
            this.testSenderModal.classList.remove("hidden")
            this.previosModal.classList.add("hidden")
      }

      /**
       * テストユーザー削除する際の確認モーダルを表示する
       * @returns {void}
       */
      displayConfirmationModal(id){
            this.deleteConfirmationModal.classList.remove("hidden")
            this.testSenderModal.classList.add("hidden")
            this.fixedBg.classList.remove("hidden")
            this.deleteTestUserBtn.addEventListener("click", ()=>{
                  this.testUserDeleteManager.modalOperation()
                  this.#handleDeleteTestUser(id)
            })
      }


      /**
       * テスト送信モーダルをキャンセルをして閉じた際に、テスト送信で使用されてるデータをリセットする
       * @returns {void}
       */
      resetData(){
            this.userCheckList = []   
            this.userSelectionManager.resetUi()
      }


      /**
       * テスト送信ユーザーを選択するモーダルを表示する
       * @returns {void}
       */
      cancelTestSendingProcess(){
            console.log(this.previosModal);
            console.log(this.testSenderModal);
            
            
            this.previosModal.classList.remove("hidden")
            this.testSenderModal.classList.add("hidden")
            
      }



      /**
       * テスト送信を行う。DBに一時保存とsocketサーバーにデータを送信する
       * @returns {void}
       */
      sendTestMessages(){
            this.sendingData.emitBroadcastMessageToSocket(this.userCheckList);
            this.sendingDataToBackEnd = []
      }


      /**
       * テスト送信を行うユーザーIDを配列に格納する
       * @returns {void}
       * @param {Event} event - チェックボックスの変更イベント
       */
      getSenderUserIds(event){
            const checkbox = event.target;
            const userId = checkbox.dataset.userId;
            const isChecked = checkbox.checked;

            // チェッボックスにチェックついていたら配列に格納
            if(isChecked){
                  this.userCheckList.push(userId);
            // 配列から削除
            }else{
                  this.userCheckList = this.userCheckList.filter((list)=> String(list) != String(userId));
            } 
      }


      /**
       * テスト送信ボタンをuserIdsがあるかないかで無効化、有効化の切り替えをおこなう
       * @returns {void}
       */
      toggleSendingBtn(){
            const testSenderButtons = document.querySelectorAll(".js_sending_btn") //テスト送信ボタン
            testSenderButtons.forEach((btn)=>{
                  btn.classList.toggle("disabled_btn", this.userCheckList.length == 0) 
            })
            
      }

}