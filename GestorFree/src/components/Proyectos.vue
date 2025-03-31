<script setup>
import { ref, defineAsyncComponent } from 'vue';

const tabs = [
  { 
    name: 'Crea Tarea', 
    component: defineAsyncComponent(() => import('./CreaTarea.vue'))
  },
  { 
    name: 'Tablero', 
    component: defineAsyncComponent(() => import('./Kanban.vue'))
  },
  { 
    name: 'Cronograma', 
    component: defineAsyncComponent(() => import('./Cronograma.vue'))
  }
];
 
const activeTab = ref(tabs[0]);
</script>

<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
      <div class="tabs-wrapper bg-white rounded-lg shadow-sm">
        <div class="flex">
          <button 
            v-for="tab in tabs" 
            :key="tab.name" 
            @click="activeTab = tab"
            class="flex-1 px-6 py-3 text-sm font-medium transition-all duration-200 ease-in-out focus:outline-none"
            :class="activeTab.name === tab.name 
              ? 'text-blue-600 bg-blue-50 border-b-2 border-blue-600' 
              : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50 border-b-2 border-transparent'">
            {{ tab.name }}
          </button>
        </div>
      </div>
      
      <div class="mt-6 bg-white rounded-lg shadow-sm p-6 transition-all duration-300 ease-in-out">
        <transition name="fade" mode="out-in">
          <component 
            :is="activeTab.component" 
            :key="activeTab.name" 
            v-if="activeTab.component"
          >
            {{ activeTab.content }}
          </component>
          <div v-else class="text-gray-600">
            {{ activeTab.content }}
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<style scoped>
.tabs-wrapper {
  position: relative;
  overflow: hidden;
}

/* Transiciones para el contenido */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
  transform: translateY(0);
}
</style>
