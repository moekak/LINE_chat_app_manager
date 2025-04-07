import { SYSTEM_URL } from "../../../../config/config.js";
import { createTextBlock } from "../../elementTemplate.js";
import { close_loader, open_loader } from "../../modalOperation.js";
import FormController from "../../ui/FormController.js";
import { templateImageData } from "../DataGenerator.js";
import ImageUploadHandler from "../ImageUploadHandler.js";
import MessageTemplateOperator from "../MessageTemplateOperator.js";
import TemplateBlockManager from "../TemplateBlockManager.js";

class MessageTemplateFormController{
      constructor(targetElement){

            this.targetElement = targetElement
            this.templateContent = targetElement.closest(".template-item");
            this.templateNameElement = this.templateContent.querySelector(".template-title");
            this.templateCategoryElement = this.templateContent.querySelector(".template-category");
            this.templateIdElement = this.templateContent.querySelector(".template_id")
            this.groupIdElement = this.templateContent.querySelector(".group_id")

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
                  this.imageElement = this.contentBlocksWrapper.querySelector(".image_element")
                  this.imageElement.src = `${SYSTEM_URL.IMAGE_URL}/${content.querySelector(".js_image_path").value}`
                  FormController.templateImageStyle(this.imageElement, `${SYSTEM_URL.IMAGE_URL}/${content.querySelector(".js_image_path").value}`)

                  const errorTxt = document.querySelector(".js_error_txt");
                  const imageUploadHandler = new ImageUploadHandler()
                  imageUploadHandler.setupFileInputs(true, errorTxt);

                  // // 画像データ作成


                  
                  const fileInputElementId = this.fileInput.closest(".content-block").dataset.id
                  const numberPart = fileInputElementId.match(/\d+/)[0];


                  templateImageData.push(
                        {
                              "contentUrl" : content.querySelector(".js_image_path").value,
                              "cropData": content.querySelector(".js_image_path").dataset.crop ?? [],
                              "order" : numberPart
                        }
                  )
                        
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

      static initializeEditModal(){
            const editBtns = document.querySelectorAll(".template_edit-btn")
            const tabEdit = document.querySelector(".tab-edit")
            const contentBlocks = document.getElementById("edit-content-blocks")
            const form = document.querySelector(".js_edit_form")
            const messageTemplateOperator = new MessageTemplateOperator()



            editBtns.forEach((btn)=>{
                  btn.addEventListener("click", (e)=>{
                        templateImageData.length = 0


                        contentBlocks.innerHTML = ""
                        messageTemplateOperator.changeElements(contentBlocks, form)
                        messageTemplateOperator.changeIsUpdate()
                        tabEdit.style.display = "none"
                        const targetElement = e.currentTarget
                        
                        const formController = new MessageTemplateFormController(targetElement)
                        formController.setDataToEditInputs()
                        const templateBlockManager = new TemplateBlockManager()
      
                        form.querySelectorAll('.content-block').forEach(block => {
                              templateBlockManager.setupBlockListeners(block)
                        });
      
                        // テンプレート作成画像アップロード
                        MessageTemplateFormController.initializeCropManagement(true)
                        close_loader()
                  })
            })
      }

      static initializeTemplateEditModal(){
            document.querySelector(".template-list").innerHTML = ""
            document.querySelector(".category-buttons").innerHTML = "";
            const modal = document.getElementById("js_template_modal")
            modal.style.zIndex = "985"
            open_loader()
      }

      static initializeCropManagement(isEdit = false){
            // テンプレート作成画像アップロード
            const errorTxt = document.querySelector(".js_error_txt");
            const imageUploadHandler = new ImageUploadHandler()
            imageUploadHandler.setupFileInputs(isEdit, errorTxt);
}




}

export default MessageTemplateFormController;