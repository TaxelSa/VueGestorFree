import { createRouter, createWebHistory } from 'vue-router';
<<<<<<< Updated upstream
import Proyectos from '../components/proyectos.vue';
import Equipo from '../components/Equipo.vue';

const routes = [
  { path: '/', component: () => import('../components/HelloWorld.vue') },
  { path: '/proyectos', component: Proyectos }, 
  { path: '/equipo', component: Equipo }
=======
import Inicio from '../components/inicio.vue';

const routes = [
  { path: '/', component: Inicio },
  { path: '/inicio', component: Inicio }, // Importación estática
>>>>>>> Stashed changes
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;

