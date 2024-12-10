
import { initializeAccountDeletionModal, initializeAccountEditModal, initializeBroadcastMessageModal } from "../component/accountModalInitializers.js";
import { initializeAccountStatusManager } from "../component/accountUIOperations.js";
import {createAccountDataRow, createMessageRowForFetch} from "../component/elementTemplate.js";
import { fetchGetOperation } from "./fetch.js";
const MESSAGES_PER_PAGE = 20

class InfiniteScroll{
      constructor(element, url, parentElement, fileType){
            this.element = element
            this.hasNoValue = false
            this.isFetchFlag = false
            this.loader = this.element.querySelector(".js_loader")
            this.dataCount = 25
            this.baseUrl = url
            this.parentElement = parentElement
            this.fileType = fileType

            // コンストラクタで定義された this を使用するメソッドをイベントリスナーやコールバックとして使用する場合、bind(this) が必要
            this.element.addEventListener("scroll", this.handleScroll.bind(this))
      }


      async handleScroll(){
            if(!this.hasNoValue && !this.isFetchFlag){
                  const {scrollTop, scrollHeight, clientHeight} = this.element
                  // 一番下までスクロールしたかを判定
                  if (scrollTop + clientHeight + 50 >= scrollHeight) {

                        this.isFetchFlag = true
                        this.loader.classList.remove("hidden")
                        const url = `${this.baseUrl}/${this.dataCount}`
      
                        try {
                              const response = await fetchGetOperation(url);

                              console.log(response);

                              if (response.length === 0 || response["accountData"].length === 0) {
                                    this.hasNoValue = true; // もうデータがない場合は停止
                                    this.loader.classList.add("hidden")  
                              } else {
                                    if(this.fileType === "accountShow"){
                                          response.forEach((res) => {
                                                this.parentElement.insertAdjacentHTML("beforeend",createMessageRowForFetch(res, res["account_id"], res["uuid"]));
                                          });
                                    }else{
                                          response["accountData"].forEach((res)=>{
                                                this.parentElement.insertAdjacentHTML("beforeend", createAccountDataRow(res, response["categories"]));
                                          })   
                                           // アカウント編集
                                          initializeAccountEditModal()
                                          //一斉送信
                                          initializeBroadcastMessageModal()
                                          // アカウント削除
                                          initializeAccountDeletionModal()
                                          // ステータス変更
                                          initializeAccountStatusManager()
                                    }
                              }
                        } catch (error) {
                              console.error("Failed to fetch data:", error);
                        } finally {
                              this.isFetchFlag = false;
                              this.dataCount = this.dataCount + MESSAGES_PER_PAGE
                              this.loader.classList.add("hidden");
                        }
                  }else{
                        this.loader.classList.add("hidden")  
                  }
            }
      }
}

export default InfiniteScroll; // ESモジュール形式でエクスポート