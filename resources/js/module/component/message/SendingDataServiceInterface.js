

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




      async submitBroadcastMessageToServer(userIds){
            try{
                  const admin_id = document.getElementById("js_account_id").value
                  this.modalOperator()
                  this.prepareBroadcastFormData(userIds)

                  const formDataObj = Object.fromEntries(this.formData.entries());
                  console.log("FormData contents:", formDataObj);

                  const response = await fetch(`${this.baseUrl}/${admin_id}`, {
                        method: 'POST',
                        body: this.formData,
                  })

                  if (!response.ok) {
                        alert("一斉送信の作成でエラーが発生しました。もう一度お試しください");
                  }

                  const data = await response.json(); // レスポンスをJSONに変換
                  return data; // JSONデータを返す
            }catch(error){
                  console.log(error);
            }

      }


      /**
     *  送信ボタンを押す前の値があるかのチェック
     * @param {String} id - データを表示させてる要素のID
     * @return {boolean} -リストが一つでもあればtrue,それ以外はfalse
      */
      static hasValue(id){
            const accordion = document.getElementById(id)
            const lists = accordion.querySelectorAll(".js_card")
            return lists.length > 0
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
      async emitBroadcastMessageToSocket(userIds = []){
            throw new Error('Method not implemented');
      }

      /**
       * モーダル処理
       * @returns {void}
       */
      modalOperator(){
            throw new Error('Method not implemented');
      }

      /**
       * データを生成する
       * @returns {void}
       */
      prepareBroadcastFormData(userIds){
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