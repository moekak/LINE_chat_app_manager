import { initializeAccountDeletionModal, initializeAccountEditModal, initializeBroadcastMessageModal, initializeSimpleBar } from "../component/accountModalInitializers.js";
import { initializeAccountStatusManager } from "../component/accountUIOperations.js";
import { createAccountDataRow } from "../component/elementTemplate.js";
import {fetchPostOperation } from "./fetch.js";
import accountStateManager from "./AccountStateManager.js";

class DynamicListManager{
      constructor(data, url){
            this.data = data
            this.url = url
      }

      async fetchData(){
            const response = await fetchPostOperation(this.data, this.url)
            response["accountData"].forEach((res)=>{
                  const parentElement = document.querySelector(`.js_parentEl${res["account_status"]}`)
                  parentElement.insertAdjacentHTML("afterbegin", createAccountDataRow(res, response["categories"]));
                  accountStateManager.setState(res["id"])
            })   
             // アカウント編集
            initializeAccountEditModal()
            //一斉送信
            initializeBroadcastMessageModal()
            // アカウント削除
            initializeAccountDeletionModal()
            // ステータス変更
            initializeAccountStatusManager()

            initializeSimpleBar()
            initializeSimpleBar()
      }
}

export default DynamicListManager; 