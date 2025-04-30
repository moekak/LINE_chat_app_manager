import { templateImageData } from "./DataGenerator.js";

// 2. TemplateFormData.js - フォームデータの収集と構築に特化
class TemplateFormData {
    constructor(form) {

        this.templateName = form.querySelector(".template-title");
        this.categoryId = form.querySelector(".category-select");
        this.adminId = document.getElementById("js_account_id");
        this.form = form
        this.text_index = 0
        this.image_index = 0;
        this.hasContent = false;

    }

    /**
     * エスケープ処理
     * @param {string} - エスケープする前の文字列
     * @return {string} - エスケープされた文字列
     */
    escapeHtml(str) {
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    /**
     * コンテンツブロックからFormDataを構築する
     * @return {Object} - {formData: FormData, hasContent: boolean}
     */
    buildFormData() {

        const formData = new FormData();
        const parentElement = this.form.querySelector(".content-blocks")
        const content_blocks = parentElement.querySelectorAll(".content-block");


        // 基本データを追加
        formData.append("template_name", this.escapeHtml(this.templateName.value));
        formData.append("category_id", this.categoryId.value);
        formData.append("admin_id", this.adminId.value);
        formData.append("template_id", document.getElementById("js_template_id").value ?? "");
        formData.append("group_id", document.getElementById("js_group_id").value ?? "");


        content_blocks.forEach((block) => {
            
            if (block.dataset.type === "image") {
                const fileInput = block.querySelector(".file-input")
                const fileInputElementId = fileInput.closest(".content-block").dataset.id
                const numberPart = fileInputElementId.match(/\d+/)[0];

                const fileData = templateImageData.find(item => item.order === numberPart);
                

                if(fileData){
                    if (fileData["content"]){
                        this.hasContent = true;
                        
                        formData.append(`image_path[${this.image_index}][content]`, fileData["content"]);
                        formData.append(`image_path[${this.image_index}][cropData][cropArea]`, fileData["cropData"]);
                        formData.append(`image_path[${this.image_index}][cropData][url]`, fileData["cropUrl"]);
                        formData.append(`image_path[${this.image_index}][order]`, fileData["order"]);
                    }else{

                        this.hasContent = true;
                        formData.append(`image_path_update[${this.image_index}][contentUrl]`, fileData["contentUrl"]);
                        formData.append(`image_path_update[${this.image_index}][cropData]`, fileData["cropData"]);
                        formData.append(`image_path_update[${this.image_index}][order]`, fileData["order"]);
                    }
                    this.image_index++;
                }else{
                    return
                }

            } else if (block.dataset.type === "text") {
                const content = this.escapeHtml(block.querySelector(".block-textarea").value.trim());
                const order = block.querySelector(".block-textarea").closest(".content-block").dataset.id
                const numberPart = order.match(/\d+/)[0];
                if (content === "") return;

                this.hasContent = true;
                formData.append(`content_texts[${this.text_index}][content]`, content);
                formData.append(`content_texts[${this.text_index}][order]`, numberPart);
                this.text_index++;
            }
        });

        return { formData, hasContent: this.hasContent };
    }
}

export default TemplateFormData;