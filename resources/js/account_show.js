
import { close_loader, close_modal, close_modal_by_click, open_loader, open_modal } from "./module/component/modalOperation.js";
import { changeDisplayOrder, handleChatRedirect} from "./module/component/accountUIOperations.js";
import socket, { registerUser } from "./module/util/socket.js";
import InfiniteScroll from "./module/util/InfiniteScroll.js";
import { initializeUserModals } from "./module/component/modalInitializers.js";
import FormController from "./module/component/ui/FormController.js";
import { createMessageRowForFetch } from "./module/component/elementTemplate.js";
import MessageTemplateOperator from "./module/component/messageTemplate/MessageTemplateOperator.js";
import ImageUploadHandler from "./module/component/messageTemplate/ImageUploadHandler.js";
import { fetchPostOperation } from "./module/util/fetch.js";
import { API_ENDPOINTS } from "./config/apiEndPoint.js";
import InitializeInputService from "./module/component/messageTemplate/InitializeInputService.js";
import { templateImageData } from "./module/component/messageTemplate/DataGenerator.js";
import DataValidator from "./module/component/messageTemplate/DataValidator.js";
import TabController from "./module/component/messageTemplate/TabController.js";


//ユーザー管理に関連するモーダルの初期化
initializeUserModals(socket)


const admin_id = document.getElementById("js_admin_account_id").value
registerUser(admin_id, "admin")
// サーバーへの接続確認

// チャットメッセージを受信した際
socket.on("chat message", async (actual_sender_id, actual_receiver_id, sender_type)=>{
      // メッセージカウントの表示をリアルタイムで更新する
      await changeDisplayOrder(actual_sender_id, actual_receiver_id, sender_type)
})
// チャット画像を受信した際
socket.on("send_image",async  (sender_id, receiver_id, sender_type)=>{
      // メッセージカウントの表示をリアルタイムで更新する
      await changeDisplayOrder(sender_id, receiver_id, sender_type)
})

socket.on('user create', async(userData)=>{
      const parentElement = document.querySelector(".js_table");
      parentElement.insertAdjacentHTML("afterBegin",createMessageRowForFetch(userData, userData["account_id"], userData["entity_uuid"]));
      initializeUserModals(socket)
      await handleChatRedirect()
})


// ページがロードされた後に5秒待ってメッセージを非表示にする
document.addEventListener("DOMContentLoaded", function () {
      
      var alert = document.getElementById("js_alert_success");
      if (alert) {
            setTimeout(function () {
                  alert.style.display = "none";
            }, 4000); // フェードアウトの完了を待って非表示にする
      }

      // カテゴリーでのエラーメッセージの表示
      if (typeof window.hasError !== 'undefined' && window.hasError) {
            const dataValidator = new DataValidator()
            dataValidator.displayErrorList(window.errorMessages)

            document.getElementById("js_template_modal").classList.remove("hidden")
            document.querySelector(".bg").classList.remove("hidden")
            document.querySelector(".js_create_form").style.display = "none"
            document.querySelector(".js_category_form ").classList.remove("hidden")
            document.getElementById("js_tab_new").classList.remove("active")
            document.getElementById("js_tab_category").classList.add("active")

            new TabController(document.querySelectorAll('.tab'), document.querySelectorAll('.tab-content'));
      }
});

close_modal()



// ブロックキャンセル処理
const btn = document.querySelector(".js_block_cancel")
const modal = document.getElementById("js_block_account_modal")
close_modal_by_click(modal, btn)

// 無限スクロール処理
{
      const element = document.querySelector(".dashboard__wrapper-table");
      const admin_id = document.getElementById("js_account_id").value 
      const base_url = `/api/user/lists/${admin_id}`
      const parentElement = document.querySelector(".js_table")
      const fileType = "accountShow"
      
      new InfiniteScroll(element, base_url, parentElement, fileType)

}

//サイト遷移の処理
{
      handleChatRedirect()
}


// ローダーを表示する
{
      const submit_btn = document.querySelector(".modal__container-btn")
      const modal = document.getElementById("js_edit_account_modal")
      const loader = document.querySelector(".loader")
      submit_btn.addEventListener("click", ()=>{
            modal.classList.add("hidden")
            document.querySelector(".bg").classList.add("hidden")
            open_modal(loader)
      })
}

// タイトル表示変更モーダル
{
      const title_btn = document.getElementById("js_create_title_btn")
      const modal = document.getElementById("js_create_title_modal")

      title_btn.addEventListener("click", ()=>{
            open_modal(modal)
      })
}

// 保存時テキスト表示変更モーダル
{
      const text_btn = document.getElementById("js_create_text_btn")
      const modal = document.getElementById("js_create_text_modal")

      text_btn.addEventListener("click", ()=>{
            open_modal(modal)
            FormController.setupTextToggle()
      })
}


// アカウント編集のloader表示
const submitForms = document.querySelectorAll(".js_loader")
const loader = document.querySelector(".loader")

submitForms.forEach((submitForm)=>{
      submitForm.addEventListener("submit", ()=>{
            submitForm.classList.add("hidden")
            open_modal(loader)
            
      })
})

// テンプレート作成モーダル
{
      const createTemplateBtn = document.getElementById("js_create_template_btn")
      // ボタンを複製して置き換える代わりに、単純に既存のイベントリスナーを削除する
      createTemplateBtn.removeEventListener("click", handleTemplateButtonClick);
      const templateModal = document.getElementById("js_template_modal")


      createTemplateBtn.addEventListener("click", () => {
            templateImageData.length = 0
            handleTemplateButtonClick();
      });

      // 非同期処理を別関数に切り出す
      async function handleTemplateButtonClick() {

            InitializeInputService.intiaizeInputs();
            new MessageTemplateOperator();
            open_loader();
            
            const adminId = {"admin_id": document.getElementById("js_account_id").value};
            try {
                  const response = await fetchPostOperation(adminId, API_ENDPOINTS.FETCH_TEMPLATE_CATEGORY);
                  
                  response["categories"].forEach((res) => {
                        FormController.populateSelectOptions(res["id"], res["category_name"]);
                  });
                  
                  open_modal(templateModal);
                  templateModal.style.zIndex = 999
                  close_loader();
            } catch(error) {
                  console.log(error);
            }
      }
}



// テンプレート編集
{

      // キャンセル処理
      const cancelBtn = document.getElementById("js_cancel_edit_btn")

      cancelBtn.addEventListener("click", ()=>{
            document.querySelector(".tab-edit").style.display = "block"
            document.getElementById("template-edit-form").classList.add("hidden")
      })
}


// カテゴリー編集
{
      const categoryEditBtns = document.querySelectorAll(".edit-category-btn")
      categoryEditBtns.forEach((btn)=>{
            btn.addEventListener("click", ()=>{
                  const inputElement = btn.closest(".category-item-row").querySelector(".category-edit-input")
                  const saveBtn = btn.closest(".category-actions").querySelector(".save-category-btn")

                  inputElement.classList.remove("disabled")
                  inputElement.readOnly = false;
                  saveBtn.classList.remove("disabled")
            })
      })


      const cancelBtns = document.querySelectorAll(".cancel-edit-btn")
      cancelBtns.forEach((btn)=>{
            btn.addEventListener("click", ()=>{
                  const inputElement = btn.closest(".category-item-row").querySelector(".category-edit-input")
                  const saveBtn = btn.closest(".category-actions").querySelector(".save-category-btn")

                  inputElement.classList.add("disabled")
                  inputElement.readOnly = true;
                  saveBtn.classList.add("disabled")
            })
      })
}

