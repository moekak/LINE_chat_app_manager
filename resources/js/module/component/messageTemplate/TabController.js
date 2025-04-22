import { API_ENDPOINTS } from "../../../config/apiEndPoint.js";
import { SUCCESS_TEXT } from "../../../config/config.js";
import { fetchGetOperation, fetchPostOperation } from "../../util/fetch.js"
import { crateCategoryButtons, createMessageTemplate } from "../elementTemplate.js";
import ButtonController from "../ui/ButtonController.js";
import FormController from "../ui/FormController.js";
import {close_loader, close_loader_template, close_modal, hide_bg, open_loader} from "./../modalOperation.js"
import DataValidator from "./DataValidator.js";
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
                    InitializeInputService.initializeErrorList()
                if(index === 0){
                    const messageTemplateOperator = new MessageTemplateOperator()
                    messageTemplateOperator.changeElements(document.getElementById("create-content-blocks"), document.querySelector(".js_create_form"))
                    document.getElementById("template-edit-form").classList.add("hidden")
                }
                if(index === 1){
                    try{
                        document.getElementById("template-edit-form").classList.add("hidden")
                        MessageTemplateFormController.initializeTemplateEditModal()
                        const modal = document.getElementById("js_template_modal")
                        const response = await fetchGetOperation(`${API_ENDPOINTS.FETCH_TEMPLATE_GET}/${document.getElementById("js_account_id").value}`)

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
                        document.querySelector(".tab-edit").classList.remove("hidden")

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

                        {
                            // 削除キャンセル
                            const cancelBtn = document.getElementById("js_cancel_template_btn")
                            cancelBtn.addEventListener("click", ()=>{
                                document.getElementById("js_template_confirm_modal").classList.add("hidden")
                                document.getElementById("js_template_modal").style.zIndex = 999
                            })
                        }

                        // ローダー出す処理

                        {
                            const btn = document.querySelector(".js_delete_template_from")
                            btn.addEventListener("click", ()=>{
                                open_loader()
                                document.getElementById("js_template_confirm_modal").classList.add("hidden")
                            })

                        }
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

    #filterCategory(){
        const categoryBtns = document.querySelectorAll(".category-btn")
        const templateItems = document.querySelectorAll(".template-item")
        const wrapper = document.getElementById("js_template_list")
        categoryBtns.forEach((btn)=>{
            btn.addEventListener("click",()=>{
                categoryBtns.forEach(btn => btn.classList.remove("active"))
                btn.classList.add("active")
                const category = btn.dataset.category

                if(category === "all"){
                    templateItems.forEach(item => item.classList.remove("hidden"))
                    return
                }

                
                // 選択されたカテゴリーの要素を取得し並び替え
                const filteredItems = Array.from(templateItems).filter(item => item.dataset.id === category);
                const sortedItems = filteredItems.sort((a, b) => {
                    const orderA = parseInt(a.dataset.order) || 0;
                    const orderB = parseInt(b.dataset.order) || 0;
                    return orderA - orderB;
                });

                // 非表示にするアイテム
                templateItems.forEach(item => {
                    if (item.dataset.id !== category) {
                        item.classList.add("hidden");
                    }
                });

                // フォームデータを保持しながら並び替える
                // クローンせずに一時的なデータ保持
                const formData = new Map();
                
                // 各要素のフォームデータを一時保存
                sortedItems.forEach(item => {
                    const inputs = item.querySelectorAll('input, select, textarea');
                    const itemData = new Map();
                    
                    inputs.forEach(input => {
                        if (input.name) {
                            itemData.set(input.name, input.value);
                        }
                    });
                    
                    formData.set(item, itemData);
                    item.classList.remove("hidden");
                });
                
                // 並び替えた順にDOMに再追加
                sortedItems.forEach(item => {
                    wrapper.appendChild(item);
                    
                    // フォームデータを復元
                    const itemData = formData.get(item);
                    if (itemData) {
                        itemData.forEach((value, name) => {
                            const input = item.querySelector(`[name="${name}"]`);
                            if (input) input.value = value;
                        });
                    }
                });


            })
        })
    }
}

export default TabController;