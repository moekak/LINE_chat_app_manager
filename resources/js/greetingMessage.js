
import { API_ENDPOINTS } from "./config/apiEndPoint.js";
import { SYSTEM_URL } from "./config/config.js";
import BroadcastMessageOperator from "./module/component/broadcast/BroadcastMessageOperator.js";
import DragAndDrop from "./module/component/DragAndDrop.js";
import { open_modal } from "./module/component/modalOperation.js"
import GreetingTestMessageSender from "./module/component/testSender/data/GreetingTestMessageSender.js";
import FormController from "./module/component/ui/FormController.js";
import { fetchGetOperation } from "./module/util/fetch.js";
import FileUploader from "./module/util/file/FileUploader.js";


// グローバル変数
const greeting_btn          = document.getElementById("js_create_message_btn")
const modal                 = document.querySelector(".broadcasting_message_modal")


greeting_btn.addEventListener("click", async()=>{

    // fileアップロードcrop
    const greetingText = document.querySelector(".js_broadcast_error")
    const errorTxt = document.querySelector(".js_error_txt")
    const uploads = document.querySelectorAll(".js_upload");

    uploads.forEach((upload) => {
        upload.addEventListener("change", async (e) => {
            FormController.initializeImageCropInput()

            greetingText.classList.add("hidden")

            const file = e.target.files[0];
            if (!file) return;

            
            const errorElement = document.querySelector(".js_broadcast_error")
            const imageErrorElement = document.querySelector(".js_image_error")
            const fileUploader = new FileUploader(file, errorTxt, errorElement, imageErrorElement, false, "", document.getElementById("js_messageSetting_modal"))
            await fileUploader.fileOperation()

            // // ドラッグ＆ドロップの初期化
            DragAndDrop.dragAndDrop("accordion", true);
            BroadcastMessageOperator.getInstance("js_accordion_wrapper", "accordion", API_ENDPOINTS.FETCH_GREETINGMESSAGE, true);

        });
    });

    // 現在設定されているメッセージを取得する
    const adminId =document.getElementById("js_account_id").value
    const response = await fetchGetOperation(`${API_ENDPOINTS.FETCH_GREETINGMESSAE_GET}/${adminId}`)
    const broadcastMessageOperatorInstance = BroadcastMessageOperator.getInstance("js_accordion_wrapper", "accordion", API_ENDPOINTS.FETCH_GREETINGMESSAGE, true);
    DragAndDrop.dragAndDrop("accordion", true)
    response.forEach(res => {
        if(res["resource_type"] === "greeting_text"){
            const messageArray = {"message": res["resource"], "message_order": res["message_order"]}
            broadcastMessageOperatorInstance.displayMessageToList(messageArray) 
        }else{
            BroadcastMessageOperator.displayImageMessageToList(`${SYSTEM_URL.IMAGE_URL}/${res["resource"]}`, "js_accordion_wrapper", "accordion", res["message_order"])
            let cropArea = null
            if(res["x_percent"]){
                cropArea = {"xPercent": res["x_percent"], "yPercent" : res["y_percent"], "heightPercent": res["height_percent"], "widthPercent": res["width_percent"]}
            }

            FileUploader.convertFileNameToFile(res["resource"])
                .then((file)=>{
                    FileUploader.setImageDataToFormDataArray(file, res["resource"], res["message_order"], res["url"] ?? "", cropArea ?? "")
                })  
        }
        BroadcastMessageOperator.deleteList("accordion")

    });

    
    open_modal(modal)
    // // テスト送信機能モーダル処理
    new GreetingTestMessageSender()
})


