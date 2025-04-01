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
const tareaSeleccionada = ref<Task | null>(null);

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

const eliminarTarea = async (id_tarea: number) => {
  console.log(`Intentando eliminar la tarea con ID: ${id_tarea}`);
  try {
    const response = await fetch('http://localhost/VueGestorFree/GestorFree/php/eliminar_tarea.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id_tarea })
    });

    if (!response.ok) {
      const errorData = await response.json();
      console.error('Error del servidor:', errorData.message);
      return;
    }

    const result = await response.json();

    if (result.status === 'success') {
      tareas.value = tareas.value.filter(tarea => tarea.id_tarea !== id_tarea);
      console.log('Tarea eliminada exitosamente');
    } else {
      console.error('Error al eliminar la tarea:', result.message);
    }
  } catch (error) {
    console.error('Error al conectar con el servidor:', error);
  }
};

const abrirFormularioModificar = (tarea: Task) => {
  tareaSeleccionada.value = { ...tarea };
};

const cerrarFormularioModificar = () => {
  tareaSeleccionada.value = null;
};

const modificarTarea = async () => {
  if (!tareaSeleccionada.value) return;

  console.log('Datos enviados al backend:', tareaSeleccionada.value);

  try {
    const response = await fetch('http://localhost/VueGestorFree/GestorFree/php/modificar_tarea.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(tareaSeleccionada.value)
    });

    const result = await response.json();

    if (result.status === 'success') {
      const index = tareas.value.findIndex(t => t.id_tarea === tareaSeleccionada.value?.id_tarea);
      if (index !== -1) {
        tareas.value[index] = { ...tareaSeleccionada.value };
      }
      cerrarFormularioModificar();
      console.log('Tarea modificada exitosamente');
    } else {
      console.error('Error al modificar la tarea:', result.message);
    }
  } catch (error) {
    console.error('Error al conectar con el servidor:', error);
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
          <button @click="eliminarTarea(tarea.id_tarea)" 
                  class="ml-auto px-3 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600">
            Eliminar
          </button>
          <!-- Botón para modificar -->
          <button @click="abrirFormularioModificar(tarea)" 
                  class="px-3 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">
            Modificar
          </button>
        </div>
      </div>
      
      <div v-if="tareas.length === 0" class="text-center text-gray-500 py-8">
        No hay tareas programadas
      </div>
    </div>

    <!-- Formulario para modificar tarea -->
    <div v-if="tareaSeleccionada" class="formulario-modificar bg-white p-4 rounded shadow-md">
      <h3 class="text-lg font-bold mb-4">Modificar Tarea</h3>
      <form @submit.prevent="modificarTarea">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Nombre</label>
          <input v-model="tareaSeleccionada.nombre_tarea" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Descripción</label>
          <textarea v-model="tareaSeleccionada.descripcion" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Fecha de Entrega</label>
          <input v-model="tareaSeleccionada.fecha_entrega" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Hora de Entrega</label>
          <input v-model="tareaSeleccionada.hora_entrega" type="time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Prioridad</label>
          <input v-model="tareaSeleccionada.prioridad" type="number" min="1" max="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Estado</label>
          <input v-model="tareaSeleccionada.id_estado" type="number" min="1" max="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="flex justify-end">
          <button type="submit" class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">Guardar</button>
          <button @click="cerrarFormularioModificar" type="button" class="ml-2 px-4 py-2 text-white bg-gray-500 rounded hover:bg-gray-600">Cancelar</button>
        </div>
      </form>
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
