// export default {
//     socketUrl: 'https://chat-socket.info:3000',
    
// };

// export const SYSTEM_URL = {
//     IMAGE_URL: "https://line-chat-app.s3.ap-northeast-1.amazonaws.com/images",
//     FETCH_GREETINGMESSAGE: "/api/greeting_message/store",
//     FETCH_GREETINGMESSAE_GET: "/api/greetingMessage/adminId",
//     CHAT_URL : "https://chat-system.info/admin/chat",
//     CHAT_BASE_URL:"https://chat-system.info"
// };

export const ERROR_TEXT = {
    TEMPLATE_NAME_EMPTY_ERROR : "テンプレート名を入力してください" ,
    CATEGORY_EMPTY_ERROR : "カテゴリーを選択してください" ,
    CONTENTS_EMPTY_ERROR : "一つ以上メッセージを入力してください。" ,
    CREATE_CATEGORY_ERROR: "カテゴリー追加に失敗しました。再度お試しください。",
    CREATE_TEMPLATE_ERROR: "テンプレート追加に失敗しました。再度お試しください。"
}

export const SUCCESS_TEXT = {
    CREATE_TEMPLATE_SUCCESS: "テンプレートが正常に作成されました",
    CREATE_NEW_CATEGORY: "カテゴリーの追加に成功しました。",
    DELETE_TEMPLATE: "テンプレートの削除に成功しました。"
}

// // // 開発用
export default {
    socketUrl: 'https://socket.line-chat-system-dev.tokyo:3000',
    
};

// // 開発用
export const SYSTEM_URL = {
    IMAGE_URL: "https://line-chat-app-dev.s3.ap-northeast-1.amazonaws.com/images",
    FETCH_GREETINGMESSAGE: "/api/greeting_message/store",
    FETCH_GREETINGMESSAE_GET: "/api/greetingMessage/adminId",
    CHAT_URL : "https://chat.line-chat-system-dev.tokyo/admin/chat",
    CHAT_BASE_URL:"https://chat.line-chat-system-dev.tokyo"
};