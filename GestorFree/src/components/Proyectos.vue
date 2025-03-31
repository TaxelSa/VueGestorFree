<script setup>
import { ref, defineAsyncComponent } from 'vue';
import { Globe, Calendar, List, LayoutGrid, Settings } from 'lucide-vue-next';

const tabs = [
  { name: 'Crea Tarea', component: defineAsyncComponent(() => import('./CreaTarea.vue')) },
  { name: 'Tablero', component: defineAsyncComponent(() => import('./Kanban.vue')) },
  { name: 'Cronograma', content: 'Aquí se muestra el cronograma del proyecto.' },
  { name: 'Configuración', content: 'Aquí puedes configurar el proyecto.' }
];

const activeTab = ref(tabs[0]);
</script>

<template>
  <div class="p-4">
    <div class="border-b flex space-x-6 pb-2">
      <button 
        v-for="tab in tabs" 
        :key="tab.name" 
        @click="activeTab = tab"
        class="flex items-center space-x-2 pb-2 border-b-2 transition duration-300 ease-in-out focus:outline-none"
        :class="activeTab.name === tab.name 
          ? 'border-blue-600 text-blue-600 font-bold' 
          : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-400'">
        <component :is="tab.icon" class="w-5 h-5" />
        <span>{{ tab.name }}</span>
      </button>
    </div>
    <div class="mt-4 p-6 border rounded-lg bg-white shadow-md text-center text-gray-700">
      <component :is="activeTab.component" />
    </div>
  </div>
</template>

