
import {initializeAccountDeletionModal, initializeAccountEditModal, initializeBroadcastMessageModal, initializeSimpleBar, initializeUserModals } from "../component/modalInitializers.js";;
import { handleChatRedirect, initializeAccountStatusManager } from "../component/accountUIOperations.js";
import {createAccountDataRow, createMessageRowForFetch} from "../component/elementTemplate.js";
import { fetchPostOperation } from "./fetch.js";
import socket from "./socket.js";
import accountStateManager from "./state/AccountStateManager.js";
import userStateManager from "./state/UserStateManager.js";
const MESSAGES_PER_PAGE = 20



class InfiniteScroll{
      constructor(element, url, parentElement, fileType){
            this.element = element
            this.hasNoValue = false
            this.isFetchFlag = false
            this.loader = this.element.querySelector(".js_loader")
            this.dataCount = 20
            this.baseUrl = url
            this.parentElement = parentElement
            this.fileType = fileType
            this.accountList = accountStateManager.getState()
            this.userAccount = userStateManager.getState()
            // コンストラクタで定義された this を使用するメソッドをイベントリスナーやコールバックとして使用する場合、bind(this) が必要
            this.element.addEventListener("scroll", this.handleScroll.bind(this))
      }


      async handleScroll(){
            if(!this.hasNoValue && !this.isFetchFlag){
                  this.dataCount = document.querySelectorAll(".js_chatUser_id").length

                  const {scrollTop, scrollHeight, clientHeight} = this.element
                  // 一番下までスクロールしたかを判定
                  if (scrollTop + clientHeight + 50 >= scrollHeight) {

                        this.isFetchFlag = true
                        this.loader.classList.remove("hidden")

                        const data = {
                              "dataCount": this.dataCount,
                              "accountList" : this.fileType == "accountShow" ? this.userAccount : this.accountList
                        }

                        try {
                              const response = await fetchPostOperation(data, this.baseUrl);
                              const hasNoData = response["accountData"] ? response["accountData"].length === 0 : response.length === 0

                              if (hasNoData) {
                                    this.hasNoValue = true; // もうデータがない場合は停止
                                    this.loader.classList.add("hidden")  
                              } else {
                                    if(this.fileType === "accountShow"){
                                          response.forEach((res) => {
                                                this.parentElement.insertAdjacentHTML("beforeend",createMessageRowForFetch(res, res["account_id"]));
                                          });
                                           //ユーザー管理に関連するモーダルの初期化
                                          initializeUserModals(socket)
                                          initializeSimpleBar()
                                          await handleChatRedirect()
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

                                          initializeSimpleBar()

                                          initializeSimpleBar()
                                          await handleChatRedirect()

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

export default InfiniteScroll; 