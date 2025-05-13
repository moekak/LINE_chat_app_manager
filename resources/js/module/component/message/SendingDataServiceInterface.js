
import formDataStateManager from "../../util/state/FormDataStateManager.js"
import BroadcastMessageOperator from "../broadcast/BroadcastMessageOperator.js";
import { close_loader, hide_bg} from "../modalOperation.js"


export default class SendingDataServiceInterface{
      constructor(baseUrl, operationType, modal){

            if (new.target === SendingDataServiceInterface) {
                  throw new Error('インターフェースのインスタンスは作成できません');
            }

            this.baseUrl = baseUrl
            this.operationType = operationType
            this.formData = new FormData();

            this.modal = modal
            this.bg = document.querySelector(".bg")
            this.loader = document.querySelector(".loader")
      }


      modalOperator(){
            this.modal.classList.add("hidden")
            this.loader.classList.remove("hidden")
      }


      operateImageData(item, index){
            // FormDataから画像を取得
            const imageFile = item.formData.get('image');
            if (imageFile) {
                  this.formData.append(`images[${index}]`, imageFile, item.fileName);
            }
            if(item.url && item.cropArea){
                  this.formData.append(`images[${index}][meta]`, JSON.stringify({ url: item.url, cropArea: item.cropArea }));
            }
      }

      operateTextData(item, index){
            this.formData.append(`messages[${index}]`, item.data);
      }


      prepareBroadcastFormData(userIds){
            if(!BroadcastMessageOperator.hasValue("accordion")){
                  this.errorHandle()
            }

            const formDataArray = formDataStateManager.getState()

            // sendMessage のデータを FormData に保存
            if (userIds.length > 0) {
                  // 配列全体を一つのキーで追加する方法
                  this.formData.append('userIds', JSON.stringify(userIds)); 
            }

            formDataArray.forEach((item, index) => {
                  if(item !== undefined && item.type !== undefined){
                        if (item.type === 'image') {
                              this.operateImageData(item, index)
                        } else if (item.type === 'text') {
                              this.operateTextData(item, index)
                        }
                  }
            });
      }

      async submitBroadcastMessageToServer(userIds){
            try{
                  const admin_id = document.getElementById("js_account_id").value
                  this.modalOperator()

                  this.prepareBroadcastFormData(userIds)
                  const response = await fetch(`${this.baseUrl}/${admin_id}`, {
                        method: 'POST',
                        body: this.formData,
                  })

                  if (!response.ok) {
                        alert("一斉送信の作成でエラーが発生しました。もう一度お試しください");
                  }

                  const data = await response.json(); // レスポンスをJSONに変換

                  console.log(data);
                  console.log(this.baseUrl);
                  
                  
                  return data; // JSONデータを返す
            }catch(error){
                  console.log(error);
            }

      }

      async emitBroadcastMessageToSocket(userIds = []){
            try{
                  const response = await this.submitBroadcastMessageToServer(userIds)
                  close_loader()
                  hide_bg()
                  
                  // 成功メッセージを出す処理
                  this.successOperator()
                  this.sendMessageToSocket(response)
            }catch(error){
                  console.log(error);
                  
            }
      }


      // ############################################################################
      // ############################## 抽象メソッド #################################
      // ############################################################################


      /**
       * 非同期処理成功時処理
       * @returns {void}
       */
      successOperator(){
            throw new Error('Method not implemented');
      }

      /**
       * socketサーバーにデータを送信する処理
       * @returns {void}
       */
      sendMessageToSocket(){
            return;
      }


      /**
       * エラーハンドリング処理
       * @returns {void}
       */
      errorHandle(){
            return
      }
      


}