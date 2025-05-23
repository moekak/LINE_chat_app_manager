export default class ProcessingManager {
      // プライベート静的変数でインスタンスを保持
      static #instance = null;

      /**
     * コンストラクタ - 直接のインスタンス化を防ぐため、プライベートにする
     * @private
     */
      constructor() {
            // すでにインスタンスが存在する場合はエラーをスロー
            if (ProcessingManager.#instance) {
                  throw new Error("ProcessingManagerは直接インスタンス化できません。getInstance()を使用してください。");
            }
            
            this.processWrapper = document.querySelector(".js_process");
            this.spinning = document.querySelector(".js_spinning");
            this.processText = this.processWrapper.querySelector("span"); // getElementsByTagNameではなくquerySelectorを使用
            
            // このインスタンスを保持
            ProcessingManager.#instance = this;
      }

      // DOM要素を再取得するメソッド
      updateProcessWrapper() {
            this.processWrapper = document.querySelector(".js_process");
      }
      
      /**
     * シングルトンインスタンスを取得するための静的メソッド
     * @returns {ProcessingManager} シングルトンインスタンス
     */
      static getInstance() {
            // インスタンスが存在しない場合は新しく作成
            if (!ProcessingManager.#instance) {
                  ProcessingManager.#instance = new ProcessingManager();
            }
            
            return ProcessingManager.#instance;
      }

      /**
       * 処理中の状態に設定
      */
      onProcess() {
            // 念のため最新のDOM要素を取得
            this.updateProcessWrapper();
            
            if (this.processWrapper) {
                  this.processWrapper.classList.remove("done");
                  this.processWrapper.classList.add("processing");
                  this.spinning.classList.add("fa-spin");
                  this.processText.textContent = "処理中..."; // innerHTMLではなくtextContentを使用
            }
      }
      
      /**
       * 処理完了の状態に設定
       */
      onDone() {
            this.updateProcessWrapper();
            
            if (this.processWrapper) {
                  this.processWrapper.classList.remove("processing");
                  this.processWrapper.classList.add("done");
                  this.spinning.classList.remove("fa-spin");
                  this.processText.textContent = "更新"; // innerHTMLではなくtextContentを使用
            }
      }
}