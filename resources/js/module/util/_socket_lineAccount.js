import io from 'socket.io-client';
import config from '../../config/config';

// Socket.IOサーバーへの接続を設定
let socket2 = io(config.socketUrl, {  // ベースURLのみ指定
      reconnection: true,
      reconnectionDelay: 1000,
      reconnectionDelayMax: 5000,
      reconnectionAttempts: Infinity,
      transports: ['websocket', 'polling']
});

// 接続が確立されたときに実行される
socket2.on('connect', () => {
      console.log('サーバーに接続されましたyoooooo');
});


export default socket2;