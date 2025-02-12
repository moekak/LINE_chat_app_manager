class CropperState{
      constructor(cropBoxData, imageData, containerData){
            this.xPercent = 0;
            this.yPercent = 0;
            this.widthPercent = 0;
            this.heightPercent = 0;
            this.imageData = imageData
            this.cropBoxData = cropBoxData
            this.containerData= containerData
      }

      // 選択範囲の位置とサイズを画像全体に対する割合（%）で計算し保存
      // cropBoxData: 選択された範囲の情報
      // imageData: 元画像のサイズ情報
      updatePercentage(){

            const widthRatio = this.imageData.naturalWidth / this.imageData.width;
            const heightRatio = this.imageData.naturalHeight / this.imageData.height;

            const extraPaddingForLeft = (this.containerData.width - this.imageData.width) / 2 ; 
            const extraPaddingForTop = (this.containerData.height - this.imageData.height) / 2 ; 
            
            const correctedLeft = this.cropBoxData.left - extraPaddingForLeft
            const correctedTop = this.cropBoxData.top - extraPaddingForTop


            // 元画像サイズを基準にクロップデータを補正
            this.xPercent = ((correctedLeft  * widthRatio) / this.imageData.naturalWidth) * 100;
            this.yPercent = ((correctedTop  * heightRatio) / this.imageData.naturalHeight) * 100;
            this.widthPercent = (this.cropBoxData.width * widthRatio / this.imageData.naturalWidth) * 100;
            this.heightPercent = (this.cropBoxData.height * heightRatio / this.imageData.naturalHeight) * 100;
      }
      

      // 保存された選択範囲の位置とサイズの割合（%）を返す
      getState(){

            return{
                  xPercent: this.xPercent,
                  yPercent :this.yPercent,
                  widthPercent : this.widthPercent ,
                  heightPercent :this.heightPercent 
            }
      }
}

export default CropperState;