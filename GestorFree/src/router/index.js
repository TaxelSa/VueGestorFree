import { createRouter, createWebHistory } from 'vue-router';
import Equipo from '../components/Equipo.vue';


const routes = [
  { path: '/equipo', component: Equipo}
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
