export const fetchPostOperation = (data, url) => {

  return fetch(`${url}`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  }).then(async(response) => {

    if (response.status === 204) {
      return; // 204なら処理を終了
    }
    if (!response.ok) {
      const errorMessage = await response.text(); // レスポンスの内容を取得し、待機する
      // throw new Error(`サーバーエラー: ${response.status} - ${errorMessage}`);
    }
    return response.json();
  })
  .catch((error)=>{
      console.error(error);
  })
};
export const fetchGetOperation = (url) => {
  return fetch(`${url}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  }).then(async (response) => {

    if (response.status === 204) {
      return; // 204なら処理を終了
    }

    if (!response.ok) {
      const errorMessage = await response.text(); // レスポンスの内容を取得し、待機する
      // throw new Error(`サーバーエラー: ${response.status} - ${errorMessage}`);
    }
    return response.json(); // 正常な場合はJSONとして返す
  })
  .then((data) => {

    return data
  })
  .catch((error) => {
    console.error("エラーが発生しました:", error.message);
  });
};