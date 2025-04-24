import TabController from "./TabController.js";
import TemplateBlockManager from "./TemplateBlockManager.js";
import TemplateFormData from "./TemplateFormData.js";
import ImageUploadHandler from "./ImageUploadHandler.js";
import DataValidator from "./DataValidator.js";
import TemplateApiService from "./TemplateApiService.js";
import ButtonController from "../ui/ButtonController.js";
import { ERROR_TEXT, SUCCESS_TEXT } from "../../../config/config.js";
import { close_loader, hide_bg, open_modal } from "../modalOperation.js";


class MessageTemplateOperator {
    constructor(isUpdate = false) {
        // DOM要素
        this.contentBlocks = document.getElementById('create-content-blocks');
        this.addTextBtns = document.querySelectorAll('.add-text');
        this.addImageBtns = document.querySelectorAll('.add-image');
        this.previewContainer = document.getElementById('preview-container');
        this.submitTemplateBtns = document.querySelectorAll(".js_submit_template_btn");
        this.tabs = document.querySelectorAll('.tab');
        this.tabContents = document.querySelectorAll('.tab-content');
        this.templateModal = document.getElementById("js_template_modal")
        this.categoryAddBtn = null;
        this.form = document.querySelector(".js_create_form")
        this.isUpdate = isUpdate
        // コンポーネント
        this.formData;
        this.blockManager = new TemplateBlockManager();
        this.tabController = new TabController(this.tabs, this.tabContents);
        this.imageUploadHandler = new ImageUploadHandler()
        this.orderSubmitBtn = document.getElementById("js_save_order_btn")
        
        this.initialize();
    }

    changeElements(contentBlock, form){
        this.contentBlocks = contentBlock
        this.form = form
    }
    changeIsUpdate(){
        this.isUpdate = true
    }

    resetBlockCounter(){
        this.blockManager.resetBlockCounter()
        
    }
    initialize() {
        // ボタンイベントのセットアップ
        this.addTextBtns.forEach((btn)=>{
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            newBtn.addEventListener('click', this.handleAddTextBlock.bind(this));
        })
        this.addImageBtns.forEach((btn)=>{
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            newBtn.addEventListener('click', this.handleAddImageBlock.bind(this));
        })
        
        this.submitTemplateBtns.forEach((btn)=>{
            const newBtn = ButtonController.replaceButton(btn.id)
            newBtn.addEventListener("click", this.handleSubmit.bind(this));
        })

        // テンプレート並び順変更処理
        const newBtn = this.orderSubmitBtn.cloneNode(true)
        this.orderSubmitBtn.replaceWith(newBtn)
        newBtn.addEventListener("click", this.handleOrderSubmit.bind(this))
        
        
        // 初期ブロックのリスナーをセットアップ
        document.querySelectorAll('.content-block').forEach(block => {
            this.blockManager.setupBlockListeners(block);
        });
    }


    handleAddTextBlock(e) {
        e.preventDefault();

        const newBlock = this.blockManager.addTextBlock(this.contentBlocks);
        this.blockManager.setupBlockListeners(newBlock);
    }

    handleAddImageBlock(e) {
        e.preventDefault();

        const newBlock = this.blockManager.addImageBlock(this.contentBlocks);
        this.blockManager.setupBlockListeners(newBlock);

        // 画像アップロードハンドラーのセットアップ
        const uploads = document.querySelectorAll(".file-input");
        const errorTxt = document.querySelector(".js_error_txt");
        
        this.imageUploadHandler.setupFileInputs(this.isUpdate, uploads, errorTxt);

    }

    handleOrderSubmit(e){
        // e.preventDefault()
        // const templateOrderInputs = document.querySelectorAll(".template_order");

        // console.log(templateOrderInputs);
        
        // // FormDataオブジェクトを作成
        // const formData = new FormData();

        // // 各input要素の値をFormDataに追加
        // templateOrderInputs.forEach((input) => {
        //     formData.append("template_order[]", input.value);
        // });

        // // FormDataの内容を確認（デバッグ用）
        // for (let pair of formData.entries()) {
        //     console.log(pair[0] + ': ' + pair[1]);
        // }
        

        
        
    }


    async handleSubmit(e) {
        e.preventDefault();
        this.formData = new TemplateFormData(this.form);
        
        // フォームデータ構築とバリデーション
        const { formData, hasContent } = this.formData.buildFormData();

        // エラー表示DOMを空にする
        document.getElementById("js_error_list").innerHTML = ""
        
        // データバリデーション
        const dataValidator = new DataValidator(
            this.formData.templateName, 
            this.formData.categoryId, 
            hasContent
        );
        
        if (dataValidator.hasInvalidData()) {
            dataValidator.displayErrorMessages();
            return;
        }

        try {
            const response = await TemplateApiService.createTemplate(formData, this.isUpdate);


            if (response["status"] === 500) {
                open_modal(this.templateModal)
                dataValidator.displayErrorList([ERROR_TEXT.CREATE_TEMPLATE_ERROR])
            }else if(response["status"] === 422){
                open_modal(this.templateModal)
                dataValidator.displayErrorList(DataValidator.getAllValidationErrorMessages(response))
            }else if(response["status"] === 201){
                document.querySelector(".fixed_bg").classList.add("hidden")
                close_loader()
                hide_bg()
                dataValidator.displaySuccessMessage(SUCCESS_TEXT.CREATE_TEMPLATE_SUCCESS)
            }
            
        } catch (error) {
            console.log(error);
            
            alert("メッセージテンプレート作成中にエラーが発生しました。再度お試しください。");
        }
    }
}

export default MessageTemplateOperator;