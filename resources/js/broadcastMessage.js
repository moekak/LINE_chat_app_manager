import { deleteList, displayMessageToList, dragAndDrop, hasValue, hideErrorMsg } from "./module/component/broadcastMessageOperations.js"
import { close_modal, open_modal } from "./module/component/modalOperation.js"
import { fetchPostOperation } from "./module/util/fetch.js"
import { cleanHtmlContent } from "./module/util/messageService.js"
import { resizeImage } from "./module/util/processAndResizeImage.js"
import socket from "./module/util/socket.js"


// 一斉配信のテキスト箇所
const broadcastMessageInput = document.querySelector(".js_message_input")
const display_btn = document.querySelector(".js_message_display_btn")


let broadcastMessage = ""
broadcastMessageInput.addEventListener("input", (e)=>{
    hideErrorMsg()
    broadcastMessage = e.currentTarget.value
    if(broadcastMessage.length > 0){
        display_btn.classList.remove("disabled_btn")
    }else{
        display_btn.classList.add("disabled_btn")
    }
})


display_btn.addEventListener("click", ()=>{
    if(broadcastMessage.length > 0){
        displayMessageToList(broadcastMessage, null, null, "js_accordion_wrapper", "accordion")
        deleteList("accordion")
        dragAndDrop("accordion")
        broadcastMessage = ""
        broadcastMessageInput.value = ""
    }
})

window.onload = (e)=>{
	dragAndDrop("accordion")
}


let fileStorage = {};  // Fileオブジェクトを保存するためのオブジェクト
const uploads = document.querySelectorAll(".js_upload");
uploads.forEach((upload, index) => {
    upload.addEventListener("change", async (e) => {

        hideErrorMsg()
        // 選択されたファイルにアクセス
        const file = e.target.files[0];

        // FileオブジェクトのままURLを作成
        const objectURL = URL.createObjectURL(file);

        // `displayMessageToList` にファイルのURLを渡して表示する
        displayMessageToList(null, objectURL, index, "js_accordion_wrapper", "accordion");
        deleteList("accordion", upload)
        // ファイルをリサイズし、Blobオブジェクトを取得
        const resizedBlob = await resizeImage(file);
        // リサイズされたBlobオブジェクトを保存
        fileStorage[index] = resizedBlob;
        // 使用後にメモリ解放
        // URL.revokeObjectURL(objectURL);
                

        // ドラッグ＆ドロップの初期化
        dragAndDrop("accordion");
    });
});


// 一斉送信の送信ボタンクリック処理

const submit_btn = document.querySelector(".js_message_submit_btn")
submit_btn.addEventListener("click", ()=>{
    if(hasValue("accordion")){
        const data = document.querySelectorAll(".js_data")
        const sendingData = {
            content: [],
            admin_id: document.getElementById("js_account_id").value,
        };
    
        data.forEach((data)=>{
            if(data.querySelector(".js_img")){
                let fileIndex =data.querySelector(".js_img").getAttribute("data-file-index")
                sendingData.content.push({ data: fileStorage[fileIndex], type: "broadcast_img" });
            }else{
                sendingData.content.push({ data: cleanHtmlContent(data.innerHTML), type: "broadcast_text" });
            }
        })
    
    
        // バックエンドに送信するデータ
        const sendingDatatoBackEnd = sendingData["content"];
        const admin_account_id = sendingData["admin_id"]
    
        const loader = document.querySelector(".loader")
        const modal = document.querySelector(".broadcasting_message_modal")
        modal.classList.add("hidden")
        open_modal(loader)
    
        fetchPostOperation(sendingData, "/api/broadcast_message/store")
        .then((res)=>{
            if(res["created_at"]){
    
                // モーダルをloaderを閉じる処理
                document.getElementById("js_boradcasting_modal").classList.add("hidden")
                document.querySelector(".bg").classList.add("hidden")
                loader.classList.add("hidden")
    
                // 成功メッセージを出す処理
                const success_el = document.getElementById("js_alert_success")
                success_el.style.display = "block";
                success_el.innerHTML = "一斉送信に成功しました"
                document.querySelector(".js_message_input").value = ""
                document.querySelector(".js_upload").value = ""
                document.querySelector(".js_accordion_wrapper").innerHTML = ""
    
                // 成功メッセージを出して2秒後に批評にする
                setTimeout(() => {
                    success_el.style.display = "none"
                }, 2000);
    
                const created_at = res["created_at"]
                socket.emit("broadcast message", {sendingDatatoBackEnd, admin_account_id, created_at})
            }
        })
    }else{
        const error_el = document.querySelector(".js_broadcast_error")
        error_el.classList.remove("hidden")
    }
    
    
    
})
