import { API_ENDPOINTS } from "../../../config/apiEndPoint.js";
import { fetchGetOperation, fetchPostOperation } from "../../util/fetch.js"
import { crateCategoryButtons, createMessageTemplate } from "../elementTemplate.js";
import ButtonController from "../ui/ButtonController.js";
import FormController from "../ui/FormController.js";
import {close_loader, open_loader} from "./../modalOperation.js"
import MessageTemplateFormController from "./edit/FormController.js";
import InitializeInputService from "./InitializeInputService.js";
import MessageTemplateOperator from "./MessageTemplateOperator.js";

// 4. TabController.js - タブ管理に特化
class TabController {
    constructor(tabs, tabContents) {
        this.tabs = tabs;
        this.tabContents = tabContents;
        this.setupListeners();
    }
    

    setupListeners() {
        this.tabs.forEach((tab, index) => {
            const tabElement = document.getElementById(tab.id);
            if (tabElement && !tabElement.dataset.listenerAttached) {
                tabElement.addEventListener('click', async() => {
                    console.log("タブクリック処理");
                    InitializeInputService.initializeErrorList()
                if(index === 0){
                    const messageTemplateOperator = new MessageTemplateOperator()
                    messageTemplateOperator.changeElements(document.getElementById("content-blocks"), document.querySelector(".js_create_from"))
                    document.getElementById("template-edit-form").classList.add("hidden")
                }
                if(index === 1){
                    try{
                        MessageTemplateFormController.initializeTemplateEditModal()
                        const modal = document.getElementById("js_template_modal")

                        const response = await fetchGetOperation(`${API_ENDPOINTS.FETCH_TEMPLATE_GET}/${document.getElementById("js_account_id").value}`)
                        console.log(response);
                        
                        const categories = await fetchPostOperation({"admin_id": document.getElementById("js_account_id").value}, `${API_ENDPOINTS.FETCH_TEMPLATE_CATEGORY}`)
 
        
                        const templateRaw = createMessageTemplate(response)
                        const buttonAll = '<button class="category-btn active" data-category="all">すべて</button>'
                        document.querySelector(".template-list").innerHTML += templateRaw
                        document.querySelector(".category-buttons").innerHTML += buttonAll
                        categories["categories"].forEach((category)=>{
                            const categoriesRaw = crateCategoryButtons(category)
                            document.querySelector(".category-buttons").innerHTML += categoriesRaw
                        })
                        modal.style.zIndex = "999"
                        close_loader()
                        MessageTemplateFormController.initializeEditModal()
                        this.#filterCategory()
                    }catch(error){
                        console.log(error);
                        
                    }
                    
                }
                this.activateTab(index);
                    // 他の処理...
                });
                
                tabElement.dataset.listenerAttached = "true";
            }
        });
    }

    activateTab(index) {
        // アクティブタブの更新
        this.tabs.forEach(t => t.classList.remove('active'));
        this.tabs[index].classList.add('active');
        
        // タブコンテンツの表示/非表示
        this.tabContents.forEach(content => {
            content.style.display = 'none';
        });
        this.tabContents[index].style.display = 'block';
    }

    #filterCategory(){
        const categoryBtns = document.querySelectorAll(".category-btn")
        const templateItems = document.querySelectorAll(".template-item")
        categoryBtns.forEach((btn)=>{
            btn.addEventListener("click",()=>{
                categoryBtns.forEach(btn => btn.classList.remove("active"))
                btn.classList.add("active")
                const category = btn.dataset.category
                console.log(category);
                
                if(category === "all"){
                    templateItems.forEach(item => item.classList.remove("hidden"))
                    return
                }
                const targetTemplateItems = Array.from(templateItems).filter(item => item.dataset.id === category)
                const otherTemplateItems = Array.from(templateItems).filter(item => item.dataset.id !== category)
                otherTemplateItems.forEach(item => item.classList.add("hidden"))
                targetTemplateItems.forEach(item => item.classList.remove("hidden"))

            })
        })
    }
}

export default TabController;