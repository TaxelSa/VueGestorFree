import { createRouter, createWebHistory } from 'vue-router';
import Baño from '../components/Baño.vue';


const routes = [
  { path: '/', component: () => import('../components/HelloWorld.vue') },
  { path: '/baño', component: Baño }, 
  
  
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
