import { toggleDisplayButtonState } from "./accountUIOperations.js";
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
        index = index
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

export const dragAndDrop = (id, changeOrder = false) =>{
	const elem = document.getElementById(id);
    console.log("changeOrder value:", changeOrder); // changeOrderの値を確認
    const options = {
        animation: 150,
        handle: '.drag-handle',
        onEnd(evt) {  // onEndを直接ここに定義
            if (changeOrder) {  // 条件チェックをonEnd内部で行う
                const items = document.querySelectorAll(".js_data");
                const headings = document.querySelectorAll(".js_headings")
                console.log("Items found:", items.length);
                
                Array.from(items).forEach((item, index) => {
                    item.setAttribute('data-id', index);
                });
                Array.from(headings).forEach((heading, index) => {
                    heading.setAttribute('data-id', index);
                });
            }
        }
    };


    Sortable.create(elem, options);
}

// メッセージ表示リストから削除する処理
export const deleteList = (id, formData) =>{
    let delete_btns = document.querySelectorAll(".js_deleteList")
    const accordion = document.getElementById(id)

    
    delete_btns.forEach((btn)=>{
        btn.addEventListener("click", (e)=>{
            let target_id = e.currentTarget.parentElement.getAttribute("data-id")
            formData = formData.filter((data, index) =>index != target_id);

            let list_el = e.currentTarget.parentElement.parentElement

            if(accordion.contains(list_el)){
                accordion.removeChild(list_el) 
            }

            const elements = document.querySelectorAll(".js_data")
            elements.forEach((el, index)=>{
                el.setAttribute("data-id", index)
            })

            const headings = document.querySelectorAll(".js_headings")
            headings.forEach((el, index)=>{
                el.setAttribute("data-id", index)
            })

            toggleDisplayButtonState(document.querySelector(".js_message_submit_btn "), document.querySelectorAll(".js_headings"))
            
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