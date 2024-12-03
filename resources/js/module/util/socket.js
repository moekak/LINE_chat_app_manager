import io from 'socket.io-client';
import config from '../../config/config.js';

// Socket.IOサーバーへの接続を設定
let socket = io(config.socketUrl, {
      reconnection: true,         // 自動再接続を有効にする
      reconnectionDelay: 1000,    // 再接続の遅延時間 (ミリ秒)
      reconnectionDelayMax : 5000, // 再接続の最大遅延時間 (ミリ秒)
      reconnectionAttempts: Infinity // 再接続の試行回数 (無限に設定)
});

// 接続が確立されたときに実行される



export const registerUser = (admin_id, type) =>{
      socket.emit("register", `${type}${admin_id}`)
}

export default socket;