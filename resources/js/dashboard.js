import { initializeAccountDeletionModal, initializeAccountEditModal, initializeBroadcastMessageModal, initializeLineMessageUpdationModal } from "./module/component/modalInitializers.js";
import { open_modal, close_modal } from "./module/component/modalOperation.js"
import { changeAccountDisplayOrder, initializeAccountStatusManager} from "./module/component/accountUIOperations.js";
import socket, { registerUser } from "./module/util/socket.js";
import InfiniteScroll from "./module/util/InfiniteScroll.js";

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


// socket.on("message read", (adminUuid, userUuid, unreadCount)=>{
//     const wrappers = document.querySelectorAll(".js_account_id")
//     wrappers.forEach((wrapper)=>{
//         if(wrapper.getAttribute("data-id") === adminUuid){
//             const current_count = Number(wrapper.querySelector(".js_total_count").innerHTML)
//             wrapper.querySelector(".js_total_count").innerHTML = current_count - unreadCount

//             if(Number(wrapper.querySelector(".js_total_count").innerHTML) <= 0){
//                 wrapper.querySelector(".js_total_count").style.display = "none"
//             }

//         }
//     })

    
// })
const admin_id = document.getElementById("js_admin_account_id").value
registerUser(admin_id, "user")
  // サーバーへの接続確認

// チャットメッセージを受信した際
socket.on("chat message", (actual_sender_id, actual_receiver_id, sender_type, admin_login_id)=>{
    changeAccountDisplayOrder(actual_receiver_id, sender_type, admin_login_id)
})
// チャット画像を受信した際
socket.on("send_image", (sender_id, receiver_id, sender_type, admin_login_id)=>{
    changeAccountDisplayOrder(receiver_id, sender_type, admin_login_id)
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



// 無限スクロール処理
{
    const elements = document.querySelectorAll(".dashboard__wrapper-table");
    const admin_id = document.getElementById("js_admin_account_id").value 
    const fileType = "dashboard"
    
    elements.forEach((element)=>{
        const base_url = `/api/account/lists/${admin_id}/${element.getAttribute("data-status-id")}`
        const parentElement = document.querySelector(`.js_parentEl${element.getAttribute("data-status-id")}`)
        new InfiniteScroll(element, base_url, parentElement, fileType)
    })
}

