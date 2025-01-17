import DynamicListManager from "../util/DynamicListManager.js";
import { fetchGetOperation } from "../util/fetch.js";
import { formateDateToAsia } from "../util/formatDate.js";
import socket from "../util/socket.js";
import userStateManager from "../util/state/UserStateManager.js";
import { initializeAccountDeletionModal, initializeAccountEditModal, initializeBroadcastMessageModal, initializeUserModals } from "./accountModalInitializers.js";
import { createMessageRow, createMessageRowForFetch } from "./elementTemplate.js";
import { open_modal } from "./modalOperation.js";


// メッセージ未読数をリアルタイムでカントアップする
export const increateMessageCount = (sender_id, type) => {
      // 送信者がユーザーの場合
      if (type == "user") {
            const count_elements = document.querySelectorAll(".js_message_count");
            count_elements.forEach((element)=>{
                  let id = element.getAttribute("data-id");
                  // 送信者ユーザーIDとDOM要素が同じところ見つけ、未読数を増やす
                  if(id == sender_id){
                        element.innerHTML = Number(element.innerHTML) + 1
                  }
            })
      }
};


// 管理画面のshowページのユーザー表示更新(リアルタイムで)
export const changeDisplayOrder = async (sender_id, receiver_id, sender_type) =>{
      if(sender_type == "user"){
            const elements          = document.querySelectorAll(".js_chatUser_id")
            const parentElement     = document.querySelector(".js_table")

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

                        //ユーザー管理に関連するモーダルの初期化
                        initializeUserModals(socket)
                        await handleChatRedirect()
                        return 
                  }
            }

            const response = await fetchGetOperation(`/api/user/${sender_id}/account/${receiver_id}`)
            parentElement.insertAdjacentHTML('afterbegin', createMessageRow(response[0], response["admin_account_id"]));
            userStateManager.setState(response[0]["id"])

            //ユーザー管理に関連するモーダルの初期化
            initializeUserModals(socket)
            await handleChatRedirect()
      }
      
}


export const changeAccountDisplayOrder = (receiver_id, sender_type, admin_login_id) =>{
      let hasAccount = false;
      if(sender_type == "user" && document.getElementById("js_admin_account_id").value == admin_login_id){
            const elemets = document.querySelectorAll(".js_account_id")

            for (let element of elemets){
                  let account_id = element.getAttribute("data-id");

                  if(account_id == receiver_id){
                        
                        hasAccount == true
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
                        // ステータス変更
                        initializeAccountStatusManager()
                        return 
                  }
            }

            if(!hasAccount){
                  const data = {"account_uuid": receiver_id}
                  const dynamicListManager = new DynamicListManager(data, "/api/fetch/account");
                  dynamicListManager.fetchData() 
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


export const handleEditUserName = async (res, modal, type)=>{
      await setDataForEditUserName(res)  
      open_modal(modal)
}


// アカウント削除処理
export const setActionUrl = (id, className) =>{

      let form = document.querySelector(`.${className}`)
      let action  = form.getAttribute("action");

      // URLに既存のIDが含まれているかを正規表現で探す
      // パターン: 数字がID部分に相当する (例: /update/26 のようなURL)
      const idPatternUpdate = /\/update\/\d+/;
      const isPatternBlock = /\/block\/\d+/;

      if (action.match(idPatternUpdate)) {
            // 既存のIDを新しいIDに置き換える
            action = action.replace(idPatternUpdate, `/update/${id}`);
      } 
      if (action.match(isPatternBlock)) {
            // 既存のIDを新しいIDに置き換える
            action = action.replace(isPatternBlock, `/block/${id}`);
      } 

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

// 編集モーダルに編集したいアカウントのデータをセットする
export const setAccountDataForEditing = (res) => {
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



export const setLineMessageForUpdating = (res) =>{
      return new Promise((resolve) => {
            const message_Input = document.querySelector(".js_line_message_input")
            message_Input.value = res
            resolve()
      });
}


// ステータス変更処理
export const initializeAccountStatusManager =()=>{
      const statuses = document.querySelectorAll(".js_status_choices")
      const loader = document.querySelector(".loader")

      statuses.forEach((status)=>{
            status.addEventListener("click", (e)=>{
                  let status_id = e.currentTarget.getAttribute("data-status-id")
                  let account_id = e.currentTarget.getAttribute("data-account-id")
                  let current_status_name = e.currentTarget.getAttribute("data-current-status")

                  // もし変更後のステータスが使用中の場合
                  if(current_status_name == "使用中"){
                        open_modal(loader)
                        fetchGetOperation(`/api/account/${account_id}`)
                        .then((res)=>{
                              // ステータスを変更しようとしているアカウントが予備アカウントがない場合モーダルを出す
                              if(!res){
                                    const modal = document.querySelector(".js_alert_model")
                                    open_modal(modal)
                                    document.getElementById("js_edit_second_account_id").value = account_id
                                    
                              }else{
                                    updateAccountStatus(account_id, status_id, current_status_name)
                              }
                        })  
                  } else{
                        updateAccountStatus(account_id, status_id, current_status_name)
                  }
            })
      })

      const updateAccountStatus = (account_id, status_id, current_status_name) =>{
            open_modal(loader)
            fetchGetOperation(`/api/account/${account_id}/status/${status_id}/current_stautus/${current_status_name}/update`)
            .then((res)=>{
                  // ステータス変更に成功した場合
                  if(res){
                        window.scrollTo({
                              top: 0,
                              left: 0,
                              behavior: 'auto'  // 'auto' ensures instant scrolling
                        });
                        localStorage.setItem("status_update", "success")
                        window.location.reload()
                  }
            })
      }
}


export const handleChatRedirect = async () => {
      const redirect_btns = document.querySelectorAll(".js_redirect_btn");
      redirect_btns.forEach((btn) => {
            btn.addEventListener("click", async (e) => {
                  let admin_id = e.currentTarget.getAttribute("data-admin-id")
                  let user_id = e.currentTarget.getAttribute("data-user-id")
                  e.preventDefault();
                  await submitRedirectForm(admin_id, user_id);
            });
      });
};



const submitRedirectForm = async (adminId, userId) => {
      const token = await fetchGetOperation("/api/token/generate");
      const tokenElement = document.querySelector(".js_token");
      const user_id = document.querySelector(".js_user_el")
      const admin_id = document.querySelector(".js_admin_el")

      tokenElement.value = token;
      user_id.value= userId
      admin_id.value= adminId

      const form = document.querySelector(".js_redirect_form");
      form.submit();
};


export const toggleDisplayButtonState = (btn, message) =>{
      btn.classList.toggle("disabled_btn", message.length === 0);
}