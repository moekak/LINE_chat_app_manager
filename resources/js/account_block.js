import { initializeAccountBlockModal } from "./module/component/accountModalInitializers.js";
import { close_modal, close_modal_by_click } from "./module/component/modalOperation.js";
import socket from "./module/util/socket.js";

initializeAccountBlockModal(socket)
close_modal()


// ブロック解除キャンセル処理
const btn = document.querySelector(".js_block_cancel")
const modal = document.getElementById("js_block_account_modal")
close_modal_by_click(modal, btn)