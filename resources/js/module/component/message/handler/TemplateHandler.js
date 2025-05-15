import { templateImageData } from "../../messageTemplate/DataGenerator.js";

export default class TemplateHandler{
      constructor(sendingDataService){
            this.sendingDataService = sendingDataService;
      }
      handle(userIds){

            console.log(this.sendingDataService.formData);
            
            // sendMessage のデータを FormData に保存
            if (userIds.length > 0) {
                  this.sendingDataService.formData.append('userIds', JSON.stringify(userIds)); 
            }

            const contentBlocks = document.querySelectorAll(".content-block")
            contentBlocks.forEach((block, index)=>{

                  console.log(block.querySelector(".image-upload"));
                  
                  if(block.dataset.type === "text"){
                        const text = block.querySelector(".block-textarea").value
                        this.sendingDataService.formData.append(`messages[${index}]`, text);
                  } else if(block.dataset.type === "image"){
                        const fileData = templateImageData.find(item => item.order == index);

                        // console.log(fileData);
                        // console.log(fileData.content);
                        // console.log(fileData.content.name);
                        this.sendingDataService.formData.append(`images[${index}]`, fileData.content, fileData.content.name);

                        if(block.querySelector(".image-upload").dataset.url){
                              this.sendingDataService.formData.append(`images[${index}][meta]`, JSON.stringify({ url: fileData.cropUrl, cropArea: fileData.cropData }));
                        }
                  }  
            })


            console.log('FormDataの内容ssssssssssss:');
            for (let [key, value] of this.sendingDataService.formData.entries()) {
                  console.log(`${key}: ${value}`);
            }
            

      }
}