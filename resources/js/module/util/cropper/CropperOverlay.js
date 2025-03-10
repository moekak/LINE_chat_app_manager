class CropperOverlay {
      
      // スタイル変更したい要素のクラス名の定義
      static SELECTORS = {
            SELECTED_AREA: '.cropper-face',
            LINES: '.cropper-line',
            POINTS: '.cropper-point',
            DOTS: '.cropper-dashed'
      };

      // toggleで変更するときのスタイル
      // (例)visible = true →　display: block, backgroundColor: #fff
      // (例)visible = false →　display: none, backgroundColor: red
      static STYLES = {
            HIDDEN: 'none',
            VISIBLE: 'block',
            SELECTED_COLOR: 'red',
            DEFAULT_COLOR: '#fff'
      };

      #overlayElements = null

      constructor(){
            this.#initializeElements()
      }

      // スタイル変更対象のDOM要素をオブジェクトとして格納
      #initializeElements(){
            this.#overlayElements = {
                  selectedArea: document.querySelector(CropperOverlay.SELECTORS.SELECTED_AREA),
                  lines : document.querySelectorAll(CropperOverlay.SELECTORS.LINES),
                  points : document.querySelectorAll(CropperOverlay.SELECTORS.POINTS),
                  dots : document.querySelectorAll(CropperOverlay.SELECTORS.DOTS)
            }
      }

      // オーバーレイ要素の表示状態を制御するメソッド
      setVisibility(isVisible){
            // 表示状態に応じたスタイル値を設定
            const displayStyle = isVisible ? CropperOverlay.STYLES.VISIBLE: CropperOverlay.STYLES.HIDDEN
            const backgroundColor = isVisible ? CropperOverlay.STYLES.DEFAULT_COLOR : CropperOverlay.STYLES.SELECTED_COLOR

            // 複数要素（lines, points, dots）を一括で処理するための配列
            const targetElements = [this.#overlayElements.lines, this.#overlayElements.points, this.#overlayElements.dots]

            // 各要素グループの表示/非表示を切り替え
            targetElements.forEach((elements)=>{
                  elements.forEach((element)=>{
                        element.style.display = displayStyle
                  })
            })

            // 背景の切り替え
            this.#overlayElements.selectedArea.style.backgroundColor = backgroundColor
      }

      static updateCropperState(isEnabled) {
            const cropper = document.querySelector(".cropper-drag-box");
            const cropperContainer = document.querySelector(".cropper-container");
            const cropperBox = document.querySelector(".cropper-crop-box");

            if (!cropper || !cropperContainer || !cropperBox) {
                  return;
            }

            if (isEnabled) {
                  cropper.classList.add("cropper-crop", "cropper-modal");
                  cropperContainer.classList.add("cropper-bg");
                  cropperBox.style.display = "block";
            } else {
                  cropper.classList.remove("cropper-crop", "cropper-modal");
                  cropperContainer.classList.remove("cropper-bg");
                  cropperBox.style.display = "none";
            }
      }
}


export default CropperOverlay;