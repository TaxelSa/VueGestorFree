<script setup>
import { RouterLink, RouterView } from 'vue-router';
</script>

<template>
  <div class="app-container">
    <nav class="navbar">
      <router-link to="" class="nav-link">Inicio</router-link>
      <router-link to="" class="nav-link">Servicios</router-link>
      <router-link to="" class="nav-link">Contacto</router-link>
    </nav>
    <main class="content">
      <router-view></router-view>
    </main>
  </div>
</template>

<style scoped>
.app-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 100vh;
  background: #eef5ff;
  font-family: 'Arial', sans-serif;
}

.navbar {
  display: flex;
  justify-content: center;
  gap: 15px;
  width: 100%;
  background: #007bff;
  padding: 10px 0;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-link {
  color: white;
  text-decoration: none;
  font-size: 16px;
  font-weight: 500;
  padding: 8px 12px;
  transition: opacity 0.3s;
}

.nav-link:hover {
  opacity: 0.7;
}

.content {
  width: 90%;
  max-width: 800px;
  margin-top: 20px;
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
}
</style>
