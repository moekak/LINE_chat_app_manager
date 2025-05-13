import { formateDateToAsia } from '../util/formatDate.js'
import { SYSTEM_URL } from '../../config/config.js';

export const createMessageRowForFetch = (res, admin_account_id, sender_uuid) =>{
      const createdAtTokyo = formateDateToAsia(res['created_at'])
      const latestMessageDate = res['latest_message_date'] ? formateDateToAsia(res['latest_message_date']) : ''
      const display = res['unread_count'] > 0 ? 'flex' : 'none'

      return `
            <tr data-id=${res['entity_uuid']} class='js_chatUser_id'>
                  <td w20 class='chat_user_name' data-simplebar>${res['line_name']}</td>
                  <td data-id=${res['id']}>
                        <div class='message_count js_message_count' style='display:${display}; font-weight: bold;'>${res['unread_count']}</div>
                  </td>
                  <td class='js_latest_message_date'>${latestMessageDate}</td>
                  <td>${createdAtTokyo}</td>
                  <td>
                        <div class="operation">
                              <a href='${SYSTEM_URL.CHAT_URL}/${res['id']}/${admin_account_id}'>
                                    <button type='submit' title='トーク' class='operation_icon redirect_btn'>
                                          <img src='/img/icons8-message-24.png' alt=''>
                                    </button>
                              </a>
                              <button class='operation_icon js_edit_user_btn' data-id=${res['id']}><img src='/img/icons8-edit-24.png' alt=''></button>
                              <button class='operation_icon js_block_btn' data-uuid=${res['entity_uuid']} data-name=${res['line_name']} data-id=${res['id']}><img src='/img/icons8-no-entry-24.png' alt=''></button>
                        </div>
                  </td>
            </tr>
      `
}
export const createMessageRow = (res, admin_account_id) =>{
      const createdAtTokyo = formateDateToAsia(res[0]['created_at'])
      const latestMessageDate = formateDateToAsia()
      const display = res[0]['unread_count'] > 0 ? 'flex' : 'none'

      return `
            <tr data-id=${res[0]['entity_uuid']} class='js_chatUser_id'>
                  <td w20 class='chat_user_name' data-simplebar>${res[0]['line_name']}</td>
                  <td data-id=${res[0]['id']}>
                        <div class='message_count js_message_count' style='display:${display}; font-weight: bold;'>${res[0]['unread_count']}</div>
                  </td>
                  <td class='js_latest_message_date'>${latestMessageDate}</td>
                  <td>${createdAtTokyo}</td>
                  <td>
                        <div class="operation">
                              <a href='${SYSTEM_URL.CHAT_URL}/${res[0]['id']}/${admin_account_id}'>
                                    <button type='submit' title='トーク' class='operation_icon redirect_btn'>
                                          <img src='/img/icons8-message-24.png' alt=''>
                                    </button>
                              </a>
                              <button class='operation_icon js_edit_user_btn' data-id=${res[0]['id']}><img src='/img/icons8-edit-24.png' alt=''></button>
                              <button class='operation_icon js_block_btn' data-uuid=${res[0]['entity_uuid']} data-name=${res[0]['line_name']} data-id=${res[0]['id']}><img src='/img/icons8-no-entry-24.png' alt=''></button>
                        </div>
                  </td>
            </tr>
      `
}

