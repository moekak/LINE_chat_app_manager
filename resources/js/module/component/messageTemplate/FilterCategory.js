import { API_ENDPOINTS } from "../../../config/apiEndPoint.js"
import { fetchGetOperation } from "../../util/fetch.js"
import {  createMessageTemplate, createMessageTemplateForAll } from "../elementTemplate.js"
import { close_loader_template, open_loader_template } from "../modalOperation.js"
import DataValidator from "./DataValidator.js"
import MessageTemplateFormController from "./edit/FormController.js"
import TabController from "./TabController.js"

class FilterCategory{
      constructor(activeFilterButton){
            this.activeFilterButton = activeFilterButton
            this.filterButtons = document.querySelectorAll(".category-btn")
            this.templateWrapper = document.getElementById("js_template_list")
            this.orderSubmitBtn = document.getElementById("js_save_order_btn")
            this.categoryId = ""
            this.data
            this.#fetchTemplateData()
      }
      
      #handleDisplayOrderSubmit(){
            this.orderSubmitBtn.addEventListener("click", async(e)=>{
                  open_loader_template()
                  e.preventDefault()
                  const templateOrderInputs = document.querySelectorAll('input[name="template_order[]"]');
                  // 値を格納する配列を作成
                  const templateOrders = [];
                  // 各input要素の値を配列に追加
                  templateOrderInputs.forEach(function(input) {
                        templateOrders.push(input.value);
                  });

                  // ここで取得した値を使って必要な処理を行う
                  // 例：FormDataに格納する場合
                  const formData = new FormData();
                  
                  // 複数の同じ名前のフィールドをFormDataに追加
                  templateOrders.forEach(function(value) {
                        formData.append('template_order[]', value);
                  });

                  try{
                        const response = await fetch(API_ENDPOINTS.FETCH_UPDATE_TEMPLATE_ORDER, {
                              method: 'POST',
                              body: formData,
                        });
                        
                        if (!response.ok) {
                              throw new Error("メッセージテンプレート作成でエラーが発生しました");
                        }

                        const data = await response.json();
                        if(data["status"] === 201){
                              DataValidator.displayCategorySuccessMessage("テンプレート順番の入れ替えに成功しました。")
                              close_loader_template()
                        }else{
                              const dataValidator = new DataValidator()
                              dataValidator.displayErrorList(["テンプレート順番の入れ替えに失敗しました。再度お試しください。"])
                        }
                  }catch(error){
                        const dataValidator = new DataValidator()
                        dataValidator.displayErrorList(["テンプレート順番の入れ替えに失敗しました。再度お試しください。"])
                  }

            })
      }
      
      #changeFilterBtnStyle(){
            this.filterButtons.forEach(btn => btn.classList.remove("active"))
            this.activeFilterButton.classList.add("active")
            this.categoryId  = this.activeFilterButton.dataset.id
            
      }

      async #fetchTemplateData(){
            this.orderSubmitBtn.classList.add("disabled")
            this.#changeFilterBtnStyle()
            this.data = await fetchGetOperation(`${API_ENDPOINTS.FETCH_TEMPLATE_DATA}/${this.categoryId}/${document.getElementById("js_account_id").value}`)

            if(this.data.length > 0){
                  this.orderSubmitBtn.classList.remove("disabled")
            }

            const templateRaw = createMessageTemplate(this.data)
            this.templateWrapper.innerHTML += templateRaw
            document.getElementById("js_loader-template").classList.add("hidden")

            MessageTemplateFormController.initializeEditModal()
            this.#handleDisplayOrderSubmit()

            // テンプレート削除
            {
                  const deleteBtns = document.querySelectorAll(".template_delete_btn")
                  deleteBtns.forEach((btn)=>{
                        btn.addEventListener("click", async ()=>{

                              //削除確認モーダル
                              document.getElementById("js_template_confirm_modal").classList.remove("hidden")
                              const template_id = btn.closest(".template-item").querySelector(".template_id").value
                              document.getElementById("js_delete_templete_id").value = template_id

                              document.getElementById("js_template_modal").style.zIndex = 1
                        })
                  })
            }
      }


      static async getAllTemplateData(){
            const categoryBtns = document.querySelectorAll(".category-btn")
            categoryBtns.forEach((btn)=>{
                  btn.classList.remove("active")
                  if(btn.dataset.category === "all"){
                        btn.classList.add("active")
                  }
            })


            document.querySelector(".order-instructions").classList.add("hidden")
            try{
                  const response = await fetchGetOperation(`${API_ENDPOINTS.FETCH_TEMPLATE_GET}/${document.getElementById("js_account_id").value}`)

                  const templateRaw = createMessageTemplateForAll(response)
                  document.querySelector(".template-list").innerHTML += templateRaw
                  TabController.filterCategory()
                  MessageTemplateFormController.initializeEditModal()
                  document.getElementById("js_loader-template").classList.add("hidden")
            }catch(error){
                  console.log(error);
                  
            }


            
            // テンプレート削除
            {
                  const deleteBtns = document.querySelectorAll(".template_delete_btn")
                  deleteBtns.forEach((btn)=>{
                        btn.addEventListener("click", async ()=>{

                              //削除確認モーダル
                              document.getElementById("js_template_confirm_modal").classList.remove("hidden")
                              const template_id = btn.closest(".template-item").querySelector(".template_id").value
                              document.getElementById("js_delete_templete_id").value = template_id

                              document.getElementById("js_template_modal").style.zIndex = 1
                        })
                  })
            }

      }



      static fetchFilteredData(category, button){
            const submitButn = document.getElementById("js_save_order_btn")
            if(category === "all"){
                  submitButn.classList.add("hidden")
            }else{
                  submitButn.classList.remove("hidden")
            }
            const wrapper = document.getElementById("js_template_list")
            document.getElementById("js_loader-template").classList.remove("hidden")
            wrapper.innerHTML = ""
            if(category === "all"){
                  document.querySelector(".order-instructions").classList.add("hidden")
                  FilterCategory.getAllTemplateData()
            }else{
                  document.querySelector(".order-instructions").classList.remove("hidden")
                  new FilterCategory(button)
            }
      }
}
export default FilterCategory