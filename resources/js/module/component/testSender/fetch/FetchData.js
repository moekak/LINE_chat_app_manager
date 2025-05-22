import { API_ENDPOINTS } from "../../../../config/apiEndPoint.js";
import { fetchGetOperation } from "../../../util/fetch.js";
import { createTestSenderList } from "../../elementTemplate.js";

export default class FetchData{
      constructor(parent){
            this.parent = parent
            this.userItemWrapper = document.querySelector(".user-item-wrapper")
      }

      async fetchTestUsers(processingManager){
            const response = await fetchGetOperation(API_ENDPOINTS.FETCH_TEST_SENDERS)

            processingManager.onDone()
            if(response["status"] === 201){
                  response["testSenders"].forEach((sender)=>{
                        if(!this.hasTestUser(sender["user_id"])){
                              this.userItemWrapper.insertAdjacentHTML("afterbegin", createTestSenderList(sender));
                        }
                  })
                  this.parent.count.innerHTML = response["testSenders"].length
                  this.parent.resetData()
                  this.parent.setupEventListenersAfterFetch()
            }else{
                  alert("テスト送信ユーザーの取得に失敗しました。再度お試しください。")
                  
            }
            
      }

      hasTestUser(userId){
            const userItems = document.querySelectorAll(".user-item")
            return Array.from(userItems).some(item => {
                  return item.dataset.userId === userId;
            });
      }
}