export const createBroadcastMessageRow = (data, id) =>{
      // 改行を<br>タグに変換
      // const displayedData = data.type == 'text' ? data.display.replace(/\n/g, '<br>') : `<img  src='${data.display}' class='displayImg js_img'>`
      const displayedData = data.type == 'text' ? escapeHtml(data.display).replace(/\n/g, '<br>') : `<img  src='${data.display}' class='displayImg js_img'>`

      return `
            <div class='card js_card mb-2'>
                  <div class='card-header js_headings' id='heading${data.elementLength}' data-id=${data.index}>
                        <div class='card-header-left'>
                              <img src='/img/icons8-drag-25.png' class='drag-handle' style ='width: 20px;'/>
                              <h5 class='mb-0'>
                                    <button class='btn collapsed' data-toggle='collapse' data-target='#collapse${data.elementLength}' aria-expanded='false' aria-controls='collapse${data.elementLength}'>
                                          ${escapeHtml(data.heading)}
                                    </button>
                              </h5>
                        </div>
                        <p class='js_deleteList'>×</p>
                  </div>
            
                  <div id='collapse${data.elementLength}' class='collapse' aria-labelledby='heading${data.elementLength}' data-parent='#${id}'>
                        <div class='card-body js_data' data-id='${data.elementLength}'>${displayedData}</div>
                  </div>
            </div>
      `
}

export const createAccountDataRow = (res, categories) =>{
      const style = res['unread_count'] > 0 ? 'flex' : 'none';
      const statusMap = {
            '1': '使用中',
            '2': '未使用',
            '3': '停止',
            '4': 'バン'
      };
      const status = statusMap[res['account_status']];
      const createdAtTokyo = formateDateToAsia(res['created_at'])
      return `
            <tr class='js_account_id' data-id='${res['entity_uuid']}'>
                  <td w20 class='account_name' data-simplebar>${res['account_name']}</td>
                  <td class=' text-center total_message-count'>
                        <div class='message_count js_mesage_count js_total_count' style='display: ${style}; font-weight: bold;'>${res['unread_count']}</div>
                  </td>
                  <td data-id=${res['id']} class='js_status' style='color: #008000; cursor: pointer;'>
                        <div class='btn-group'>
                              <button class='btn btn-secondary btn-sm dropdown-toggle js_status_btn' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                    ${status}
                              </button>
                              <ul class='dropdown-menu'>
                                    ${categories.filter(category => category.status !== status).map(category => `
                                                <li class='dropdown-item js_status_choices' 
                                                      data-current-status='${status}'
                                                      data-status-name='${category.status}'
                                                      data-status-id='${category.id}'
                                                      data-account-id='${res['id']}'>
                                                      ${category.status}
                                                </li>
                                    `).join('')}
                              </ul>
                        </div>
                  </td>
                  <td class='js_latest_message_date'>${res['latest_message_date'] ?? ''}</td>
                  <td>${createdAtTokyo}</td>
                  <td>
                        <div class="operation">
                              <a href='${CHAT_BASE_URL}/account/show/${res['id']}'><button  title='リスト' class='operation_icon'><img src='/img/icons8-user-24.png' alt=''></button></a>
                              <button  title='一斉送信' class='operation_icon js_send_message_btn' data-id=${res['id']}><img src='/img/icons8-send-24.png' alt=''></button>
                              <button  title='情報' class='operation_icon js_edit_account_btn' data-id=${res['id']}><img src='/img/icons8-edit-24.png' alt=''></button>
                              <button   title='削除'class='operation_icon js_delete_account_btn' type='submit' data-id=${res['id']} data-name=${res['account_name']}><img src='/img/icons8-delete-24.png' alt=''></button>
                        </div>
                  </td>
            </tr>
      `
}

export const createTextBlock = (value = null, id = null) =>{
      return `
            <div class='block-header'>
                  <div class='block-title'>
                  <svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                        <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                        <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                  </svg>
                  テキスト
                  </div>
                  <div class='block-actions'>
                  <button class='btn btn-icon btn-light delete-block'>
                        <svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                              <polyline points='3 6 5 6 21 6'></polyline>
                              <path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path>
                              <line x1='10' y1='11' x2='10' y2='17'></line>
                              <line x1='14' y1='11' x2='14' y2='17'></line>
                        </svg>
                  </button>
                  </div>
            </div>
            <div class='block-content'>
                  <textarea class='block-textarea' placeholder='テキストを入力してください' name='content_text' maxLength='1000' data-id=${id ?? ''}>${value ?? ''}</textarea>
            </div>
      `
}

