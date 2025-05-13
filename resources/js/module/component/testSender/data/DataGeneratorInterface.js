import { API_ENDPOINTS } from "../../../../config/apiEndPoint.js";
import TestSendingData from "../../message/TestSendingData.js";
import { open_modal } from "../../modalOperation.js";

/**
 * 項目作成のインターフェース
 * @interface
 */
export default class DataGeneratorInterface {
      constructor() {
            if (new.target === DataGeneratorInterface) {
                  throw new Error('インターフェースのインスタンスは作成できません');
            }

            this.testSenderModal
            this.previosModal
            this.userCheckListElement = document.querySelectorAll(".user-select")
            this.userCheckList = []
            
            this.sendingData = new TestSendingData(API_ENDPOINTS.FETCH_TEST_MESSAGE_STORE)
      }

      // ############################################################################
      // ############################## 共通メソッド #################################
      // ############################################################################

      /**
       * テスト送信ユーザーを選択するモーダルを表示する
       * @returns {void}
       */
      dislpayTestSenderModal(){
            open_modal(this.testSenderModal)
            this.previosModal.classList.add("hidden")
            
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