import { templateImageData } from "../../messageTemplate/DataGenerator.js";

export default class TemplateHandler{
      constructor(sendingDataService){
            this.sendingDataService = sendingDataService;
      }
      handle(userIds){

            // sendMessage のデータを FormData に保存
            if (userIds.length > 0) {
                  this.sendingDataService.formData.append('userIds', JSON.stringify(userIds)); 
            }

            const blockWrapper = this.sendingDataService.parent.isUpdate ? document.getElementById("edit-content-blocks") : document.getElementById("create-content-blocks")
            const contentBlocks = blockWrapper.querySelectorAll(".content-block")


            contentBlocks.forEach((block, index)=>{
                  if(block.dataset.type === "text"){
                        const text = block.querySelector(".block-textarea").value

                        if(text == 0 || text == ""){
                              return
                        }
                        this.sendingDataService.formData.append(`messages[${index}]`, text);

                  } else if(block.dataset.type === "image"){
                        const fileInputElementId = block.dataset.id
                        const numberPart = fileInputElementId.match(/\d+/)[0];
                        const fileData = templateImageData.find(item => item.order == numberPart);

                        if(fileData){
                              // テンプレート新規
                              if(fileData.content){
                                    this.sendingDataService.formData.append(`images[${index}]`, fileData.content);
                                    if(block.querySelector(".image-upload").dataset.url){
                                          this.sendingDataService.formData.append(`images[${index}][meta]`, JSON.stringify({ url: fileData.cropUrl, cropArea: fileData.cropData }));
                                    }  
                              // テンプレート更新
                              }else {
                                    this.sendingDataService.formData.append(`images[${index}][content]`, fileData.contentUrl);

                                    if(fileData.cropData){
                                          this.sendingDataService.formData.append(`images[${index}][meta]`, fileData.cropData);
                                    }  
                              }
                        }
                        
                  }  
            })

                  // const formDataObj = Object.fromEntries(this.sendingDataService.formData.entries());
                  // console.log("FormData contents:", formDataObj);

                  // debugger
      }
}