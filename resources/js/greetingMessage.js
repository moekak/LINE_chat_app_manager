import { toggleDisplayButtonState } from "./module/component/accountUIOperations.js";
import { deleteList, displayMessageToList, dragAndDrop, hasValue, hideErrorMsg } from "./module/component/broadcastMessageOperations.js"
import { isAllowedType, isCorrectSize } from "./module/component/imageFileOperator.js"
import { open_modal } from "./module/component/modalOperation.js"
import { cleanHtmlContent } from "./module/util/messageService.js"
import imageCompression from 'browser-image-compression';

// グローバル変数
const greeting_btn          = document.getElementById("js_create_message_btn")
const modal                 = document.querySelector(".broadcasting_message_modal")
const greetingMessageInput  = document.querySelector(".js_greeting_input")
const display_btn           = document.querySelector(".js_greeting_display_btn")

let greetingMessage = ""
const formDataArray = []; // FormDataを保持する配列を作成
let index = 0

greeting_btn.addEventListener("click", ()=>{
    open_modal(modal)
})

// メッセージの入力ありなしで追加ボタンのスタイルを変更する
greetingMessageInput.addEventListener("input", (e)=>{
    hideErrorMsg()
    greetingMessage = e.currentTarget.value
    toggleDisplayButtonState(display_btn, greetingMessage)
})

// 追加ボタンを押したらメッセージまたは画像をプレビューできるように表示させる
display_btn.addEventListener("click", ()=>{
    if(greetingMessage.length > 0){
        formDataArray[index] = {"type" : "text", "data" : greetingMessage}
        displayMessageToList(greetingMessage, null, index, "js_accordion_wrapper_greeting", "accordion_greeting")
        deleteList("accordion_greeting", formDataArray)
        dragAndDrop("accordion_greeting", true)
        greetingMessage = ""
        greetingMessageInput.value = ""
        index++
    }
})

// ドラッグドロップ機能
window.onload = (e)=>{
	dragAndDrop("accordion_greeting", true)
}


const greetingText = document.querySelector(".js_broadcast_error")
const errorTxt = document.querySelector(".js_error_txt")

const uploads = document.querySelectorAll(".js_upload");
uploads.forEach((upload) => {
    upload.addEventListener("change", async (e) => {

        const file = e.target.files[0];
        if (!file) return;

        if(!isAllowedType(file.type)){
            greetingText.classList.remove("hidden")
            errorTxt.innerHTML = "許可されているファイル形式は JPG, PNG, GIF, WEBP のみです。"
            return
        }

        if(!isCorrectSize(file.size)){
            greetingText.classList.remove("hidden")
            errorTxt.innerHTML = "画像サイズが大きすぎます。5MB以内で指定してください。"
            return
        }

        // 1. 圧縮
        const compressedFile = await imageCompression(file, {
            maxSizeMB: 1,
            maxWidthOrHeight: 1024,
            useWebWorker: true
        });

        const reader = new FileReader();
        reader.onload = e =>{
            displayMessageToList(null,  e.target.result, index, "js_accordion_wrapper_greeting", "accordion_greeting");
            deleteList("accordion_greeting", formDataArray)
            index++
        }

        reader.readAsDataURL(compressedFile);

        // オリジナルのファイル名を加工
        const originalName = file.name;
        const extension = originalName.split('.').pop();  // 拡張子を取得
        const newFileName = `${Date.now()}_${Math.random().toString(36).substr(2, 9)}.${extension}`;

        // 3. FormDataを配列に保存
        const formData = new FormData();
        formData.append('image', compressedFile); // ファイル名も保持
        formDataArray[index] = {
            formData: formData,
            fileName: newFileName,  // ファイル名を保存
            type: 'image'        // タイプも保存しておくと便利
        };

        // ドラッグ＆ドロップの初期化
        dragAndDrop("accordion_greeting", true);
    });
});


// 一斉送信の送信ボタンクリック処理

const submit_btn = document.querySelector(".js_greeting_submit_btn")
let sendMessage = []
submit_btn.addEventListener("click", ()=>{
    if(hasValue("accordion_greeting")){
        sendMessage = []
        const data = document.querySelectorAll(".js_data")
        // 順番通りに並べ替え
        for(let i = 0; i < data.length; i ++){
            sendMessage[i] = formDataArray[Array.from(data)[i].getAttribute("data-file-index")]
        }

        const formData = new FormData();

        // sendMessage のデータを FormData に追加
        sendMessage.forEach((item, index) => {
            if (item.type === 'image') {
                // FormDataから画像を取得
                const imageFile = item.formData.get('image');
                if (imageFile) {
                    formData.append(`images[${index}]`, imageFile, item.fileName);
                }
            } else if (item.type === 'text') {
                // テキストデータを追加
                formData.append(`messages[${index}]`, item.data);
            }
        });


        const admin_id = document.getElementById("js_greeting_account_id").value
        const loader = document.querySelector(".loader")
        const modal = document.querySelector(".broadcasting_message_modal")
        modal.classList.add("hidden")
        open_modal(loader)

        // fetch でデータを送信
        fetch(`/api/greeting_message/store/${admin_id}`, {
            method: 'POST',
            body: formData,
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then((res) => {
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
