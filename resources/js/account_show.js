
import { close_modal } from "./module/component/modalOperation.js";
import { changeDisplayOrder, handleEditUserName, setActionUrl} from "./module/component/accountUIOperations.js";
import { fetchGetOperation } from "./module/util/fetch.js";
import socket, { registerUser } from "./module/util/socket.js";
import { fetchSpecificUserInfo } from "./module/component/fetchUserData.js";
import { initializeAccountBlockModal } from "./module/component/accountModalInitializers.js";

// ブロックモーダル初期化
initializeAccountBlockModal(socket)

const admin_id = document.getElementById("js_admin_account_id").value
registerUser(admin_id, "admin")
// サーバーへの接続確認
socket.on('connect', () => {
      console.log('サーバーに接続されました（chat.jsからの確認）');
});


// チャットメッセージを受信した際
socket.on("chat message", (actual_sender_id, actual_receiver_id, sender_type)=>{
      // メッセージカウントの表示をリアルタイムで更新する
      changeDisplayOrder(actual_sender_id, actual_receiver_id, sender_type)
})
// チャット画像を受信した際
socket.on("send_image", (sender_id, receiver_id, sender_type)=>{
      // メッセージカウントの表示をリアルタイムで更新する
      changeDisplayOrder(sender_id, receiver_id, sender_type)
})



// ユーザー編集処理
const edit_btns = document.querySelectorAll(".js_edit_user_btn")
const editModal = document.getElementById("js_edit_account_modal")

edit_btns.forEach((edit_btn)=>{
      fetchSpecificUserInfo(edit_btn, editModal)
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

close_modal()




// ユーザーブロック
