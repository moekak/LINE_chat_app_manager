import { fetchGetOperation } from "../util/fetch.js"
import { setAccountDataForEditing, setAccountName, setActionUrl } from "./accountUIOperations.js"
import { open_modal } from "./modalOperation.js"

// アカウント編集モーダルの初期化
export const initializeAccountEditModal =() =>{
      const edit_btns = document.querySelectorAll(".js_edit_account_btn")
      const edit_modal = document.getElementById("js_edit_account_modal")
      const loader = document.querySelector(".loader")


      edit_btns.forEach((btn)=>{
            btn.addEventListener("click", (e) => {
                  // valueを空にする
                  document.querySelector(".js_account_id_input").value = ""
                  console.log(document.querySelector(".js_account_id_input").value);
                  
                  // 編集をしたいアカウントのIDを取得する
                  let target_btn = e.currentTarget;
                  let account_id = target_btn.getAttribute("data-id");

                  console.log("account_id" + account_id);
                  
                  
                  // formのactionの設定
                  setActionUrl(account_id, "js_edit_account_form")
                  // 編集モーダルを表示するまでローダーを表示する
                  open_modal(loader)
                  // 編集したいアカウントの情報を非同期で取得する
                  fetchGetOperation(`/account/edit/${account_id}`)
                        .then((res) => {
                              setAccountDataForEditing(res)
                        .then(() => {
                              // 編集モーダルの表示
                              open_modal(edit_modal);
                        });
                  });
            });
      })
}


// 一斉送信モーダルの初期化
export const initializeBroadcastMessageModal = () =>{
      const sending_btns            = document.querySelectorAll(".js_send_message_btn")
      const broadcasting_modal      = document.getElementById("js_boradcasting_modal")

      sending_btns.forEach((btn)=>{
            btn.addEventListener("click", (e)=>{
                  e.preventDefault()
                  // 一斉送信メッセージモーダルを表示する
                  open_modal(broadcasting_modal);
                  // 一斉メッセージ行いたいアカウントのIDを取得し、inputに格納
                  let account_id = e.currentTarget.getAttribute("data-id")
                  document.getElementById("js_account_id").value = account_id
            })
      })
}



// アカウント削除モーダルの初期化
export const initializeAccountDeletionModal= () =>{
      const delete_btns             = document.querySelectorAll(".js_delete_account_btn")
      const delete_account_modal    = document.getElementById("js_delete_account_modal")

      delete_btns.forEach((delete_btn)=>{
            delete_btn.addEventListener("click", (e)=>{
                  // 削除したいアカウントのIDを取得する
                  let id = e.currentTarget.getAttribute("data-id");
                  let name = e.currentTarget.getAttribute("data-name")
                  setActionUrl(id, "js_delete_account_from")
                  setAccountName(name, "js_account_name")
                  open_modal(delete_account_modal)
            }) 
      })
}

// ユーザーブロックモーダルの初期化
export const initializeAccountBlockModal = () =>{
      const block_btns = document.querySelectorAll(".js_block_btn")
      const blockModal = document.getElementById("js_block_account_modal")

      block_btns.forEach((btn)=>{
            btn.addEventListener("click", (e)=>{
                  let id = e.currentTarget.getAttribute("data-id");
                  let name = e.currentTarget.getAttribute("data-name")
                  setActionUrl(id, "js_block_account_from")
                  setAccountName(name, "js_account_name")
                  open_modal(blockModal)
            })
      })
}