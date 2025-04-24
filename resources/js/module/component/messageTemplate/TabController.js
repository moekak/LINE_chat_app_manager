
import {close_loader, open_loader} from "./../modalOperation.js"
import MessageTemplateFormController from "./edit/FormController.js";
import InitializeInputService from "./InitializeInputService.js";
import MessageTemplateOperator from "./MessageTemplateOperator.js";
import FilterCategory from "./FilterCategory.js"

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
                    InitializeInputService.initializeErrorList()
                if(index === 0){
                    const messageTemplateOperator = new MessageTemplateOperator()
                    messageTemplateOperator.changeElements(document.getElementById("create-content-blocks"), document.querySelector(".js_create_form"))
                    document.getElementById("template-edit-form").classList.add("hidden")
                }
                if(index === 1){
                    try{
                        document.getElementById("js_template_list").innerHTML = ""
                        document.getElementById("js_loader-template").classList.remove("hidden")
                        const categoryBtns = document.querySelectorAll(".category-btn")
                        categoryBtns.forEach((btn)=>{
                            btn.classList.remove("active")
                            if(btn.dataset.category === "all"){
                                btn.classList.add("active")
                            }
                        })
                        
                        document.getElementById("template-edit-form").classList.add("hidden")
                        MessageTemplateFormController.initializeTemplateEditModal()
                        const modal = document.getElementById("js_template_modal")
                        FilterCategory.getAllTemplateData()
                        modal.style.zIndex = "999"
                        close_loader()
                        document.querySelector(".tab-edit").classList.remove("hidden")

                    }catch(error){
                        console.log(error);
                        
                    }
                    
                }

                if(index === 2){
                    
                    document.querySelector(".js_category_form").classList.remove("hidden")
                    document.querySelector(".js_edit_form").classList.add("hidden")
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

    static filterCategory(){
        const categoryBtns = document.querySelectorAll(".category-btn")
        const wrapper = document.getElementById("js_template_list")
        categoryBtns.forEach((btn)=>{
            const newButton = btn.cloneNode(true)
            btn.replaceWith(newButton)
            newButton.addEventListener("click",(e)=>{

                document.getElementById("js_loader-template").classList.remove("hidden")
                wrapper.innerHTML = ""
                if(e.target.dataset.category === "all"){
                    document.querySelector(".order-instructions").classList.add("hidden")
                    FilterCategory.getAllTemplateData()
                }else{
                    document.querySelector(".order-instructions").classList.remove("hidden")
                    new FilterCategory(newButton)
                }

            })
        })
    }
}

export default TabController;