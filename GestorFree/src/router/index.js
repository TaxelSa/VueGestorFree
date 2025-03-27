import { createRouter, createWebHistory } from 'vue-router';
import Proyectos from '../components/proyectos.vue';

const routes = [
  { path: '/', component: () => import('../components/HelloWorld.vue') },
  { path: '/proyectos', component: Proyectos }, 
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
