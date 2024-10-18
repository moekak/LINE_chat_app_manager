import { initializeAccountDeletionModal, initializeAccountEditModal, initializeBroadcastMessageModal } from "./module/component/accountModalInitializers.js";
import { open_modal, close_modal } from "./module/component/modalOperation.js"
import { changeAccountDisplayOrder, initializeAccountStatusManager} from "./module/component/accountUIOperations.js";
import {  fetchPostOperation } from "./module/util/fetch.js"
import { prepareMessageData } from "./module/util/messageService.js";
import socket, { registerUser } from "./module/util/socket.js";

// 
// モーダル初期設定

// アカウント編集モーダル処理
initializeAccountEditModal()
// 一斉送信フロント処理
initializeBroadcastMessageModal()
// アカウント削除処理
initializeAccountDeletionModal()
// モーダルを閉じる処理
close_modal();

// アカウント作成モーダル処理
const create_btn    = document.getElementById("js_create_account_btn")
const create_modal  = document.getElementById("js_create_account_modal")

create_btn.addEventListener("click", ()=>{
    open_modal(create_modal)
})

// アカウント編集のloader表示
const submitForms = document.querySelectorAll(".js_loader")
const loader = document.querySelector(".loader")

submitForms.forEach((submitForm)=>{
    submitForm.addEventListener("submit", ()=>{
        submitForm.classList.add("hidden")
        open_modal(loader)
        
    })
})



// ページがロードされた後に5秒待ってメッセージを非表示にする
document.addEventListener("DOMContentLoaded", function () {
    var alert = document.getElementById("js_alert_success");
    if (alert) {
        setTimeout(function () {
            alert.style.display = "none";
        }, 4000); // フェードアウトの完了を待って非表示にする
    }
});





const admin_id = document.getElementById("js_admin_account_id").value
registerUser(admin_id, "user")
  // サーバーへの接続確認
socket.on('connect', () => {
    console.log('サーバーに接続されました（chat.jsからの確認）');
});



// チャットメッセージを受信した際
socket.on("chat message", (actual_sender_id, actual_receiver_id, sender_type, admin_login_id)=>{
    changeAccountDisplayOrder(actual_sender_id, actual_receiver_id, sender_type, admin_login_id)
})
// チャット画像を受信した際
socket.on("send_image", (sender_id, receiver_id, sender_type, admin_login_id)=>{
    changeAccountDisplayOrder(sender_id, receiver_id, sender_type, admin_login_id)
})


// 一斉送信処理
// document.querySelector(".js_message_submit_btn").addEventListener("click", ()=>{
    
//     const { body,  formatted_body, admin_account_id} = prepareMessageData()
//     const data = {
//         "content": body,
//         "admin_id": admin_account_id
//     }

//     fetchPostOperation(data, "/api/broadcast_message/store")
//     .then((res)=>{
//         document.getElementById("js_boradcasting_modal").classList.add("hidden")
//         document.querySelector(".bg").classList.add("hidden")

//         const success_el = document.getElementById("js_alert_success")
//         success_el.style.display = "block";
//         success_el.innerHTML = "一斉送信に成功しました"
//         document.querySelector(".js_message_input").value = ""

//         setTimeout(() => {
//             success_el.style.display = "none"
//         }, 2000);
//         const created_at = res["created_at"];

//         socket.emit("broadcast message", {formatted_body, admin_account_id, created_at})
        
//     })
// })


// アカウント削除キャンセル処理
initializeAccountStatusManager()

//ステータス変更成功の文言を出す処理
const status_update = localStorage.getItem("status_update");
if(status_update){
    const success_alert = document.getElementById("js_alert_success")
    localStorage.removeItem("status_update")
    success_alert.innerHTML = "アカウントのステータス変更に成功しました。"
    success_alert.style.display = "block";
    setTimeout(() => {
        success_alert.style.display = "none"
    }, 2000);
    
}