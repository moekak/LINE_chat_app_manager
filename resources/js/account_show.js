
import { close_modal, close_modal_by_click } from "./module/component/modalOperation.js";
import { changeDisplayOrder} from "./module/component/accountUIOperations.js";
import socket, { registerUser } from "./module/util/socket.js";
import { fetchSpecificUserInfo } from "./module/component/fetchUserData.js";
import { initializeAccountBlockModal } from "./module/component/accountModalInitializers.js";
import InfiniteScroll from "./module/util/InfiniteScroll.js";


// ブロックモーダル初期化
initializeAccountBlockModal(socket)

const admin_id = document.getElementById("js_admin_account_id").value
registerUser(admin_id, "admin")
// サーバーへの接続確認

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



// ブロックキャンセル処理
const btn = document.querySelector(".js_block_cancel")
const modal = document.getElementById("js_block_account_modal")
close_modal_by_click(modal, btn)


{
      const element = document.querySelector(".dashboard__wrapper-table");
      const loader = document.querySelector(".js_loader")

      new InfiniteScroll(element, loader)

}



