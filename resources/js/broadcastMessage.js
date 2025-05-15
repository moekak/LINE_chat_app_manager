import { API_ENDPOINTS } from "./config/apiEndPoint.js";
import BroadcastMessageOperator from "./module/component/broadcast/BroadcastMessageOperator.js";
import DragAndDrop from "./module/component/DragAndDrop.js";
import { open_modal } from "./module/component/modalOperation.js";
import BroadcastMessageGenerator from "./module/component/testSender/data/BroadcastMessageGenerator.js";
import FormController from "./module/component/ui/FormController.js";
import FileUploader from "./module/util/file/FileUploader.js";




window.onload = (e)=>{
    DragAndDrop.dragAndDrop("accordion", true)
}

BroadcastMessageOperator.getInstance("js_accordion_wrapper", "accordion", API_ENDPOINTS.FETCH_BROADCASTMESSAGE);
const broadcastText = document.querySelector(".js_broadcast_error")
const imageError = document.querySelector(".js_image_error")
const errorTxt = document.querySelector(".js_error_txt")

const uploads = document.querySelectorAll(".js_upload");
uploads.forEach((upload) => {
    upload.addEventListener("change", async (e) => {
        
        FormController.initializeImageCropInput()
        broadcastText.classList.add("hidden")
        imageError.classList.add("hidden")
        const file = e.target.files[0];

        if (!file) return;

        const errorElement = document.querySelector(".js_broadcast_error")
        const imageErrorElement = document.querySelector(".js_image_error")
        const fileUploader = new FileUploader(file, errorTxt, errorElement, imageErrorElement, false, "", document.getElementById("js_messageSetting_modal"))
        await fileUploader.fileOperation()

        // // ドラッグ＆ドロップの初期化
        DragAndDrop.dragAndDrop("accordion", true);
        BroadcastMessageOperator.getInstance("js_accordion_wrapper", "accordion", API_ENDPOINTS.FETCH_BROADCASTMESSAGE);
    });
});

// // テスト送信機能モーダル処理
new BroadcastMessageGenerator()