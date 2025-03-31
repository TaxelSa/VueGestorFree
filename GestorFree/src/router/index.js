import { createRouter, createWebHistory } from 'vue-router';
/*import Kanban from '../components/Kanban.vue';*/
import Equipo from '../components/Equipo.vue';
import Inicio from '../components/inicio.vue';
import Login from '../components/login.vue';
import Proyectos from '../components/Proyectos.vue';


const routes = [
  { path: '/', redirect: '/login' },  // Redirige la página principal al login
  { path: '/login', component: Login },
  { path: '/inicio', component: Inicio, meta: { requiresAuth: true } },
  /*{ path: '/proyectos', component: Proyectos, meta: { requiresAuth: true } },*/
  { path: '/equipo', component: Equipo, meta: { requiresAuth: true } },
  /*{ path: '/kanban', component: Kanban }, */
  { path: '/equipo', component: Equipo },
  { path: '/proyectos', component: Proyectos, meta: { requiresAuth: true }},
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// 🔹 **Protección de rutas**  
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem("numero_control"); // Si hay sesión guardada

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login'); // Si no está autenticado, redirigir al login
  } else {
    next(); // Si está autenticado o la ruta no necesita auth, continuar
  }
});

export default router;
