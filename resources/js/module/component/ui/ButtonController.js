class ButtonController{

      /**
     * ボタンを新しいボタンに置き換える処理
     * イベントリスナーの重複をさけるため
     * 
     * @param {string} buttonId - 置き換えるボタンのID
     * @returns {HTMLElement} - 新しく置き換えられたボタン要素
     */
      static replaceButtonById(buttonId){
            const oldSelectBtn = document.getElementById(buttonId)

            if (!oldSelectBtn || !oldSelectBtn.parentNode) {
                  console.error("親要素が見つかりません: js_change_area");
                  return;
            }
            const newSelectBtn = oldSelectBtn.cloneNode(true)
            oldSelectBtn.parentNode.replaceChild(newSelectBtn, oldSelectBtn);

            return newSelectBtn
      }

      /**
     * ボタンを新しいボタンに置き換える処理
     * イベントリスナーの重複をさけるため
     * 
     * @param {HTMLElement} buttonId - 置き換えるボタン要素
     * @returns {HTMLElement} - 新しく置き換えられたボタン要素
     */
      static replaceButton(oldSelectBtn){

            const newSelectBtn = oldSelectBtn.cloneNode(true)
            oldSelectBtn.replaceWith(newSelectBtn);

            return newSelectBtn
      }
}

export default ButtonController;