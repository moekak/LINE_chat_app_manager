export const prepareMessageData = () => {
    // サーバーに送信するデータをすべて取得する
    const body = document.querySelector(".js_message_input").value
    const formatted_body = body.replace(/\n/g, '<br>'); // 改行文字を <br> タグに置き換える
    const admin_account_id = document.getElementById("js_account_id").value

    return { body, formatted_body, admin_account_id};
  };
  

//<br> タグを含むすべての HTML タグを除去し、適切な改行を維持する
export const cleanHtmlContent =(html) => {
  // 1. <br>タグを改行文字に置換
  let text = html.replace(/<br\s*\/?>/gi, '\n');
  
  // 2. その他のHTMLタグを除去
  text = text.replace(/<[^>]+>/g, '');
  
  // 3. HTMLエンティティをデコード
  let textarea = document.createElement('textarea');
  textarea.innerHTML = text;
  text = textarea.value;
  
  // 4. 連続する改行を1つの改行に置換
  text = text.replace(/\n{3,}/g, '\n\n');
  
  // 5. 前後の空白を除去
  return text.trim();
}
