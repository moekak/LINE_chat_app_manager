// シングルトンパターン

class UserStateManager{
      constructor(){
            if (!UserStateManager.instance) {
                  this.list = []; // 初期状態
                  UserStateManager.instance = this; // インスタンスを保存
            }
            return UserStateManager.instance; // 常に同じインスタンスを返す
      }

      getState(){
            return this.list
      }

      setState(value){
            this.list.push(value)
      }
}

const userStateManager = new UserStateManager();
Object.freeze(userStateManager); // インスタンスを凍結して変更不可に

export default userStateManager;