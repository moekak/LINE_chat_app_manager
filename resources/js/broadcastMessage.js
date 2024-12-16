import { deleteList, displayMessageToList, dragAndDrop, hasValue, hideErrorMsg } from "./module/component/broadcastMessageOperations.js"
import { close_modal, open_modal } from "./module/component/modalOperation.js"
import { fetchPostOperation } from "./module/util/fetch.js"
import { cleanHtmlContent } from "./module/util/messageService.js"
import { resizeImage } from "./module/util/processAndResizeImage.js"
import socket from "./module/util/socket.js"
import imageCompression from 'browser-image-compression';



// 一斉配信のテキスト箇所
const broadcastMessageInput = document.querySelector(".js_message_input")
const display_btn = document.querySelector(".js_message_display_btn")

const formDataArray = []; // FormDataを保持する配列を作成
const textArray = []
let index = 0

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
        formDataArray[index] = {"type" : "text", "data" : broadcastMessage}
        displayMessageToList(broadcastMessage, null, index, "js_accordion_wrapper", "accordion")
        deleteList("accordion", formDataArray)
        dragAndDrop("accordion", true)
        broadcastMessage = ""
        broadcastMessageInput.value = ""
        index++
    }
})

window.onload = (e)=>{
	dragAndDrop("accordion", true)
}

const uploads = document.querySelectorAll(".js_upload");
uploads.forEach((upload) => {
    upload.addEventListener("change", async (e) => {

        const file = e.target.files[0];
        if (!file) return;

         // 1. 圧縮
        const compressedFile = await imageCompression(file, {
            maxSizeMB: 1,
            maxWidthOrHeight: 1024,
            useWebWorker: true
        });

        const reader = new FileReader();
        reader.onload = e =>{
            console.log("index" + index);
            
            displayMessageToList(null, e.target.result, index, "js_accordion_wrapper", "accordion");
            deleteList("accordion", formDataArray)
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


        // // ドラッグ＆ドロップの初期化
        dragAndDrop("accordion", true);

        
    });
});


// 一斉送信の送信ボタンクリック処理

const submit_btn = document.querySelector(".js_message_submit_btn")
let sendMessage = []
submit_btn.addEventListener("click", ()=>{
    if(hasValue("accordion")){
        sendMessage = []
        const data = document.querySelectorAll(".js_data")


        for(let i = 0; i < data.length; i ++){
            console.log(Array.from(data)[i].getAttribute("data-file-index"));
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

        console.log(sendMessage);

        

        const admin_id = document.getElementById("js_account_id").value
        const loader = document.querySelector(".loader")
        const modal = document.querySelector(".broadcasting_message_modal")
        modal.classList.add("hidden")
        open_modal(loader)
        

        // fetch でデータを送信
        fetch(`/api/broadcast_message/store/${admin_id}`, {
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

                        console.log("socketサーバーに送信！");
                        console.log(res["data"]);

                        const sendingDatatoBackEnd = res["data"];
                        
                        socket.emit("broadcast message", {
                            sendingDatatoBackEnd: sendingDatatoBackEnd,
                            admin_id: admin_id,
                            created_at: created_at
                        });
                    }
            })
            .catch((error) => {
                console.error('Upload error:', error);
            });

    


    
        // fetchPostOperation(formData, `/api/broadcast_message/store/${admin_id}`)
        // .then((res)=>{

        //     console.log(res);
            
            // if(res["created_at"]){
    
            //     // モーダルをloaderを閉じる処理
            //     document.getElementById("js_boradcasting_modal").classList.add("hidden")
            //     document.querySelector(".bg").classList.add("hidden")
            //     loader.classList.add("hidden")
    
            //     // 成功メッセージを出す処理
            //     const success_el = document.getElementById("js_alert_success")
            //     success_el.style.display = "block";
            //     success_el.innerHTML = "一斉送信に成功しました"
            //     document.querySelector(".js_message_input").value = ""
            //     document.querySelector(".js_upload").value = ""
            //     document.querySelector(".js_accordion_wrapper").innerHTML = ""
    
            //     // 成功メッセージを出して2秒後に批評にする
            //     setTimeout(() => {
            //         success_el.style.display = "none"
            //     }, 2000);
    
            //     const created_at = res["created_at"]
            //     socket.emit("broadcast message", {sendingDatatoBackEnd, admin_account_id, created_at})
            // }
        // })
    }else{
        const error_el = document.querySelector(".js_broadcast_error")
        error_el.classList.remove("hidden")
    }
    
    
    
})
