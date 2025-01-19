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
    setMinusState(){
            this.index["count"]--
    }
    resetState(){
        this.index["count"] = 0
    }
    static resetInstance() {
        IndexStateManager.instance = null; // インスタンスをリセット
    }

    /**
     * 新しいインスタンスを作成
     */
    static createNewInstance() {
        const newInstance = new IndexStateManager();
        Object.freeze(newInstance); // 新しいインスタンスを凍結
        return newInstance;
    }
}

const indexStateManager = new IndexStateManager();
Object.freeze(indexStateManager); // インスタンスを凍結して変更不可に
export { indexStateManager, IndexStateManager };
export default indexStateManager;