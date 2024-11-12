import { initializeAccountDeletionModal, initializeAccountEditModal, initializeBroadcastMessageModal, initializeLineMessageUpdationModal } from "./module/component/accountModalInitializers.js";
import { open_modal, close_modal } from "./module/component/modalOperation.js"
import { changeAccountDisplayOrder, initializeAccountStatusManager} from "./module/component/accountUIOperations.js";
import socket, { registerUser } from "./module/util/socket.js";

// モーダル初期設定
// アカウント編集モーダル処理
initializeAccountEditModal()
// 一斉送信フロント処理
initializeBroadcastMessageModal()
// アカウント削除処理
initializeAccountDeletionModal()
// LINE送信文言変更モーダル処理
initializeLineMessageUpdationModal()
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


// // アカウント追加処理
// const submit_form =document.getElementById("js_add_account_form")

// submit_form.addEventListener("submit", (e)=>{
//     e.preventDefault()

//     let account_name = document.querySelector(".js_account_name_input").value 
//     let channel_access_token = document.querySelector(".js_channel_access_token_input").value
//     let channel_secret = document.querySelector(".js_channel_secret_input").value
//     let url = document.querySelector(".js_url_input").value
//     let status = document.querySelector(".js_status_select").value
//     let second_account_id = document.querySelector(".js_second_account_id")

//     const data = {
//         "account_name" : account_name,
//         "account_url": url,
//         "channelsecret" : channel_secret,
//         "channelaccesstoken" : channel_access_token,
//         "account_status" : status,
//         "second_account_id" : second_account_id
//     }

//     console.log(data);

//     setInterval(()=>{
//         submit_form.submit() 
//     }, 3000)

    
// })