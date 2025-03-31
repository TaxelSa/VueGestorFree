<template>
  <div class="app-container">
    <aside class="sidebar">
      <h2 class="menu-title">Menú</h2>
      <nav class="menu">
        <router-link to="/inicio" class="menu-item">Tu trabajo</router-link>
        <router-link to="/proyectos" class="menu-item">Proyectos</router-link>
        <router-link to="/equipo" class="menu-item">Equipos</router-link>
        <div class="sesion">
          <!-- Se agregó el evento click para llamar al método logout -->
          <router-link @click="logout" class="menu-item">Cerrar Sesión</router-link>
        </div>

      </nav>
    </aside>
    <main class="content">
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
export default {
  methods: {
    // Método para cerrar sesión
    logout() {
      localStorage.removeItem("numero_control"); // Elimina el número de control del localStorage
      this.$router.push("/login"); // Redirige a la página de login
    }
  }
};
</script>

<style scoped>
.app-container {
  display: flex;
  min-height: 100vh;
  font-family: 'Arial', sans-serif;
  background: #f5f5f5;
}

.sidebar {
  width: 225px;
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
  color: #FF9B17;
}

.menu-item.router-link-active {
  background: #ffbd669d;
  color: #ee8a08;
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