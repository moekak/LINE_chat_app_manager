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

        let order = 0;
        let text_index = 0;
        let image_index = 0;
        let hasContent = false;

        content_blocks.forEach((block) => {
            if (block.dataset.type === "image") {
                const fileInput = block.querySelector(".file-input")
                const file = fileInput.files[0];
                if (file){
                    hasContent = true;
                    const cropArea = block.querySelector(".image-upload").getAttribute("data-crop-area");
                    const url = block.querySelector(".image-upload").dataset.url;
                    
                    formData.append(`image_path[${text_index}][content]`, file);
                    formData.append(`image_path[${text_index}][cropData][cropArea]`, cropArea);
                    formData.append(`image_path[${text_index}][cropData][url]`, url);
                    formData.append(`image_path[${text_index}][order]`, order);
                }else if(fileInput.dataset.image !== ""){
                    hasContent = true;
                    const cropArea = fileInput.dataset.crop;
                    const imageUrl = fileInput.dataset.image

                    
                    formData.append(`image_path[${text_index}][contentUrl]`, imageUrl);
                    formData.append(`image_path[${text_index}][cropData]`, cropArea);
                    formData.append(`image_path[${text_index}][order]`, order);
                }
                

                
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