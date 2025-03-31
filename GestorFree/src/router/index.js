import { createRouter, createWebHistory } from 'vue-router';
import Kanban from '../components/Kanban.vue';
import Equipo from '../components/Equipo.vue';
import Inicio from '../components/inicio.vue';
import Login from '../components/login.vue';
import App from '../App.vue';
import Proyectos from '../components/Proyectos.vue';


const routes = [
  { path: '/', component: Inicio },
  { path: '/inicio', component: Inicio }, 
  { path: '/kanban', component: Kanban }, 
  { path: '/equipo', component: Equipo },
  { path: '/equipo', component: App },
  { path: '/login', component: Login },
  { path: '/proyectos', component: Proyectos }

];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;

