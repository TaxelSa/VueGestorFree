<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const nombre_completo = ref(""); // Para almacenar el nombre del usuario
const logged_in = ref(false); // Para saber si el usuario está logueado

// Verificar si hay sesión activa al montar el componente
onMounted(async () => {
  try {
    const response = await fetch("http://localhost/VueGestorFree/Controllers/session.php"); // Ruta del script PHP
    const data = await response.json();
    
    if (data.logged_in) {
      logged_in.value = true;
      nombre_completo.value = data.nombre_completo; // Guardamos el nombre del usuario
    } else {
      logged_in.value = false;
      nombre_completo.value = "";
    }
  } catch (error) {
    console.error("Error al verificar la sesión:", error);
    logged_in.value = false;
  }
});

const logout = () => {
  // Eliminar los datos de sesión en el frontend
  localStorage.removeItem("numero_control");
  localStorage.removeItem("userName");
  nombre_completo.value = ""; // Limpiar el nombre del usuario en Vue
  logged_in.value = false; // Cambiar el estado a no logueado
  router.push("/login"); // Redirigir al login
};
</script>

<template>
  <div class="app-container">
    <aside class="sidebar">
      <h2 class="menu-title">Menú</h2>
      
      <!-- Mostrar el nombre del usuario si está logueado -->
      <h3 v-if="logged_in">{{ nombre_completo }}</h3>
      
      <!-- Mensaje si no está logueado -->
      <h3 v-else>Debe iniciar sesión para continuar</h3>
      
      <nav class="menu">
        <router-link to="/inicio" class="menu-item">Tu trabajo</router-link>
        <router-link to="/proyectos" class="menu-item">Proyectos</router-link>
        <router-link to="/equipo" class="menu-item">Equipos</router-link>
        <div class="sesion">
          <button @click="logout" class="menu-item">Cerrar Sesión</button>
        </div>
      </nav>
    </aside>
    <main class="content">
      <router-view></router-view>
    </main>
  </div>
</template>


<style scoped>
.app-container {
  display: flex;
  min-height: 100vh;
  font-family: 'Arial', sans-serif;
  background: #f5f5f5;
}

.sidebar {
  width: 250px;
  background: white;
  padding: 20px;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  position: fixed; /* Hace que el sidebar sea fijo */
  height: 100vh; /* Ocupa toda la altura de la pantalla */
  overflow-y: auto; /* Permite scroll si el menú es muy largo */
}

.menu {
  display: flex;
  flex-direction: column;
  height: calc(100vh - 100px); /* Altura ajustada (100vh - espacio del título y padding) */
}

.menu-title {
  color: #333;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

.menu-item {
  color: #555;
  text-decoration: none;
  font-size: 16px;
  padding: 12px 15px;
  border-radius: 4px;
  margin-bottom: 5px;
  transition: all 0.3s;
}

.menu-item:hover {
  background: #f0f0f0;
  color: #007bff;
}

.menu-item.router-link-active {
  background: #e6f0ff;
  color: #007bff;
  font-weight: 500;
}

.content {
  flex: 1;
  padding: 30px;
  background: #f5f5f5;
  margin-left: 250px; /* Igual al ancho del sidebar */
}

.sesion {
  margin-top: auto; /* Empuja el enlace de sesión hacia abajo */
  padding-top: 20px;
  border-top: 1px solid #eee; /* Línea separadora opcional */
}

.sesion .menu-item {
  color: #666;
  font-weight: 500;
}
</style>
