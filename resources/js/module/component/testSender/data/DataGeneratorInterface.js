import { API_ENDPOINTS } from "../../../../config/apiEndPoint.js";
import TestSendingData from "../../message/TestSendingData.js";
import { open_modal } from "../../modalOperation.js";
import UserSelectionManager from "../uiController/UserSelectionManager .js";

/**
 * 項目作成のインターフェース
 * @interface
 */
export default class DataGeneratorInterface {
      constructor(type) {
            if (new.target === DataGeneratorInterface) {
                  throw new Error('インターフェースのインスタンスは作成できません');
            }

            this.testSenderModal = document.getElementById("js_test_sender")
            this.testSenderButtons = document.querySelectorAll(".js_sending_btn") //テスト送信ボタン
            this.previosModal
            this.userCheckListElement = document.querySelectorAll(".user-select")
            this.selectAllUsers = document.getElementById("select-all-users") //「すべて選択」チェックボックス
            this.individualSendBtns = document.querySelectorAll(".js_send_individual_btn")
            this.deleteUserBtns = document.querySelectorAll(".js_delete_user-item")
            this.deleteConfirmationModal = document.getElementById("js_delete_test_user")
            this.returnBtn = document.getElementById("js_return_btn")
            this.userCheckList = []
            

            this.sendingData = new TestSendingData(API_ENDPOINTS.FETCH_TEST_MESSAGE_STORE, type)

            this.userSelectionManager = new UserSelectionManager()
      }

      // ############################################################################
      // ############################## 共通メソッド #################################
      // ############################################################################

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
      displayConfirmationModal(){
            this.deleteConfirmationModal.classList.remove("hidden")
            this.testSenderModal.classList.add("hidden")
      }


      resetData(){
            this.userCheckList = []   
            this.userSelectionManager.resetUi()
      }


      /**
       * テスト送信ユーザーを選択するモーダルを表示する
       * @returns {void}
       */
      cancelTestSendingProcess(){
            this.previosModal.classList.remove("hidden")
            this.testSenderModal.classList.add("hidden")
            
      }



      /**
       * テスト送信を行う。DBに一時保存とsocketサーバーにデータを送信する
       * @returns {void}
       */
      sendTestMessages(){
            this.sendingData.emitBroadcastMessageToSocket(this.userCheckList);
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
            this.testSenderButtons.forEach((btn)=>{
                  btn.classList.toggle("disabled_btn", this.userCheckList.length == 0) 
            })
            
      }


      // ############################################################################
      // ############################## 抽象メソッド #################################
      // ############################################################################

      /**
       * 初期化処理
       * @returns {void}
       */
      initialize(){
            throw new Error('Method not implemented');
      }

            
      /**
       * テスト送信で送信するメッセージを取得する
       * @returns {void}
       */
      getSendingData() {
            throw new Error('Method not implemented');
      }

      /**
      * テスト送信する画像メッセージを取得し、データを成型
      *  @returns {void}
      */
      #formatImageMessage(){
            throw new Error('Method not implemented');
      }

      /**
      * テスト送信するテキストメッセージを取得し、データを成型
      *  @returns {void}
      */
      #formatTextMessage(){
            throw new Error('Method not implemented');
      }

      /**
      * 管理者IDを取得する
      *  @returns {string} 管理者ID 例: 4
      */
      #getAdminID(){
            throw new Error('Method not implemented');
      }



}