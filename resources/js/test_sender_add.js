import { open_modal } from "./module/component/modalOperation.js"

const add_sender_btn = document.getElementById("js_create_test_sender_btn")

add_sender_btn.addEventListener("click", ()=>{
      open_modal(document.getElementById("js_test_sender_add"))
})