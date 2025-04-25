import { API_ENDPOINTS } from "../../../config/apiEndPoint.js"
import { fetchPostOperation } from "../../util/fetch.js"
import { addCategoryButton, crateCategoryButton, editCategoryButton } from "../elementTemplate.js"
import { close_loader_template, open_loader_template } from "../modalOperation.js"
import DataValidator from "./DataValidator.js"
import InitializeInputService from "./InitializeInputService.js"

export default class Uicontroller{
      constructor(){
            this.editForm = document.getElementById("template-edit-form")
            this.templateList = document.querySelector(".tab-edit")
            this.templateWrapper = document.getElementById("js_template_list")
            this.categoryBtns = document.querySelectorAll(".category-btn")
      }

      showTemplateLists(){
            this.editForm.classList.add("hidden")
            this.templateList.style.display = "block"
      }

      hideRemovedTemplate(template_id){
            const templateItems = document.querySelectorAll(".template-item")
            templateItems.forEach((item)=>{
                  const templateId = item.querySelector(".template_id").value
                  if(templateId == template_id){
                        this.templateWrapper.removeChild(item)
                        return
                  }
            })
      }

      getActiveFilterCategory(){
            return Array.from(this.categoryBtns).find(btn => btn.classList.contains("active"));
      }

      
      static initializeCategoryInput(){
            document.getElementById("js_category_input").value = ""
      }

      static changeEditCategoryStyle(){
            const categoryEditBtns = document.querySelectorAll(".edit-category-btn")
            categoryEditBtns.forEach((btn)=>{
                  btn.addEventListener("click", (e)=>{
                        e.preventDefault()
                        document.getElementById("js_template_list").innerHTML = ""
                        const inputElement = btn.closest(".category-item-row").querySelector(".category-edit-input")
                        const saveBtn = btn.closest(".category-actions").querySelector(".save-category-btn")

                        inputElement.classList.remove("disabled")
                        inputElement.readOnly = false;
                        saveBtn.classList.remove("disabled")
                  })
            })


            const cancelBtns = document.querySelectorAll(".cancel-edit-btn")
            cancelBtns.forEach((btn)=>{
                  btn.addEventListener("click", ()=>{
                        const inputElement = btn.closest(".category-item-row").querySelector(".category-edit-input")
                        const saveBtn = btn.closest(".category-actions").querySelector(".save-category-btn")

                        inputElement.classList.add("disabled")
                        inputElement.readOnly = true;
                        saveBtn.classList.add("disabled")
                  })
            })
      }
      
      static editCategoryProcess(){
            const saveBtn =document.querySelectorAll(".save-category-btn")
            saveBtn.forEach((btn)=>{
                  btn.addEventListener("click", async()=>{
                        const dataElement = btn.closest(".category-item-row")
                        if(dataElement.querySelector(".category-edit-input").value.length == 0){
                              document.getElementById("js_error_list").innerHTML = ""
                              const dataValidator = new DataValidator()
                              dataValidator.displayErrorList(["カテゴリー名を入力してください。"])
                              return
                        }else{
                              InitializeInputService.initializeErrorList() 
                        }

                        open_loader_template()
                        const inputElement = dataElement.querySelector(".category-edit-input")
                        const sendingData = {
                              "id": dataElement.querySelector(".js_category_id").value,
                              "admin_id" : dataElement.querySelector(".js_admin_id").value,
                              "category_name_edit": inputElement.value
                        }


                        const response = await fetchPostOperation(sendingData, API_ENDPOINTS.FETCH_CATEGORY_EDIT)

                        if(response["status"] == "201"){

                              console.log(response);
                              
                              DataValidator.displayCategorySuccessMessage("カテゴリーの編集に成功しました")
                              Uicontroller.displayUpdateCategoryName(btn, inputElement, response["category"]["name"])
                              Uicontroller.addUpdatedCategoryToOptionElement(response["category"])
                              Uicontroller.addUpdatedCategoryButtonToFilter(response["category"])
                        }else{
                              const dataValidator = new DataValidator()
                              dataValidator.displayErrorList(["カテゴリーの編集に失敗しました。お手数ですが、もう一度お試しください。"])
                        }
                        close_loader_template()
                        
                        
                  })
            })
      }

      static displayUpdateCategoryName(btn, inputElement, name){
            inputElement.classList.add("disabled")
            btn.classList.add("disabled")
            inputElement.value = name
      }

      static addCategoryToOptionElement(category){
            const selectElement = document.getElementById("category-select")
            selectElement.innerHTML += crateCategoryButton(category)
            const selectElementForEdit = document.getElementById("edit-category-select")
            selectElementForEdit.innerHTML += editCategoryButton(category)
      }

      static addUpdatedCategoryToOptionElement(category){
            const options = document.querySelectorAll(".category-option")
            const targetoption = Array.from(options).find((option)=> option.value == category["id"])
            targetoption.innerHTML = category["name"]


            const editOptions = document.querySelectorAll(".edit-category")
            const targetEditOption = Array.from(editOptions).find((option)=> option.value == category["id"])
            targetEditOption.innerHTML = category["name"]


      }
      static addCategoryButtonToFilter(category){
            const wrapper = document.querySelector(".category-buttons")
            wrapper.innerHTML += addCategoryButton(category)
      }

      static addUpdatedCategoryButtonToFilter(category){
            const categories = document.querySelectorAll(".category-btn")
            const targetCategory = Array.from(categories).find((target)=> target.dataset.id == category["id"])


            categories.forEach((category)=>{
                  console.log(category);
                  
            })
            console.log(category["id"]);


            if(targetCategory){
                  targetCategory.innerHTML = category["name"]
                  targetCategory.dataset.category = category["name"]
            }
      }


      
}