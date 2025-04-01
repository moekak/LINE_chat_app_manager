import { formateDateToAsia } from "../util/formatDate.js"
import { SYSTEM_URL } from "../../config/config.js";

export const createMessageRowForFetch = (res, admin_account_id, sender_uuid) =>{
      const createdAtTokyo = formateDateToAsia(res["created_at"])
      const latestMessageDate = res["latest_message_date"] ? formateDateToAsia(res["latest_message_date"]) : ""
      const display = res["unread_count"] > 0 ? "flex" : "none"

      return `
            <tr data-id=${res["entity_uuid"]} class="js_chatUser_id">
                  <td w20 class="chat_user_name" data-simplebar>${res["line_name"]}</td>
                  <td data-id=${res["id"]}>
                        <div class="message_count js_message_count" style="display:${display}; font-weight: bold;">${res["unread_count"]}</div>
                  </td>
                  <td class="js_latest_message_date">${latestMessageDate}</td>
                  <td>${createdAtTokyo}</td>
                  <td class="operation">
                        <form action="${SYSTEM_URL.CHAT_URL}" method="POST" class="js_redirect_form">
                              <input type="hidden" name="admin_id" class="js_admin_el">
                              <input type="hidden" name="user_id" class="js_user_el">
                              <input type="hidden" name="token" class="js_token">
                              <button type="submit" class="operation_icon js_redirect_btn" data-user-id="${res["id"]}" data-admin-id=${admin_account_id}><img src="/img/icons8-message-24.png" alt=""></button>
                        </form>
                        <button class="operation_icon js_edit_user_btn" data-id=${res["id"]}><img src="/img/icons8-edit-24.png" alt=""></button>
                        <button class="operation_icon js_block_btn" data-uuid=${res["entity_uuid"]} data-name=${res["line_name"]} data-id=${res["id"]}><img src="/img/icons8-no-entry-24.png" alt=""></button>
                  </td>
            </tr>
      `
}
export const createMessageRow = (res, admin_account_id) =>{
      const createdAtTokyo = formateDateToAsia(res[0]["created_at"])
      const latestMessageDate = formateDateToAsia()
      const display = res[0]["unread_count"] > 0 ? "flex" : "none"

      return `
            <tr data-id=${res[0]["entity_uuid"]} class="js_chatUser_id">
                  <td w20 class="chat_user_name" data-simplebar>${res[0]["line_name"]}</td>
                  <td data-id=${res[0]["id"]}>
                        <div class="message_count js_message_count" style="display:${display}; font-weight: bold;">${res[0]["unread_count"]}</div>
                  </td>
                  <td class="js_latest_message_date">${latestMessageDate}</td>
                  <td>${createdAtTokyo}</td>
                  <td class="operation">
                        <form action="${SYSTEM_URL.CHAT_URL}" method="POST" class="js_redirect_form">
                              <input type="hidden" name="admin_id" class="js_admin_el">
                              <input type="hidden" name="user_id" class="js_user_el">
                              <input type="hidden" name="token" class="js_token">
                              <button type="submit" class="operation_icon js_redirect_btn" data-user-id="${res[0]["id"]}" data-admin-id=${admin_account_id}><img src="/img/icons8-message-24.png" alt=""></button>
                        </form>
                        <button class="operation_icon js_edit_user_btn" data-id=${res[0]["id"]}><img src="/img/icons8-edit-24.png" alt=""></button>
                        <button class="operation_icon js_block_btn" data-uuid=${res[0]["entity_uuid"]} data-name=${res[0]["line_name"]} data-id=${res[0]["id"]}><img src="/img/icons8-no-entry-24.png" alt=""></button>
                  </td>
            </tr>
      `
}

export const createBroadcastMessageRow = (data, id) =>{
      // 改行を<br>タグに変換
      const displayedData = data.type == "text" ? data.display.replace(/\n/g, '<br>') : `<img  src='${data.display}' class="displayImg js_img">`

      return `
            <div class="card js_card mb-2">
                  <div class="card-header js_headings" id="heading${data.elementLength}" data-id=${data.index}>
                        <div class="card-header-left">
                              <img src="/img/icons8-drag-25.png" class="drag-handle" style ="width: 20px;"/>
                              <h5 class="mb-0">
                                    <button class="btn collapsed" data-toggle="collapse" data-target="#collapse${data.elementLength}" aria-expanded="false" aria-controls="collapse${data.elementLength}">
                                          ${data.heading}
                                    </button>
                              </h5>
                        </div>
                        <p class="js_deleteList">×</p>
                  </div>
            
                  <div id="collapse${data.elementLength}" class="collapse" aria-labelledby="heading${data.elementLength}" data-parent="#${id}">
                        <div class="card-body js_data" data-id="${data.elementLength}">${displayedData}</div>
                  </div>
            </div>
      `
}

