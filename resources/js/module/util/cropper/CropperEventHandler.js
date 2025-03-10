import CropperOverlay from "./CropperOverlay.js";

class CropperEventHandler{
      constructor(changeBtn, cropper){
            this.choices = document.getElementsByName('choice')
            this.changeBtn = changeBtn;
            this.cropper = cropper
      }

      // // 画像編集に関連する全てのイベントリスナーを初期化
      // initializeEvents(){
      //       this.#detectChoiceEvents()
      // }

      // // URLソース選択（ラジオボタン）の変更を監視
      // // 選択状態に応じてURL入力欄の表示/非表示を切り替える
      // #detectChoiceEvents(){
      //       this.choices.forEach((choice)=>{
      //             choice.addEventListener("change", (event) => this.#handleUrlInputToggle(event, this))
      //       })
      // }

      // 画像変更ボタンのクリックイベントを監視
      // クリック時にCropperの編集モードを開始
      changeBtnEvent(){
            this.changeBtn.addEventListener("click", ()=>{
                  let txt = this.changeBtn.innerHTML
                  if(txt === "選択範囲確定"){
                        this.cropper.disableCropper() 
                        this.changeBtn.innerHTML = "選択範囲変更" 
                        this.changeBtn.style.background = "#80808038"  
                  }else{
                        this.cropper.enableCropperEditing()
                        this.changeBtn.innerHTML = "選択範囲確定" 
                        this.changeBtn.style.background = "#fff"   
                  }
            });
      }

      // URL変更要素の表示切替
      static handleUrlInputToggle(event) {
            const url_wrapper = document.getElementById("js_url_setting");
            url_wrapper.classList.toggle("hidden", event.target.value === "off");

            if(event.target.value === "off"){
                  CropperOverlay.updateCropperState(false)
            }else{
                  CropperOverlay.updateCropperState(true)
            }
      }

}


export default CropperEventHandler;