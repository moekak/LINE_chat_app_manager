import { fetchGetOperation } from "../util/fetch.js";
import { formateDateToAsia } from "../util/formatDate.js";
import { createMessageRow } from "./elementTemplate.js";
import { open_modal } from "./modalOperation.js";

export const increateMessageCount = (sender_id, type) => {
      if (type == "user") {
            const count_elements = document.querySelectorAll(".js_message_count");
            count_elements.forEach((element)=>{
                  let id = element.getAttribute("data-id");

                  if(id == sender_id){
                        element.innerHTML = Number(element.innerHTML) + 1
                  }
            })
      }
};


      //   管理画面のshowページのユーザー表示更新(リアルタイムで)
export const changeDisplayOrder = (sender_id, receiver_id, sender_type) =>{
      if(sender_type == "user"){
            const elements = document.querySelectorAll(".js_chatUser_id")
            const parentElement = document.querySelector(".js_table")

            for (let element of elements) {
                  let id = element.getAttribute("data-id");
                  if(id == sender_id){
                        const newCloneDiv = element.cloneNode(true)
                        const message_count_element = newCloneDiv.querySelector(".js_message_count")
                        message_count_element.innerHTML = Number(message_count_element.innerHTML) + 1 
                        message_count_element.style.display = "flex";

                        const latest_message_element = newCloneDiv.querySelector(".js_latest_message_date")
                        latest_message_element.innerHTML = formateDateToAsia()

                        parentElement.insertBefore(newCloneDiv, parentElement.firstChild)   
                        parentElement.removeChild(element)
                        return 
                  }

            }

            fetchGetOperation(`/api/user/${sender_id}/account/${receiver_id}`)
            .then((res)=>{
                  parentElement.insertAdjacentHTML('afterbegin', createMessageRow(res, res["admin_account_id"], sender_id));
            })
      }
      
}


export const changeAccountDisplayOrder = (sender_id, receiver_id, sender_type, admin_login_id) =>{
      if(sender_type == "user" && document.getElementById("js_admin_account_id").value == admin_login_id){
            const elemets = document.querySelectorAll(".js_account_id")

            for (let element of elemets){
                  let account_id = element.getAttribute("data-id");
                  

                  if(account_id == receiver_id){
                        const parentElement = element.parentElement
                        const newClonedDiv = element.cloneNode(true);
                        const count_elment = newClonedDiv.querySelector(".js_total_count");
                        count_elment.innerHTML = Number(count_elment.innerHTML) + 1
                        count_elment.style.display = "flex";

                        const latest_message_element = newClonedDiv.querySelector(".js_latest_message_date")
                        latest_message_element.innerHTML = formateDateToAsia()

                        parentElement.insertBefore(newClonedDiv, parentElement.firstChild)   
                        parentElement.removeChild(element)

                        // アカウント編集
                        initializeAccountEditModal()
                        //一斉送信
                        initializeBroadcastMessageModal()
                        // アカウント削除
                        initializeAccountDeletionModal()
                        return 
                  }
            }

            
      }
}


// ユーザー編集処理
export const setDataForEditUserName = async (res) => {
      const userName_input = document.querySelector(".js_edit_account_input");
      const userId_input = document.querySelector(".js_account_id_input");

      userName_input.value = res["line_name"];
      userId_input.value = res["id"];
};

export const handleEditUserName = async (res)=>{
      const modal = document.getElementById("js_edit_account_modal")
      await setDataForEditUserName(res)
      open_modal(modal)
      
}


// アカウント削除処理
export const setActionUrl = (id, className) =>{
      let form = document.querySelector(`.${className}`)
      let action  = form.getAttribute("action");
      action = action.replace("ID_PLACEHOLDER", id);
      form.setAttribute("action", action);
}
// アカウント削除処理
export const setActionFullUrl = (id, className) =>{
      let form = document.querySelector(`.${className}`)
      let newAction = `/account/flag/update/valid/${id}`; 
      form.setAttribute("action", newAction);
}

export const setAccountName = (name, className) =>{
      const name_field = document.getElementById(className)
      name_field.innerHTML = name
}



// #########################################################################################
//                                  アカウント編集処理
// #########################################################################################


// 編集モーダルに編集したいアカウントのデータをセットする
const setAccountDataForEditing = (res) => {
      return new Promise((resolve) => {
            const message_input           = document.querySelector(".js_edit_account_input");
            const id_input                = document.querySelector(".js_account_id_input")
            const url_input               = document.querySelector(".js_edit_url_input")
            const second_account_input    = document.querySelector(".js_edit_secondAccount_input")
            const second_account_options  = document.querySelectorAll(".js_second_account")

            message_input.value                 = res["account_data"]["account_name"];
            id_input.value                      = res["account_data"]["id"];
            url_input.value                     = res["account_data"]["account_url"]
            second_account_input.value          = res["second_account"]["id"]
            second_account_input.textContent    = res["second_account"]["account_name"] ?? "予備アカウントを選択してください"
            second_account_input.selected       = true

            second_account_options.forEach((option)=>{
                  if(option.textContent == res["second_account"]["account_name"]){
                        option.style.display = "none"
                  }else{
                        option.style.display = "block"
                  }
            })
            resolve();
      });
};

// アカウントを編集するための初期処理
export const initializeAccountEditModal =() =>{
      const edit_btns = document.querySelectorAll(".js_edit_account_btn")
      const edit_modal = document.getElementById("js_edit_account_modal")
      const loader = document.querySelector(".loader")


      edit_btns.forEach((btn)=>{
            btn.addEventListener("click", (e) => {
                  // 編集をしたいアカウントのIDを取得する
                  let target_btn = e.currentTarget;
                  let account_id = target_btn.getAttribute("data-id");
                  
                  // formのactionの設定
                  setActionUrl(account_id, "js_edit_account_form")
                  // 編集モーダルを表示するまでローダーを表示する
                  open_modal(loader)
                  // 編集したいアカウントの情報を非同期で取得する
                  fetchGetOperation(`/account/edit/${account_id}`)
                        .then((res) => {
                              setAccountDataForEditing(res)
                        .then(() => {
                              // 編集モーダルの表示
                              open_modal(edit_modal);
                        });
                  });
            });
      })
}



// #########################################################################################
//                                  一斉メッセージ送信処理
// #########################################################################################

export const initializeBroadcastMessageModal = () =>{
      const sending_btns            = document.querySelectorAll(".js_send_message_btn")
      const broadcasting_modal      = document.getElementById("js_boradcasting_modal")

      sending_btns.forEach((btn)=>{
            btn.addEventListener("click", (e)=>{
                  e.preventDefault()
                  // 一斉送信メッセージモーダルを表示する
                  open_modal(broadcasting_modal);
                  // 一斉メッセージ行いたいアカウントのIDを取得し、inputに格納
                  let account_id = e.currentTarget.getAttribute("data-id")
                  document.getElementById("js_account_id").value = account_id
            })
      })
}



// #########################################################################################
//                            アカウント削除処理
// #########################################################################################


export const initializeAccountDeletionModal= () =>{
      const delete_btns             = document.querySelectorAll(".js_delete_account_btn")
      const delete_account_modal    = document.getElementById("js_delete_account_modal")

      delete_btns.forEach((delete_btn)=>{
            delete_btn.addEventListener("click", (e)=>{
                  // 削除したいアカウントのIDを取得する
                  let id = e.currentTarget.getAttribute("data-id");
                  let name = e.currentTarget.getAttribute("data-name")
                  setActionUrl(id, "js_delete_account_from")
                  setAccountName(name, "js_account_name")
                  open_modal(delete_account_modal)
            }) 
      })
}