import { SYSTEM_URL } from "../../../../config/config.js";
import { createTextBlock } from "../../elementTemplate.js";
import FormController from "../../ui/FormController.js";
import TemplateBlockManager from "../TemplateBlockManager.js";

class MessageTemplateFormController{
      constructor(targetElement){
            this.targetElement = targetElement
            this.templateContent = targetElement.closest(".template-item");
            this.templateNameElement = this.templateContent.querySelector(".template-title");
            this.templateCategoryElement = this.templateContent.querySelector(".template-category");
            this.templateIdElement = document.querySelector(".template_id")
            this.groupIdElement = document.querySelector(".group_id")

            this.editTemplateName = document.getElementById("edit-template-title")
            this.editCategories = document.querySelectorAll(".edit-category")
            this.editForm = document.getElementById("template-edit-form")

            this.contentsBlock = this.templateContent.querySelectorAll(".js_blockcontents")
            this.contentBlocksWrapper = null

            this.blockTextarea = null
            this.editContentBlock = document.getElementById("edit-content-blocks")
            this.fileInput = null
            this.imageElement =null
      }

      #initializeContentBlock(content,templateBlockManager) {

            document.getElementById("js_template_id").value = this.templateIdElement.value
            document.getElementById("js_group_id").value = this.groupIdElement.value
            if (content.dataset.type === "text") {
                  this.contentBlocksWrapper = templateBlockManager.addTextBlock(this.editContentBlock)
                  this.blockTextarea = this.contentBlocksWrapper.querySelector(".block-textarea")
                  this.blockTextarea.innerHTML = content.querySelector(".js_content_text").value
                  this.blockTextarea.dataset.id = content.dataset.id
            } else if (content.dataset.type === "image") {
                  this.contentBlocksWrapper = templateBlockManager.addImageBlock(this.editContentBlock)
                  this.fileInput = this.contentBlocksWrapper.querySelector(".file-input")
                  this.fileInput.dataset.image = content.querySelector(".js_image_path").value
                  this.fileInput.dataset.crop = content.querySelector(".js_image_path").dataset.crop
                  this.imageElement = this.contentBlocksWrapper.querySelector(".image_element")
                  this.imageElement.src = `${SYSTEM_URL.IMAGE_URL}/${content.querySelector(".js_image_path").value}`
                  FormController.templateImageStyle(this.imageElement, `${SYSTEM_URL.IMAGE_URL}/${content.querySelector(".js_image_path").value}`)
            }
            
            return this.contentBlocksWrapper;
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
                  this.#initializeContentBlock(content, templateBlockManager)
            })

            
      }


}

export default MessageTemplateFormController;