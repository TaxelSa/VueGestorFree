import { createRouter, createWebHistory } from 'vue-router';
import Proyectos from '../components/proyectos.vue';
import Equipo from '../components/Equipo.vue';
import Inicio from '../components/inicio.vue';
import Login from '../components/login.vue';
import App from '../App.vue';

const routes = [
  { path: '/', component: Inicio },
  { path: '/inicio', component: Inicio }, 
  { path: '/proyectos', component: Proyectos }, 
  { path: '/equipo', component: Equipo },
  { path: '/equipo', component: App },
  { path: '/login', component: Login }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;

