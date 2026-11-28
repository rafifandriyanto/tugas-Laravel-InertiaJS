// ambil library axios buat ngirim request http dari frontend ke backend
import axios from 'axios';
// simpen axios ke objek global window biar bisa dipanggil dari mana aja
window.axios = axios;

// set header default biar laravel tau kalo request dari axios ini bentuknya ajax
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
