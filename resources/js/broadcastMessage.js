import BroadcastMessageOperator from "./module/component/broadcast/BroadcastMessageOperator.js";
import DragAndDrop from "./module/component/DragAndDrop.js";
import FileUploader from "./module/util/file/FileUploader.js";




window.onload = (e)=>{
    DragAndDrop.dragAndDrop("accordion", true)
}

const broadcastText = document.querySelector(".js_broadcast_error")
const errorTxt = document.querySelector(".js_error_txt")

const uploads = document.querySelectorAll(".js_upload");
const imageEditModal = document.getElementById("js_image_edit_modal")
uploads.forEach((upload) => {
    upload.addEventListener("change", async (e) => {
        // open_modal(imageEditModal)
        broadcastText.classList.add("hidden")
        const file = e.target.files[0];

        if (!file) return;

        const fileUploader = new FileUploader(file, errorTxt)
        await fileUploader.fileOperation()

        // // ドラッグ＆ドロップの初期化
        DragAndDrop.dragAndDrop("accordion", true);

        // TODO!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // formDataStateManager.getState()で画像だと、非同期の処理になるから値が取得できない問題
        new BroadcastMessageOperator("js_accordion_wrapper", "accordion")
    });
});


// // 一斉送信の送信ボタンクリック処理
// const submit_btn = document.querySelector(".js_message_submit_btn")
// let sendMessage = []
// submit_btn.addEventListener("click", ()=>{
//     if(hasValue("accordion")){
//         sendMessage = []
//         const data = document.querySelectorAll(".js_data")

//         // 順番通りに並べ替え
//         for(let i = 0; i < data.length; i ++){
//             sendMessage[i] = formDataArray[Array.from(data)[i].getAttribute("data-file-index")]
//         }

        
//         const formData = new FormData();

//         // sendMessage のデータを FormData に追加
//         sendMessage.forEach((item, index) => {
//             if (item.type === 'image') {
//                 // FormDataから画像を取得
//                 const imageFile = item.formData.get('image');
//                 if (imageFile) {
//                     formData.append(`images[${index}]`, imageFile, item.fileName);
//                 }
//             } else if (item.type === 'text') {
//                 // テキストデータを追加
//                 formData.append(`messages[${index}]`, item.data);
//             }
//         });


//         const admin_id = document.getElementById("js_account_id").value
//         const loader = document.querySelector(".loader")
//         const modal = document.querySelector(".broadcasting_message_modal")
//         modal.classList.add("hidden")
//         open_modal(loader)
        

//         // fetch でデータを送信
//         fetch(`/api/broadcast_message/store/${admin_id}`, {
//             method: 'POST',
//             body: formData,
//         })
//             .then((response) => {
//                 if (!response.ok) {
//                     throw new Error('Network response was not ok');
//                 }
//                 return response.json();
//             })
//             .then((res) => {
                
//                 if(res["created_at"]){
    
//                         // モーダルをloaderを閉じる処理
//                         document.getElementById("js_boradcasting_modal").classList.add("hidden")
//                         document.querySelector(".bg").classList.add("hidden")
//                         loader.classList.add("hidden")
            
//                         // 成功メッセージを出す処理
//                         const success_el = document.getElementById("js_alert_success")
//                         success_el.style.display = "block";
//                         success_el.innerHTML = "一斉送信に成功しました"
//                         document.querySelector(".js_message_input").value = ""
//                         document.querySelector(".js_upload").value = ""
//                         document.querySelector(".js_accordion_wrapper").innerHTML = ""
            
//                         // 成功メッセージを出して2秒後に批評にする
//                         setTimeout(() => {
//                             success_el.style.display = "none"
//                         }, 2000);
            
//                         const created_at = res["created_at"]
//                         const sendingDatatoBackEnd = res["data"];
                        
//                         socket.emit("broadcast message", {
//                             sendingDatatoBackEnd: sendingDatatoBackEnd,
//                             admin_id: admin_id,
//                             created_at: created_at
//                         });
//                     }
//             })
//             .catch((error) => {
//                 console.error('Upload error:', error);
//             });

//     }else{
//         const error_el = document.querySelector(".js_broadcast_error")
//         const errorTxt = document.querySelector(".js_error_txt")

//         errorTxt.innerHTML = `メッセージを入力して追加ボタンを押してください。<br> または画像を選択してください。`
//         error_el.classList.remove("hidden")
//     }
    
    
    
// })
