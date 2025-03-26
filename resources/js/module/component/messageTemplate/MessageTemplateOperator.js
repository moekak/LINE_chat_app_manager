import { fetchPostOperation } from "../../util/fetch.js";
import { createImageBlock, createTextBlock } from "../elementTemplate.js";
import ButtonController from "../ui/ButtonController.js";
import ImageUploadHandler from "./ImageUploadHandler.js";
import { API_ENDPOINTS } from "./../../../config/apiEndPoint.js";
import FormController from "../ui/FormController.js";
import { close_loader, open_loader } from "../modalOperation.js";

class MessageTemplateOperator{
    constructor(){
        this.contentBlocks = document.getElementById('content-blocks');
        this.addTextBtn = document.getElementById('add-text');
        this.addImageBtn = document.getElementById('add-image');
        this.previewContainer = document.getElementById('preview-container');
        // カウンター（ユニークなIDを生成するため）
        this.blockCounter = 3 // すでに2つのブロックがあるため3から開始
        this.tabs = document.querySelectorAll('.tab');
        this.tabContents = document.querySelectorAll('.tab-content');
        this.submitTemplateBtn = document.getElementById("js_submit_template_btn")
        this.categoryAddBtn = null
        this.initialize()
    }

    async initialize(){
        // タブ切り替え
        this.#toggleTabs()
        // ボタンのクリックイベントをセットアップ
        this.addTextBtn.addEventListener('click', this.#addTextBlock.bind(this));
        this.addImageBtn.addEventListener('click', this.#addImageBlock.bind(this));

        // 初期ブロックのリスナーをセットアップ
        document.querySelectorAll('.content-block').forEach(block => {
            this.#setupBlockListeners(block);
        });

        this.submitTemplateBtn.addEventListener("click", this.#submitHandler.bind(this))

        this.categoryAddBtn = ButtonController.replaceButton("js_category_add_btn")
        this.categoryAddBtn.addEventListener("click", this.#addCategoryHandler.bind(this))

    }

    async #addCategoryHandler(e){
        e.preventDefault()
        open_loader()
        const categoryName = document.getElementById("js_category_input")
        const adminId = document.getElementById("js_account_id")
        const data = {"category_name": categoryName.value,"admin_id" : adminId.value}
        categoryName.value = ""

        try{
            const response = await fetchPostOperation(data, `${API_ENDPOINTS.FETCH_CREATE_CATEGORY}`)
            console.log(response)

            if(response["message"]){
                alert(response["message"])
            }
            close_loader()
            FormController.populateSelectOptions(response["id"], response["category_name"])


        }catch(error){
            console.log(error);
            
        }

    
        

    }

    async #submitHandler(e){
        e.preventDefault()
        const templateName = document.getElementById("template-title")
        const categoryId = document.getElementById("category-select")
        const content_blocks = document.querySelectorAll(".content-block")

        let data = {
            "template_name": templateName.value,
            "category_id" : categoryId.value,
            "content_texts" : [],
            "image_path" : []
        }
        let order = 0
        let text_index = 0
        let image_index = 0

        content_blocks.forEach((block)=>{
            if(block.dataset.type === "image"){
                const file = block.querySelector(".file-input").files[0]
                const cropArea = block.querySelector(".image-upload").getAttribute("data-crop-area")
                
                const url = block.querySelector(".image-upload").dataset.url
                const cropData = {"cropArea": cropArea, "url": url}
                data["image_path"][text_index] = {"content": file, "cropData": cropData, "order": order}
                text_index ++
            }else if(block.dataset.type === "text"){
                const content = block.querySelector(".block-textarea").value
                data["content_texts"][image_index] = {"content": content, "order": order}
                image_index ++
            } 
            order ++
        })

        const response = await fetchPostOperation(data, API_ENDPOINTS.FETCH_TEMPLATE_CREATE)
        console.log(response);
        

        console.log(data);
        
    }

    // テキストブロックを追加
    #addTextBlock(e){
        e.preventDefault()
        const blockId = `block-${this.blockCounter++}`;
        const textBlock = document.createElement('div');
        textBlock.className = 'content-block text-block';
        textBlock.draggable = true;
        textBlock.dataset.type = 'text';
        textBlock.dataset.id = blockId;
        textBlock.innerHTML = createTextBlock();
    
        this.contentBlocks.appendChild(textBlock);
        this.#setupBlockListeners(textBlock);
    }
    
    // 画像ブロックを追加
    #addImageBlock(e){
        e.preventDefault()
        const blockId = `block-${this.blockCounter++}`;
        const imageBlock = document.createElement('div');
        imageBlock.className = 'content-block image-block';
        imageBlock.draggable = true;
        imageBlock.dataset.type = 'image';
        imageBlock.dataset.id = blockId;

        imageBlock.innerHTML = createImageBlock(this.blockCounter)

        this.contentBlocks.appendChild(imageBlock);
        this.#setupBlockListeners(imageBlock);

        const uploads = document.querySelectorAll(".file-input");
        const errorTxt = document.querySelector(".js_error_txt");
        const templateModal = document.getElementById("js_template_modal");

        const imageUploadHandler = new ImageUploadHandler()
        imageUploadHandler.setupFileInputs(uploads, errorTxt, templateModal);
    }


    #setupBlockListeners(block){
        // 削除ボタンのイベントリスナー
        const deleteBtn = block.querySelector('.delete-block');
        deleteBtn.addEventListener('click', () => {
            block.remove();
        });

    }

    #toggleTabs(){
        this.tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                // アクティブタブの更新
                this.tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // タブコンテンツの表示/非表示
                this.tabContents.forEach(content => {
                    content.style.display = 'none';
                });
                this.tabContents[index].style.display = 'block';
            });
        });
    }


}

export default MessageTemplateOperator;