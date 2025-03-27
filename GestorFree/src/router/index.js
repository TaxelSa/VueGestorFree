import { createRouter, createWebHistory } from 'vue-router';
import Proyectos from '../components/proyectos.vue';
import Equipo from '../components/Equipo.vue';

const routes = [
  { path: '/', component: () => import('../components/HelloWorld.vue') },
  { path: '/proyectos', component: Proyectos }, 
  { path: '/equipo', component: Equipo }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