export const createImageBlock = (blockCounter) =>{
      return `
            <div class='block-header'>
                  <div class='block-title'>
                        <svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                              <rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect>
                              <circle cx='8.5' cy='8.5' r='1.5'></circle>
                              <polyline points='21 15 16 10 5 21'></polyline>
                        </svg>
                        画像
                  </div>
                  <div class='block-actions'>
                        <button class='btn btn-icon btn-light delete-block'>
                              <svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                    <polyline points='3 6 5 6 21 6'></polyline>
                                    <path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path>
                                    <line x1='10' y1='11' x2='10' y2='17'></line>
                                    <line x1='14' y1='11' x2='14' y2='17'></line>
                              </svg>
                        </button>
                  </div>
            </div>
            <div class='block-content'>
                  <div class='image-upload'>
                        <input type='file' class='file-input' id='fileInput${blockCounter}' accept='image/*' name='image_path'>
                        <label for='fileInput${blockCounter}'>
                              <div class='image-placeholder'>
                                    <img src='/img/icons8-plus-50.png' alt='' class='image_element'>
                                    <p class='image-placeholder-txt'>ファイルの選択</p>
                              </div>
                        </label>
                  </div>
            </div>
      `
}

export const createMessageTemplate = (templates) => {

      return templates.map((template, index) => {
            let categoryName = ''
            if (template.category_name.length > 40) {
                  categoryName =  template.category_name.substring(0, 40) + '...';
            }else{
                  categoryName = template.category_name
            }
            let templateName = ''
            if (template.template_name.length > 40) {
                  templateName =  template.template_name.substring(0, 40) + '...';
            }else{
                  templateName = template.template_name
            }
            return `
                  <div class='template-item' data-id=${template['category_id']} data-order='${template['display_order']}'>
                        <input type='hidden' value='${template.template_id}' name='template_order[]' class='template_order'>
                        <div class='template-order-controls'>
                              <button type='button' class='order-btn move-up-btn' title='上に移動'>
                                    <svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                    <polyline points='18 15 12 9 6 15'></polyline>
                                    </svg>
                              </button>
                              <button type='button' class='order-btn move-down-btn' title='下に移動'>
                                    <svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                    <polyline points='6 9 12 15 18 9'></polyline>
                                    </svg>
                              </button>
                        </div>
                        <div class='template-content'>
                              ${template.contents.map(content => `
                                    <div class='js_blockcontents' data-id='${content.id}' data-order='${content.display_order}' data-type='${content.content_type}'> 
                                    <input type='hidden' class='js_content_text' value='${content.content_text || ''}'>
                                    <input type='hidden' class='js_image_path' value='${content.image_path || ''}' data-crop='${content.cropArea || ''}'>
                                    </div>
                              `).join('')}
                              <input type='hidden' value='${template.template_id}' class='template_id'>
                              <input type='hidden' value='${template.group_id}' class='group_id'>
                              <div class='template-title' style='font-weight: 600;' data-name='${template.template_name}'>${templateName}</div>
                              <div class='template-category' data-id='${template.category_id}'>${escapeHtml(categoryName)}</div>
                              <div class='template-text'>${template.contents[0].content_type === 'text' ? template.contents[0].content_text : '画像'}</div>
                        </div>
                        <div class='template-actions'>
                              <button type='button' class='action-btn edit-btn template_edit-btn' title='編集'>
                                    <svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                    <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                                    <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                                    </svg>
                              </button>
                              <button type='button' class='action-btn delete-btn template_delete_btn' title='削除'>
                                    <svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                    <polyline points='3 6 5 6 21 6'></polyline>
                                    <path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path>
                                    <line x1='10' y1='11' x2='10' y2='17'></line>
                                    <line x1='14' y1='11' x2='14' y2='17'></line>
                                    </svg>
                              </button>
                        </div>
                  </div>
            `;
      }).join('');
};


/**
 * エスケープ処理
 * @param {string} - エスケープする前の文字列
 * @return {string} - エスケープされた文字列
 */
