
<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';

const nuevaTarea = ref({
  nombre_tarea: '',
  descripcion: '',
  fecha_entrega: '',
  hora_entrega: '',
  prioridad: 1,
  id_usuario: '21011063', // Valor por defecto
  id_estado: 1, // Por defecto: To Do
  id_materia: 1,
  id_proyecto: 22
});

const mensaje = ref('');
const mostrarMensaje = ref(false);

const crearTarea = async () => {
  try {
    const response = await axios.post('http://localhost/VueGestorFree/GestorFree/php/crear_tarea.php', nuevaTarea.value);
    
    if (response.data.status === 'success') {
      mensaje.value = '¡Tarea creada exitosamente!';
      // Limpiar el formulario
      nuevaTarea.value = {
        nombre_tarea: '',
        descripcion: '',
        fecha_entrega: '',
        hora_entrega: '',
        prioridad: 1,
        id_usuario: '21011063',
        id_estado: 1,
        id_materia: 1,
        id_proyecto: 22
      };
      // Emitir evento para actualizar el Kanban
      emit('tareaCreada');
    } else {
      mensaje.value = 'Error al crear la tarea: ' + response.data.message;
    }
    
    mostrarMensaje.value = true;
    setTimeout(() => {
      mostrarMensaje.value = false;
    }, 3000);
    
  } catch (error) {
    console.error('Error:', error);
    mensaje.value = 'Error al crear la tarea';
    mostrarMensaje.value = true;
  }
};

const emit = defineEmits(['tareaCreada']);
</script>

<template>
  <div class="crear-tarea">
    <h2>Crear Nueva Tarea</h2>
    
    <div v-if="mostrarMensaje" class="mensaje" :class="{ 'error': mensaje.includes('Error') }">
      {{ mensaje }}
    </div>
    
    <form @submit.prevent="crearTarea" class="formulario">
      <div class="form-group">
        <label for="nombre">Nombre de la tarea:</label>
        <input 
          id="nombre"
          v-model="nuevaTarea.nombre_tarea"
          type="text"
          required
          placeholder="Ingrese el nombre de la tarea"
        >
      </div>
      
      <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea
          id="descripcion"
          v-model="nuevaTarea.descripcion"
          required
          placeholder="Descripción de la tarea"
        ></textarea>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label for="fecha">Fecha de entrega:</label>
          <input
            id="fecha"
            v-model="nuevaTarea.fecha_entrega"
            type="date"
            required
          >
        </div>
        
        <div class="form-group">
          <label for="hora">Hora de entrega:</label>
          <input
            id="hora"
            v-model="nuevaTarea.hora_entrega"
            type="time"
            required
          >
        </div>
      </div>
      
      <div class="form-group">
        <label for="prioridad">Prioridad:</label>
        <select id="prioridad" v-model="nuevaTarea.prioridad">
          <option value="1">Baja</option>
          <option value="2">Media</option>
          <option value="3">Alta</option>
        </select>
      </div>
      
      <button type="submit" class="btn-crear">Crear Tarea</button>
    </form>
  </div>
</template>

<style scoped>
.crear-tarea {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
  color: #172b4d;
  margin-bottom: 20px;
  text-align: center;
}

.formulario {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.form-row {
  display: flex;
  gap: 15px;
}

.form-row .form-group {
  flex: 1;
}

label {
  font-weight: 500;
  color: #172b4d;
}

input, textarea, select {
  padding: 8px 12px;
  border: 1px solid #dfe1e6;
  border-radius: 4px;
  font-size: 14px;
}

textarea {
  min-height: 100px;
  resize: vertical;
}

.btn-crear {
  background: #0052cc;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  margin-top: 10px;
}

.btn-crear:hover {
  background: #0047b3;
}

.mensaje {
  padding: 10px;
  border-radius: 4px;
  margin-bottom: 15px;
  background: #e3fcef;
  color: #006644;
}

.mensaje.error {
  background: #ffebe6;
  color: #bf2600;
}
</style>