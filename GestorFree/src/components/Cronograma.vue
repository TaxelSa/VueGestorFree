<script setup lang="ts">
import { ref, onMounted } from 'vue';

interface Task {
  id_tarea: number;
  nombre_tarea: string;
  descripcion: string;
  fecha_entrega: string;
  hora_entrega: string;
  prioridad: string | number;
  id_estado: string | number;
}

const tareas = ref<Task[]>([]);

const obtenerColorPrioridad = (prioridad: string | number) => {
  const p = Number(prioridad);
  switch (p) {
    case 1: return 'border-green-500 bg-green-100';
    case 2: return 'border-yellow-500 bg-yellow-100';
    case 3: return 'border-red-500 bg-red-100';
    default: return 'border-gray-300 bg-gray-50';
  }
};

const obtenerColorEstado = (estado: string | number) => {
  const e = Number(estado);
  switch (e) {
    case 1: return 'bg-gray-200 text-gray-800';
    case 2: return 'bg-blue-200 text-blue-800';
    case 3: return 'bg-green-200 text-green-800';
    default: return 'bg-gray-200 text-gray-800';
  }
};

const obtenerTareas = async () => {
  try {
    const response = await fetch('http://localhost/VueGestorFree/GestorFree/php/tareas.php');
    const data = await response.json();
    if (data.status === 'success') {
      tareas.value = data.data.sort((a: Task, b: Task) => {
        return new Date(a.fecha_entrega).getTime() - new Date(b.fecha_entrega).getTime();
      });
      console.log('Tareas y sus prioridades:', tareas.value.map(t => ({
        nombre: t.nombre_tarea,
        prioridad: t.prioridad,
        tipo_prioridad: typeof t.prioridad
      })));
    }
  } catch (error) {
    console.error('Error al cargar las tareas:', error);
  }
};

onMounted(() => {
  obtenerTareas();
});
</script>

<template>
  <div class="cronograma p-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Cronograma de Tareas</h2>
    
    <div class="space-y-4">
      <div v-for="tarea in tareas" 
           :key="tarea.id_tarea" 
           class="tarea-card p-4 rounded-lg border-2 transition-all hover:shadow-md"
           :class="obtenerColorPrioridad(tarea.prioridad)">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="font-semibold text-gray-800">{{ tarea.nombre_tarea }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ tarea.descripcion }}</p>
          </div>
          <div class="text-right">
            <div class="text-sm font-medium text-gray-600">
              {{ new Date(tarea.fecha_entrega).toLocaleDateString('es-ES', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
              }) }}
            </div>
            <div class="text-sm text-gray-500 mt-1">{{ tarea.hora_entrega }}</div>
          </div>
        </div>
        
        <div class="flex gap-2 mt-3">
          <span class="px-2 py-1 text-xs rounded-full"
                :class="obtenerColorEstado(tarea.id_estado)">
            {{ Number(tarea.id_estado) === 1 ? 'Por hacer' : 
               Number(tarea.id_estado) === 2 ? 'En progreso' : 'Completado' }}
          </span>
          <span class="px-2 py-1 text-xs rounded-full" 
                :class="obtenerColorPrioridad(tarea.prioridad)">
            Prioridad {{ tarea.prioridad }}
          </span>
        </div>
      </div>
      
      <div v-if="tareas.length === 0" class="text-center text-gray-500 py-8">
        No hay tareas programadas
      </div>
    </div>
  </div>
</template>

<style scoped>
.tarea-card {
  transition: all 0.2s ease;
}

.tarea-card:hover {
  transform: translateY(-2px);
}
</style>