const escapeHtml =(str) =>{
return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')

}


export const addCategoryButton =(category)=>{
      const categoryName = category['name'].length > 20 ? category['name'].substring(0, 20) + '...' : category['name']
      return `
            <button type='button' class='category-btn' data-id='${category['id']}' data-category='${category['name']}' title='${category['name']}'>${escapeHtml(categoryName)}</button>
      `
}



export const crateCategoryButton =(category)=>{
      return `
            <option class='category-option' value=${category['id']}>${escapeHtml(category['name'])}</option>
      `
}
export const editCategoryButton =(category)=>{
      return `
            <option class='edit-category' value=${category['id']}>${escapeHtml(category['name'])}</option>
      `
}



export const createMessageTemplateForAll = (templates) => {

      return templates.map((template, index) => {
            let categoryName = ''
            if (template.category_name.length > 40) {
                  categoryName =  template.category_name.substring(0, 40) + '...';
            }else{
                  categoryName = template.category_name
            }
            let templateName = ''
            if (template.template_name.length > 40) {
                  templateName =  template.template_name.substring(0, 40) + '...';
            }else{
                  templateName = template.template_name
            }
            return `
                  <div class='template-item' data-id=${template['category_id']} data-order='${template['display_order']}'>
                        <input type='hidden' value='${template.template_id}' name='template_order[]' class='template_order'>
                        <div class='template-content'>
                              ${template.contents.map(content => `
                                    <div class='js_blockcontents' data-id='${content.id}' data-order='${content.display_order}' data-type='${content.content_type}'> 
                                    <input type='hidden' class='js_content_text' value='${content.content_text || ''}'>
                                    <input type='hidden' class='js_image_path' value='${content.image_path || ''}' data-crop='${content.cropArea || ''}'>
                                    </div>
                              `).join('')}
                              <input type='hidden' value='${template.template_id}' class='template_id'>
                              <input type='hidden' value='${template.group_id}' class='group_id'>
                              <div class='template-title' style='font-weight: 600;' data-name='${template.template_name}'>${templateName}</div>
                              <div class='template-category' data-id='${template.category_id}'>${escapeHtml(categoryName)}</div>
                              <div class='template-text'>${template.contents[0].content_type === 'text' ? template.contents[0].content_text : '画像'}</div>
                        </div>
                        <div class='template-actions'>
                              <button type='button' class='action-btn edit-btn template_edit-btn' title='編集'>
                                    <svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                    <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                                    <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                                    </svg>
                              </button>
                              <button type='button' class='action-btn delete-btn template_delete_btn' title='削除'>
                                    <svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                    <polyline points='3 6 5 6 21 6'></polyline>
                                    <path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path>
                                    <line x1='10' y1='11' x2='10' y2='17'></line>
                                    <line x1='14' y1='11' x2='14' y2='17'></line>
                                    </svg>
                              </button>
                        </div>
                  </div>
            `;
      }).join('');
};



export const crateCategoryList =(category)=>{
      return `
            <tr class='category-item-row' data-id='${category['id']}'>
                  <td>
                        
                        <input type='hidden' name='id' value='${category['id']}' class='js_category_id'>
                        <input type='hidden' name='admin_id' value='${category['admin_id']}' class='js_admin_id'>
                        <input type='text' name='category_name_edit' class='category-edit-input disabled' readonly='' value='${category['name']}' maxlength='255'>
                  </td>
                  <td class='category-actions'>
                        <button type='button' class='btn btn-edit edit-category-btn' title='編集'>
                              <i class='fas fa-edit'></i>
                        </button>
                        <button type='button' class='btn btn-save save-category-btn disabled' title='保存'>
                              <i class='fas fa-check'></i>
                        </button>
                        <button type='button' class='btn btn-cancel cancel-edit-btn' title='キャンセル'>
                              <i class='fas fa-times'></i>
                        </button>
                  </td>
            </tr>
      `
}