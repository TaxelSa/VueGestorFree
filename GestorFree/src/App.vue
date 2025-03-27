<script setup>
import { RouterLink, RouterView } from 'vue-router';
</script>

<template>
  <div class="app-container">
    <aside class="sidebar">
      <h2 class="menu-title">Menú</h2>
      <nav class="menu">
        <router-link to="/tu-trabajo" class="menu-item">Tu trabajo</router-link>
        <router-link to="/proyectos" class="menu-item">Proyectos</router-link>
        <router-link to="/equipo" class="menu-item">Equipo</router-link>
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
}

.menu-title {
  color: #333;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

.menu {
  display: flex;
  flex-direction: column;
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
}
</style>
