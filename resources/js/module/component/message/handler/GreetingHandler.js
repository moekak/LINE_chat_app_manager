import formDataStateManager from "../../../util/state/FormDataStateManager.js";
import SendingDataServiceInterface from "../SendingDataServiceInterface.js";

export default class GreetingHandler{
      constructor(sendingDataService){
            this.sendingDataService = sendingDataService;
      }
      handle(userIds){
            if(!SendingDataServiceInterface.hasValue("accordion")){
                  this.errorHandle()
            }

            const formDataArray = formDataStateManager.getState()
            // sendMessage のデータを FormData に保存
            if (userIds.length > 0) {
                  // 配列全体を一つのキーで追加する方法
                  this.sendingDataService.formData.append('userIds', JSON.stringify(userIds)); 
            }

            formDataArray.forEach((item, index) => {
                  if(item !== undefined && item.type !== undefined){
                        if (item.type === 'image') {
                              this.sendingDataService.operateImageData(item, index)
                        } else if (item.type === 'text') {
                              this.sendingDataService.operateTextData(item, index)
                        }
                  }
            });
      }
}