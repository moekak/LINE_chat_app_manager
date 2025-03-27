// 2. TemplateFormData.js - フォームデータの収集と構築に特化
class TemplateFormData {
    constructor() {
        this.templateName = document.getElementById("template-title");
        this.categoryId = document.getElementById("category-select");
        this.adminId = document.getElementById("js_account_id");
    }

    /**
     * コンテンツブロックからFormDataを構築する
     * @return {Object} - {formData: FormData, hasContent: boolean}
     */
    buildFormData() {
        const formData = new FormData();
        const content_blocks = document.querySelectorAll(".content-block");
        
        // 基本データを追加
        formData.append("template_name", this.templateName.value);
        formData.append("category_id", this.categoryId.value);
        formData.append("admin_id", this.adminId.value);

        let order = 0;
        let text_index = 0;
        let image_index = 0;
        let hasContent = false;

        content_blocks.forEach((block) => {
            if (block.dataset.type === "image") {
                const file = block.querySelector(".file-input").files[0];
                if (!file) return;
                
                hasContent = true;
                const cropArea = block.querySelector(".image-upload").getAttribute("data-crop-area");
                const url = block.querySelector(".image-upload").dataset.url;
                
                formData.append(`image_path[${text_index}][content]`, file);
                formData.append(`image_path[${text_index}][cropData][cropArea]`, cropArea);
                formData.append(`image_path[${text_index}][cropData][url]`, url);
                formData.append(`image_path[${text_index}][order]`, order);
                
                text_index++;
            } else if (block.dataset.type === "text") {
                const content = block.querySelector(".block-textarea").value;
                if (content === "") return;

                hasContent = true;
                formData.append(`content_texts[${image_index}][content]`, content);
                formData.append(`content_texts[${image_index}][order]`, order);
                image_index++;
            }
            order++;
        });

        return { formData, hasContent };
    }
}

export default TemplateFormData;