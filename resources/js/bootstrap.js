import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';


// window.Pusher = Pusher;

// window.Echo = new Echo({
//   broadcaster: 'pusher',
//   key: 'b95e6ac55372befce368',
//   cluster: 'ap3',
//   forceTLS: true
// });


// console.log("2222")

// try{
//      var channel = window.Echo.channel('my-channel');
//       channel.listen('.my-event', function(data) {
//       alert(JSON.stringify(data));
//       }); 
// }catch (error){
//       console.log(error);

