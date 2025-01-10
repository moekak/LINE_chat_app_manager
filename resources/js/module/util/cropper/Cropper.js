
import CropperOverlay from "./CropperOverlay.js";
import CropperEventHandler from "./CropperEventHandler.js";
import CropperState from "./CropperState.js";
import CropperJS from "cropperjs";

class Cropper {
      constructor(image, changeBtn) {
            this.image = image;
            this.cropperInstance = null; // Cropper.js インスタンスを保持
            this.changeBtn = changeBtn;
            this.choices = document.getElementsByName('choice')
            // Cropper内メソッド、プロパティすべてをthisで受け渡す
            this.cropperHandler = new CropperEventHandler(this.changeBtn, this)
            this.cropperHandler.initializeEvents()
            this.cropperState = null;
      }

      enableCropperEditing() {
            // 画像範囲選択を選択したい際にcropperを有効にする
            if (this.cropperInstance) {
                  this.cropperInstance.enable();
            }
            // オーバーレイ要素をデフォルト(表示)に戻す
            const cropperOverlay = new CropperOverlay()
            cropperOverlay.setVisibility(true)
      }

      // 現在の Cropper インスタンスを破棄して新しい画像をセット
      updateImage(newImage) {
            // 既存のインスタンスを破棄
            if (this.cropperInstance) {
                  this.cropperInstance.destroy();
                  this.cropperInstance = null;
            }

            // 新しい画像要素を設定
            this.image = newImage;

            // 新しい Cropper インスタンスを作成
            this.resizeImage();
      }

      resizeImage() {
            // Cropper.js インスタンスを作成
            this.cropperInstance = new CropperJS(this.image, {
                  viewMode: 0.5, // 画像の範囲内にクロップボックスと画像を制限
                  dragMode: "crop", // クロップ操作を有効にする
                  autoCropArea: 0.5, // 初期のクロップエリアを最大化
                  responsive: true, // 画面サイズに応じてリサイズ対応
                  restore: true, // 元の状態を保持
                  guides: true, // ガイド線を非表示
                  highlight: true, // クロップボックスのハイライトを有効
                  cropBoxResizable: true, // クロップボックスのリサイズを許可
                  cropend: () => {
                        this.changeBtn.classList.remove("disable_btn")
                  },
            });
      }

      getCropperArea(){
            try{

                  const cropBoxData = this.cropperInstance.getCropBoxData(); // 最終的な選択範囲
                  const containerData = this.cropperInstance.getContainerData(); // コンテナのデータ
                  const imageData = this.cropperInstance.getImageData(); // 画像全体の情報
      
                  // 選択範囲の位置とサイズを画像全体に対する割合（%）で計算し保存
                  this.cropperState = new CropperState(cropBoxData, imageData, containerData);
                  this.cropperState.updatePercentage()
                  return this.cropperState.getState()
            }catch(error){
                  alert("画像リンク指定でエラーが発生しました。再度実行してくださう。")
            }
      }


      enableCropper(){
            this.cropperInstance = new CropperJS(this.image, {
                  viewMode: 0.5, // 画像の範囲内にクロップボックスと画像を制限
                  dragMode: "crop", // クロップ操作を有効にする
                  autoCropArea: 0.5, // 初期のクロップエリアを最大化
                  responsive: true, // 画面サイズに応じてリサイズ対応
                  restore: true, // 元の状態を保持
                  guides: true, // ガイド線を非表示
                  highlight: true, // クロップボックスのハイライトを有効
                  cropBoxResizable: true, // クロップボックスのリサイズを許可
            });
      }

      disableCropper(){
            // cropperを無効化にする
            // 選択範囲を色変更
            const cropperOverlay = new CropperOverlay()
            cropperOverlay.setVisibility(false)
            this.cropperInstance.disable();
      }

      destroyCropper(){
            
            this.cropperInstance.destroy();
            this.cropperInstance = null
      }
      getCropperState() {
            // CropperState インスタンスを返す
            return this.cropperState;
      }

}

export default Cropper;
