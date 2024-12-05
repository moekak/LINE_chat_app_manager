import { formateDateToAsia } from "../util/formatDate.js"

export const createMessageRowForFetch = (res, admin_account_id, sender_uuid) =>{

      const createdAtTokyo = formateDateToAsia(res["created_at"])
      const latestMessageDate = formateDateToAsia()

      const display = res["message_count"] > 0 ? "flex" : "none"
      
      return `
            <tr data-id=${sender_uuid} class="js_chatUser_id">
                  <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value=${res["id"]}></th>
                  <td w20>${res["line_name"]}</td>
                  <td data-id=${res["id"]}>
                        <div class="message_count js_message_count" style="display:${display}; font-weight: bold;">${res["message_count"]}</div>
                  </td>
                  <td class="js_latest_message_date">${latestMessageDate}</td>
                  <td>${createdAtTokyo}</td>
                  <td class="operation">
                        <button class="operation_icon"><a href="https://chat-system.info/${admin_account_id}/${res["id"]}"><img src="/img/icons8-message-24.png" alt=""></a></button>
                        <button class="operation_icon"><img src="/img/icons8-edit-24.png" alt=""></button>
                        <button class="operation_icon js_block_btn"><img src="/img/icons8-no-entry-24.png"" alt=""></button>
                  </td>
            </tr>
      `
}
export const createMessageRow = (res, admin_account_id, sender_uuid) =>{

      const createdAtTokyo = formateDateToAsia(res[0]["created_at"])
      const latestMessageDate = formateDateToAsia()

      const display = res[0]["message_count"] > 0 ? "flex" : "none"
      
      return `
            <tr data-id=${sender_uuid} class="js_chatUser_id">
                  <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value=${res[0]["id"]}></th>
                  <td w20>${res[0]["line_name"]}</td>
                  <td data-id=${res[0]["id"]}>
                        <div class="message_count js_message_count" style="display:${display}; font-weight: bold;">${res[0]["message_count"]}</div>
                  </td>
                  <td class="js_latest_message_date">${latestMessageDate}</td>
                  <td>${createdAtTokyo}</td>
                  <td class="operation">
                        <button class="operation_icon"><a href="https://chat-system.info/${admin_account_id}/${res[0]["id"]}"><img src="/img/icons8-message-24.png" alt=""></a></button>
                        <button class="operation_icon"><img src="/img/icons8-edit-24.png" alt=""></button>
                        <button class="operation_icon js_block_btn"><img src="/img/icons8-no-entry-24.png"" alt=""></button>
                  </td>
            </tr>
      `
}

export const createBroadcastMessageRow = (data, id) =>{
      // 改行を<br>タグに変換
      const displayedData = data.type == "text" ? data.display.replace(/\n/g, '<br>') : `<img data-file-index='${data.index}' src='${data.display}' class="displayImg js_img">`

      return `
            <div class="card js_card mb-2">
                  <div class="card-header" id="heading${data.elementLength}">
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
                        <div class="card-body js_data"data-id="${data.elementLength + 1}">${displayedData}</div>
                  </div>
            </div>
      `
}