export const createAccountDataRow = (res, categories) =>{
      const style = res["unread_count"] > 0 ? "flex" : "none";
      const statusMap = {
            "1": "使用中",
            "2": "未使用",
            "3": "停止",
            "4": "バン"
      };
      const status = statusMap[res["account_status"]];
      const createdAtTokyo = formateDateToAsia(res["created_at"])
      return `
            <tr class="js_account_id" data-id="${res["entity_uuid"]}">
                  <td w20 class="account_name" data-simplebar>${res["account_name"]}</td>
                  <td class=" text-center total_message-count">
                        <div class="message_count js_mesage_count js_total_count" style="display: ${style}; font-weight: bold;">${res["unread_count"]}</div>
                  </td>
                  <td data-id=${res["id"]} class="js_status" style="color: #008000; cursor: pointer;">
                        <div class="btn-group">
                              <button class="btn btn-secondary btn-sm dropdown-toggle js_status_btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    ${status}
                              </button>
                              <ul class="dropdown-menu">
                                    ${categories.filter(category => category.status !== status).map(category => `
                                                <li class="dropdown-item js_status_choices" 
                                                      data-current-status="${status}"
                                                      data-status-name="${category.status}"
                                                      data-status-id="${category.id}"
                                                      data-account-id="${res["id"]}">
                                                      ${category.status}
                                                </li>
                                    `).join('')}
                              </ul>
                        </div>
                  </td>
                  <td class="js_latest_message_date">${res["latest_message_date"] ?? ""}</td>
                  <td>${createdAtTokyo}</td>
                  <td class="operation">
                        <a href="${CHAT_BASE_URL}/account/show/${res["id"]}"><button class="operation_icon"><img src="/img/icons8-user-24.png" alt=""></button></a>
                        <button class="operation_icon js_edit_account_btn" data-id=${res["id"]}><img src="/img/icons8-edit-24.png" alt=""></button>
                        <button class="operation_icon js_send_message_btn" data-id=${res["id"]}><img src="/img/icons8-send-24.png" alt=""></button>
                        <button class="operation_icon js_delete_account_btn" type="submit" data-id=${res["id"]} data-name=${res["account_name"]}><img src="/img/icons8-delete-24.png" alt=""></button>
                  </td>
            </tr>
      `
}

export const createTextBlock = (value = null, id = null) =>{
      return `
            <div class="block-header">
                  <div class="block-title">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                  </svg>
                  テキスト
                  </div>
                  <div class="block-actions">
                  <button class="btn btn-icon btn-light delete-block">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <polyline points="3 6 5 6 21 6"></polyline>
                              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                              <line x1="10" y1="11" x2="10" y2="17"></line>
                              <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                  </button>
                  </div>
            </div>
            <div class="block-content">
                  <textarea class="block-textarea" placeholder="テキストを入力してください" name="content_text" max="1000" data-id=${id ?? ""}>${value ?? ""}</textarea>
            </div>
      `
}

export const createImageBlock = (blockCounter) =>{
      return `
            <div class="block-header">
                  <div class="block-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                              <circle cx="8.5" cy="8.5" r="1.5"></circle>
                              <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        画像
                  </div>
                  <div class="block-actions">
                        <button class="btn btn-icon btn-light delete-block">
                              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                              </svg>
                        </button>
                  </div>
            </div>
            <div class="block-content">
                  <div class="image-upload">
                        <input type="file" class="file-input" id="fileInput${blockCounter}" accept="image/*" name="image_path">
                        <label for="fileInput${blockCounter}">
                              <div class="image-placeholder">
                                    <img src="/img/icons8-plus-50.png" alt="" class="image_element">
                                    <p class="image-placeholder-txt">ファイルの選択</p>
                              </div>
                        </label>
                  </div>
            </div>
      `
}

