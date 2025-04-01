import { createTextBlock } from "../../elementTemplate.js";
import TemplateBlockManager from "../TemplateBlockManager.js";

class MessageTemplateFormController{
      constructor(targetElement){
            this.targetElement = targetElement
            this.templateContent = targetElement.closest(".template-item");
            this.templateNameElement = this.templateContent.querySelector(".template-title");
            this.templateCategoryElement = this.templateContent.querySelector(".template-category");

            this.editTemplateName = document.getElementById("edit-template-title")
            this.editCategories = document.querySelectorAll(".edit-category")
            this.editForm = document.getElementById("template-edit-form")

            this.contentsBlock = this.templateContent.querySelectorAll(".js_blockcontents")
            this.contentBlocksWrapper = null
      }

      setDataToEditInputs(){
            this.editForm.classList.remove("hidden")
            this.editTemplateName.value = this.templateNameElement.innerHTML
            this.editCategories.forEach((category)=>{
                  if(category.value == this.templateCategoryElement.dataset.id){
                        category.selected = true
                  }
            })

            const templateBlockManager = new TemplateBlockManager()
            this.contentsBlock.forEach((content)=>{
                  if(content.dataset.type === "text"){
                        console.log("2");
                        this.contentBlocksWrapper = templateBlockManager.addTextBlock(document.getElementById("edit-content-blocks"))
                        this.contentBlocksWrapper.querySelector(".block-textarea").innerHTML = content.querySelector(".js_content_text").value
                        this.contentBlocksWrapper.querySelector(".block-textarea").dataset.id = content.dataset.id
                  }else if(content.dataset.type === "image"){
                        this.contentBlocksWrapper = templateBlockManager.addImageBlock(document.getElementById("edit-content-blocks")) 
                        this.contentBlocksWrapper = templateBlockManager.addTextBlock(document.getElementById("edit-content-blocks"))
                        this.contentBlocksWrapper.querySelector(".block-textarea").innerHTML = content.querySelector(".js_content_text").value
                        this.contentBlocksWrapper.querySelector(".block-textarea").dataset.id = content.dataset.id
                  }
            })

            
      }


}

export default MessageTemplateFormController;