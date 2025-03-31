import { createApp } from 'vue';
import router from './router';
/*import Login from './components/login.vue';*/
import App from './App.vue';

const app = createApp(App);
app.use(router);
app.mount('#app');
