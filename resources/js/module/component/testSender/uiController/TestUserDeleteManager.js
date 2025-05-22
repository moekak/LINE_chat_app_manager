import ProcessingManager from "./ProcessingManager.js"

export class TestUserDeleteManager{
      constructor(){
            this.testUserDeleteConfirmationModal = document.getElementById("js_delete_test_user")
            this.testSenderModal = document.getElementById("js_test_sender")
            this.userItemWrapper = document.querySelector(".user-item-wrapper")
            this.processManager = ProcessingManager.getInstance()
      }

      modalOperation(){
            this.testUserDeleteConfirmationModal.classList.add("hidden")
            this.testSenderModal.classList.remove("hidden")
            this.processManager.onProcess()
      }

      deleteTestUserFromDom(user_id){
            const userItems = document.querySelectorAll(".user-item")
            const targetItem = Array.from(userItems).find((item)=> item.dataset.userId === user_id)
            if(targetItem){
                  this.userItemWrapper.removeChild(targetItem)
            }
            this.processManager.onDone()
      }

}