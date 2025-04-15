import { templateImageData } from "./DataGenerator.js";

// 2. TemplateFormData.js - フォームデータの収集と構築に特化
class TemplateFormData {
    constructor(form) {

        this.templateName = form.querySelector(".template-title");
        this.categoryId = form.querySelector(".category-select");
        this.adminId = document.getElementById("js_account_id");
        this.form = form

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
        formData.append("template_name", this.templateName.value);
        formData.append("category_id", this.categoryId.value);
        formData.append("admin_id", this.adminId.value);
        formData.append("template_id", document.getElementById("js_template_id").value ?? "");
        formData.append("group_id", document.getElementById("js_group_id").value ?? "");



        let text_index = 0;
        let image_index = 0;
        let hasContent = false;

        content_blocks.forEach((block) => {
            
            if (block.dataset.type === "image") {
                const fileInput = block.querySelector(".file-input")
                const fileInputElementId = fileInput.closest(".content-block").dataset.id
                const numberPart = fileInputElementId.match(/\d+/)[0];

                const fileData = templateImageData.find(item => item.order === numberPart);
                

                if(fileData){
                    if (fileData["content"]){
                        hasContent = true;
                        
                        formData.append(`image_path[${image_index}][content]`, fileData["content"]);
                        formData.append(`image_path[${image_index}][cropData][cropArea]`, fileData["cropData"]);
                        formData.append(`image_path[${image_index}][cropData][url]`, fileData["cropUrl"]);
                        formData.append(`image_path[${image_index}][order]`, fileData["order"]);
                    }else{

                        hasContent = true;
                        formData.append(`image_path_update[${image_index}][contentUrl]`, fileData["contentUrl"]);
                        formData.append(`image_path_update[${image_index}][cropData]`, fileData["cropData"]);
                        formData.append(`image_path_update[${image_index}][order]`, fileData["order"]);
                    }
                }else{
                    return
                }
                image_index++;
            } else if (block.dataset.type === "text") {
                const content = block.querySelector(".block-textarea").value;
                const order = block.querySelector(".block-textarea").closest(".content-block").dataset.id
                const numberPart = order.match(/\d+/)[0];
                if (content === "") return;

                hasContent = true;
                formData.append(`content_texts[${text_index}][content]`, content);
                formData.append(`content_texts[${text_index}][order]`, numberPart);
                text_index++;
            }
        });

        return { formData, hasContent };
    }
}

export default TemplateFormData;