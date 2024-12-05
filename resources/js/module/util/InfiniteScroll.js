
import {createMessageRowForFetch} from "../component/elementTemplate.js";
import { fetchGetOperation } from "./fetch.js";

class InfiniteScroll{
      constructor(element, loader){
            this.element = element
            this.hasNoValue = false
            this.isFetchFlag = false
            this.loader = loader

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
                        let parentElement =document.querySelector(".js_table")
                        const start = document.querySelectorAll(".js_chatUser_id").length
                        const admin_id = document.getElementById("js_line_account_id").value 
                        const url = `/api/user/lists/${admin_id}/${start}`
      
                        console.log(start);
                        
                        try {
                              const response = await fetchGetOperation(url);

                              console.log(response);
                              
      
                              if (response.length === 0) {
                                    this.hasNoValue = true; // もうデータがない場合は停止
                                    this.loader.classList.add("hidden")  
                              } else {
                                    response.forEach((res) => {
                                          parentElement.insertAdjacentHTML("beforeend",createMessageRowForFetch(res, res["account_id"], res["uuid"]));
                                    });
                              }
                        } catch (error) {
                              console.error("Failed to fetch data:", error);
                        } finally {
                              this.isFetchFlag = false;
                              this.loader.classList.add("hidden");
                        }
                  }else{
                        // console.log("not bottom");
                        
                        this.loader.classList.add("hidden")  
                  }
            }
      }
}

export default InfiniteScroll; // ESモジュール形式でエクスポート