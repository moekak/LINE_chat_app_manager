import { createBroadcastMessageRow } from "./elementTemplate.js";

export const displayMessageToList = (message, src, index, className, id) =>{
    
    // メッセージ表示リストの親要素を取得
    const parentElement = document.querySelector(`.${className}`);
    // 親要素の子要素をすべて取得し、その数を取得する
    let elementLength = parentElement.querySelectorAll(".js_card").length
    // テキストが最大文字超えていたら...にする

    let heading;
    let display;
    let type;
    
    if(message){
        const MAX_LENGTH = 20
        heading = message.length > MAX_LENGTH ? message.substr(0, MAX_LENGTH) + "..." : message
        display = message
        type = "text"
        index = null
    }

    if(src){
        heading = "画像"
        display = src
        type = "img"
        index = index
    }
    
    // HTML作成し、親要素に挿入する
    const data = {heading, display, elementLength, type, index}
    const template = createBroadcastMessageRow(data, id);
    parentElement.insertAdjacentHTML('beforeend', template);
}

export const dragAndDrop = (id) =>{
	const elem = document.getElementById(id);
	Sortable.create(elem, {
		animation: 150,
        handle: '.drag-handle',
	});
}

// メッセージ表示リストから削除する処理
export const deleteList = (id, upload = null, ) =>{
    let delete_btns = document.querySelectorAll(".js_deleteList")
    const accordion = document.getElementById(id)

    
    delete_btns.forEach((btn)=>{
        btn.addEventListener("click", (e)=>{

            if(upload){
                upload.value = ""
            }

            let list_el = e.currentTarget.parentElement.parentElement

            if(accordion.contains(list_el)){
                accordion.removeChild(list_el) 
            }
            
        })
    })
}


// 送信ボタンを押す前の値があるかのチェック
export const hasValue = (id)=>{
    const accordion = document.getElementById(id)
    const lists = accordion.querySelectorAll(".js_card")

    console.log(lists);
    
    return lists.length > 0
}

export const hideErrorMsg = () =>{
    const error_el = document.querySelector(".js_broadcast_error")
    if(!error_el.classList.contains("hidden")) error_el.classList.add("hidden")
}