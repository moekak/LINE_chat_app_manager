import { deleteList, displayMessageToList, dragAndDrop, hasValue, hideErrorMsg } from "./module/component/broadcastMessageOperations.js"
import { open_modal } from "./module/component/modalOperation.js"
import { fetchPostOperation } from "./module/util/fetch.js"
import { cleanHtmlContent } from "./module/util/messageService.js"
import { resizeImage } from "./module/util/processAndResizeImage.js"

// グローバル変数
const greeting_btn          = document.getElementById("js_create_message_btn")
const modal                 = document.querySelector(".broadcasting_message_modal")
const greetingMessageInput  = document.querySelector(".js_greeting_input")
const display_btn           = document.querySelector(".js_greeting_display_btn")

let greetingMessage = ""

greeting_btn.addEventListener("click", ()=>{
    open_modal(modal)
})

// メッセージの入力ありなしで追加ボタンのスタイルを変更する
greetingMessageInput.addEventListener("input", (e)=>{
    hideErrorMsg()
    greetingMessage = e.currentTarget.value
    if(greetingMessage.length > 0){
        display_btn.classList.remove("disabled_btn")
    }else{
        display_btn.classList.add("disabled_btn")
    }
})

// 追加ボタンを押したらメッセージまたは画像をプレビューできるように表示させる
display_btn.addEventListener("click", ()=>{
    if(greetingMessage.length > 0){
        displayMessageToList(greetingMessage, null, null, "js_accordion_wrapper_greeting", "accordion_greeting")
        deleteList("accordion_greeting")
        dragAndDrop("accordion_greeting")
        greetingMessage = ""
        greetingMessageInput.value = ""
    }
})

// ドラッグドロップ機能
window.onload = (e)=>{
	dragAndDrop("accordion_greeting")
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
        displayMessageToList(null, objectURL, index, "js_accordion_wrapper_greeting", "accordion_greeting");
        deleteList("accordion_greeting",upload)
        // ファイルをリサイズし、Blobオブジェクトを取得
        const resizedBlob = await resizeImage(file);
        // リサイズされたBlobオブジェクトを保存
        fileStorage[index] = resizedBlob;
        // 使用後にメモリ解放
        // URL.revokeObjectURL(objectURL);
                

        // ドラッグ＆ドロップの初期化
        dragAndDrop("accordion_greeting");
    });
});


// 一斉送信の送信ボタンクリック処理

const submit_btn = document.querySelector(".js_greeting_submit_btn")
submit_btn.addEventListener("click", ()=>{
    if(hasValue("accordion_greeting")){
        const data = document.querySelectorAll(".js_data")
        const sendingData = {
            content: [],
            admin_id: document.getElementById("js_greeting_account_id").value,
        };
    
        data.forEach((data)=>{
            if(data.querySelector(".js_img")){
                let fileIndex =data.querySelector(".js_img").getAttribute("data-file-index")
                sendingData.content.push({ data: fileStorage[fileIndex], type: "greeting_img" });
            }else{
                sendingData.content.push({ data: cleanHtmlContent(data.innerHTML), type: "greeting_text" });
            }
        })
    
    
        // バックエンドに送信するデータ
    
        const loader = document.querySelector(".loader")
        modal.classList.add("hidden")
        open_modal(loader)

        console.log(sendingData);
        

        fetchPostOperation(sendingData, "/api/greeting_message/store")
        .then((res)=>{
            if(res["status"] = "success"){
                document.getElementById("js_greeting_modal").classList.add("hidden")
                document.querySelector(".bg").classList.add("hidden")
                loader.classList.add("hidden")

                //成功メッセージを出す処理
                const success_el = document.getElementById("js_alert_success")
                success_el.style.display = "block";
                success_el.innerHTML = "初回あいさつメッセージの登録に成功しました"
                document.querySelector(".js_greeting_input").value = ""
                document.querySelector(".js_upload").value = ""
                document.querySelector(".js_accordion_wrapper_greeting").innerHTML = ""

                // 成功メッセージを出して2秒後に批評にする
                setTimeout(() => {
                    success_el.style.display = "none"
                }, 2000);
            }else{
                //成功メッセージを出す処理
                const success_el = document.getElementById("js_alert_success")
                success_el.style.display = "block";
                success_el.innerHTML = "初回あいさつメッセージに失敗しました。再度お試しください。"
                document.querySelector(".js_greeting_input").value = ""
                document.querySelector(".js_upload").value = ""
                document.querySelector(".js_accordion_wrapper_greeting").innerHTML = ""
            }
        })
    }else{
        const error_el = document.querySelector(".js_broadcast_error")
        error_el.classList.remove("hidden")
    }
})
