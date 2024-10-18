
export const formateDateToAsia = (createdAt = null) =>{
    // Date オブジェクトに変換

    const date = createdAt ? new Date(createdAt) : new Date()

    // Asia/Tokyo のタイムゾーンに変換（YYYY-MM-DD HH:MM:SS形式）
    const options = {
        timeZone: 'Asia/Tokyo',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false // 24時間形式にする
    };
    
    // toLocaleStringで整形しながら表示
    const dateTokyo = date.toLocaleString('ja-JP', options).replace(/\//g, '-').replace(',', '');

    return dateTokyo
}