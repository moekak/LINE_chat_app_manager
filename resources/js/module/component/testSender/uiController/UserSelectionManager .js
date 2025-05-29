export default class UserSelectionManager {
      constructor(parent){
            this.selectAllCheckbox = document.getElementById("select-all-users")
            this.userCheckboxes = document.querySelectorAll('.user-select');
            this.selectedCountEl = document.getElementById('selected-count');
            this.sendToSelectedBtn = document.getElementById('send-to-selected');
            this.parent = parent
      }

      initialize(){
            const selectAllCheckbox = document.getElementById("select-all-users")
            const newCheckbox = selectAllCheckbox.cloneNode(true)
            selectAllCheckbox.replaceWith(newCheckbox)
            newCheckbox.addEventListener("change", this.handleInput.bind(this)) //　すべて選択
            this.userCheckboxes.forEach((checkbox)=>{
                  checkbox.addEventListener('change', this.updateSelectedCount.bind(this)); // 個々の選択
            })
      }

      /**
      * 「すべて選択」にチェックを入れた時、個々のユーザーチェックボックスにすべてチェックを入れる
      * @returns {void}
      */
      handleInput(){
            const userCheckboxes =document.querySelectorAll('.user-select')
            const selectAllCheckbox =  document.getElementById("select-all-users")
            userCheckboxes.forEach(checkbox => {
                  checkbox.checked = selectAllCheckbox.checked;
            });


            this.updateSelectedCount();
      }


      /**
      * ユーザー選択UIの切り替えをおこなう処理
      * @returns {void}
      */
      updateSelectedCount(){

            const selectedCount = document.querySelectorAll('.user-select:checked').length;
            this.selectedCountEl.textContent = `${selectedCount}人選択中`;
            this.sendToSelectedBtn.disabled = selectedCount <= 0 // 「選択したユーザーに送信」ぼたんの無効化、有効化の切り替え
            
            // 「全て選択」のチェックボックスの切り替えをおこなう。全てのユーザーが選択されてたらチェック入れる。それ以外がチェックはずす
            if (selectedCount === this.userCheckboxes.length) {
                  this.selectAllCheckbox.checked = true;
            } else if (selectedCount === 0) {
                  this.selectAllCheckbox.checked = false;
            } 

            // 個々のユーザー選択チェックボックスのスタイルの切り替えを、チェックされたかどうかで切り替える
            const userCheckboxes = document.querySelectorAll('.user-select');
            userCheckboxes.forEach(checkbox => {
                  const userItem = checkbox.closest('.user-item');
                  if (checkbox.checked) {
                        userItem.classList.add('selected');
                  } else {
                        userItem.classList.remove('selected');
                  }
            });
      }


      /**
      * UIのスタイルの変更処理
      * @returns {void}
      */
      resetUi(){
            const userCheckboxes = document.querySelectorAll('.user-select');
            this.selectedCountEl.textContent = `0人選択中`;
            this.sendToSelectedBtn.disabled = true

            userCheckboxes.forEach((checkbox)=>{
                  checkbox.closest(".user-item").classList.remove("selected")
                  checkbox.checked = false
            })

            const selectAllCheckbox = document.getElementById("select-all-users")
            selectAllCheckbox.checked = false

            const testSenderButtons = document.querySelectorAll(".js_sending_btn")
            testSenderButtons.forEach((btn)=>{
                  btn.classList.add("disabled_btn")  
            })
            
      }


      /**
      * ユーザーチェックボックスにチェックを入れた際のスタイル処理
      * @returns {void}
      * @param {HTMLElement} userItem 
      */
      static checkStyle(userItem){
            userItem.querySelector(".user-select").checked = true
            userItem.classList.add("selected")
      }
}