class FormDataStateManager{
    constructor(){
        if (!FormDataStateManager.instance) {
              this.formDataArray = []; // 初期状態
              FormDataStateManager.instance = this; // インスタンスを保存
        }
        return FormDataStateManager.instance; // 常に同じインスタンスを返す
    }

    /**
     * 全体の状態を取得
     * @returns {Array} - formDataArray の現在の状態
     */
    getState(){
        return this.formDataArray;
    }

    /**
     * 指定したインデックスのデータを取得
     * @param {number} index - データのインデックス
     * @returns {Object|null} - 指定したインデックスのデータ
     */
    getItem(index) {
        return this.formDataArray[index] || null;
    }

    reOrderItem(index, value){
        this.formDataArray[index] = value
    }

    addData(data){
        this.formDataArray.push(...data)
    }


    /**
     * 指定されたindexのデータを削除
     * @param {number} index - データのインデックス
     */
    removeItem(index) {
        this.formDataArray.splice(index, 1); // 配列からindex番目を削除  
    }
    resetItem() {
        this.formDataArray.length = 0; // 配列を空にする
    }

    /**
     * データを保存または更新
     * @param {number} index - 保存したいインデックス
     * @param {Object} data - 保存するデータ (formData, fileName, type)
     */
    setItem(index, data) {
        this.formDataArray[index] = data;
    }
}

const formDataStateManager = new FormDataStateManager();
Object.freeze(formDataStateManager); // インスタンスを凍結して変更不可に

export default formDataStateManager;