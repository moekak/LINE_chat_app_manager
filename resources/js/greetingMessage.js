
import { API_ENDPOINTS } from "./config/apiEndPoint.js";
import BroadcastMessageOperator from "./module/component/broadcast/BroadcastMessageOperator.js";
import DragAndDrop from "./module/component/DragAndDrop.js";
import { open_modal } from "./module/component/modalOperation.js"
import FormController from "./module/component/ui/FormController.js";
import FileUploader from "./module/util/file/FileUploader.js";


// グローバル変数
const greeting_btn          = document.getElementById("js_create_message_btn")
const modal                 = document.querySelector(".broadcasting_message_modal")


greeting_btn.addEventListener("click", ()=>{
    open_modal(modal)
})


BroadcastMessageOperator.getInstance("js_accordion_wrapper", "accordion", API_ENDPOINTS.FETCH_GREETINGMESSAGE, true);
const greetingText = document.querySelector(".js_broadcast_error")
const errorTxt = document.querySelector(".js_error_txt")
const imageEditModal = document.getElementById("js_image_edit_modal")
const uploads = document.querySelectorAll(".js_upload");

uploads.forEach((upload) => {
    upload.addEventListener("change", async (e) => {

        FormController.initializeImageCropInput()
        open_modal(imageEditModal)

        greetingText.classList.add("hidden")

        const file = e.target.files[0];
        if (!file) return;

        const fileUploader = new FileUploader(file, errorTxt)
        await fileUploader.fileOperation()

        // // ドラッグ＆ドロップの初期化
        DragAndDrop.dragAndDrop("accordion", true);
        BroadcastMessageOperator.getInstance("js_accordion_wrapper", "accordion", API_ENDPOINTS.FETCH_GREETINGMESSAGE, true);

    });
});


