import { createRouter, createWebHistory } from 'vue-router';
import Proyectos from '../components/proyectos.vue';
import Equipo from '../components/Equipo.vue';
import Inicio from '../components/inicio.vue';

const routes = [
  { path: '/', component: Inicio },
  { path: '/inicio', component: Inicio }, 
  { path: '/proyectos', component: Proyectos }, 
  { path: '/equipo', component: Equipo }

];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;

