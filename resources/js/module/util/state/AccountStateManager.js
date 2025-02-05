// シングルトンパターン

class AccountStateManager{
      constructor(){
            if (!AccountStateManager.instance) {
                  this.list = []; // 初期状態
                  AccountStateManager.instance = this; // インスタンスを保存
            }
            return AccountStateManager.instance; // 常に同じインスタンスを返す
      }

      getState(){
            return this.list
      }

      setState(value){
            this.list.push(value)
      }
}

const accountStateManager = new AccountStateManager();
Object.freeze(accountStateManager); // インスタンスを凍結して変更不可に

export default accountStateManager;