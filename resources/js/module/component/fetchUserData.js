import { fetchGetOperation } from "../util/fetch.js"
import { handleEditUserName, setActionUrl } from "./accountUIOperations.js"

export const fetchSpecificUserInfo = (btn, modal) =>{
    btn.addEventListener("click", (e)=>{
        // 編集するユーザーidを取得する
        let user_id = e.currentTarget.getAttribute("data-id")
        setActionUrl(user_id, "js_edit_account_form")
        fetchGetOperation(`/api/user/${user_id}`)
        .then((res)=>{
            // ユーザーの情報をAPIを使用して取得
            handleEditUserName(res, modal)
        })
    })    
}