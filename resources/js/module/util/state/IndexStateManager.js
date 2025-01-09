class IndexStateManager{
    constructor(){
        if (!IndexStateManager.instance) {
              this.index = {count: 0}; // 初期状態
              IndexStateManager.instance = this; // インスタンスを保存
        }
        return IndexStateManager.instance; // 常に同じインスタンスを返す
    }

    getState(){
            return this.index["count"]
    }

    setState(){
            this.index["count"]++
    }
}

const indexStateManager = new IndexStateManager();
Object.freeze(indexStateManager); // インスタンスを凍結して変更不可に

export default indexStateManager;