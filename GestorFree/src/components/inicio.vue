<script setup>
import { ref, onMounted } from "vue";

const proyectos = ref([]);
const mensaje = ref("");
const mensajeTipo = ref("");

// Datos del formulario
const idProyecto = ref("");
const nombreProyecto = ref("");
const descripcion = ref("");
const fechaEntrega = ref("");
const idUsuario = ref("");
const idEstado = ref("");
const idMateria = ref("");
const idEquipo = ref("");

// Obtener proyectos
const obtenerProyectos = async () => {
  try {
    const respuesta = await fetch("http://localhost/vueGestorFree/GestorFree/php/teams-table-proyectos.php");
    if (!respuesta.ok) throw new Error("Error al obtener proyectos.");
    
    const datos = await respuesta.json();
    proyectos.value = Array.isArray(datos) ? datos : [];
  } catch (error) {
    mensaje.value = "❌ Error al obtener los proyectos.";
    mensajeTipo.value = "error";
  }
};

// Insertar proyecto
const crearProyecto = async () => {
  const proyecto = {
    id_proyecto: id_proyecto.value,
    nombre_proyecto: nombre_proyecto.value,
    descripcion: descripcion.value,
    fecha_entrega: fecha_entrega.value,
    id_usuario: id_usuario.value,
    id_estado: id_estado.value,
    id_materia: id_materia.value,
    id_equipo: id_equipo.value
  };

  try {
    const respuesta = await fetch("http://localhost/vueGestorFree/GestorFree/php/teams-table-insert-proyectos.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(proyecto),
    });

    if (respuesta.ok) {
      mensaje.value = "✅ Proyecto creado exitosamente.";
      mensajeTipo.value = "success";
      obtenerProyectos();
    } else {
      mensaje.value = "❌ Error al crear el proyecto.";
      mensajeTipo.value = "error";
    }
  } catch (error) {
    mensaje.value = "❌ Error de conexión con el servidor.";
    mensajeTipo.value = "error";
  }
};

onMounted(() => {
  obtenerProyectos();
});
</script>

<template>
  <div class="container">
    <h1>Gestión de Proyectos</h1>
    
    <div class="formulario">
      <h2>Crear Nuevo Proyecto</h2>
      <input v-model="idProyecto" placeholder="ID del Proyecto" required />
      <input v-model="nombreProyecto" placeholder="Nombre del Proyecto" required />
      <input v-model="descripcion" placeholder="Descripción" required />
      <input v-model="fechaEntrega" type="date" required />
      <input v-model="idUsuario" placeholder="ID Usuario" required />
      <input v-model="idEstado" placeholder="ID Estado" required />
      <input v-model="idMateria" placeholder="ID Materia" required />
      <input v-model="idEquipo" placeholder="ID Equipo" required />
      <button @click="crearProyecto">Agregar Proyecto</button>
    </div>

    <p v-if="mensaje" :class="mensajeTipo" class="mensaje">{{ mensaje }}</p>

    <h2>Proyectos Registrados</h2>
    <table v-if="proyectos.length > 0" class="projects-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Fecha de entrega</th>
          <th>Estado</th>
          <th>Estudiante Propietario</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="proyecto in proyectos" :key="proyecto.id_proyecto">
          <td>{{ proyecto.id_proyecto }}</td>
          <td>{{ proyecto.nombre_proyecto }}</td>
          <td>{{ proyecto.descripcion }}</td>
          <td>{{ proyecto.fecha_entrega }}</td>
          <td>{{ proyecto.id_estado }}</td>
          <td>{{ proyecto.id_usuario }}</td>
        </tr>
      </tbody>
    </table>
    <p v-else>No hay proyectos registrados.</p>
  </div>
</template>

<style scoped>
.container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 30px;
  font-family: Arial, sans-serif;
}

h1, h2 {
  text-align: center;
  color: #333;
}

.formulario {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-bottom: 30px;
}

input, button {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

button {
  background: #007bff;
  color: white;
  cursor: pointer;
  transition: background 0.3s;
}

button:hover {
  background: #0056b3;
}

.mensaje {
  color: green;
  font-weight: bold;
  text-align: center;
}

.mensaje.error {
  color: red;
}

.projects-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background: white;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.projects-table th, .projects-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.projects-table th {
  background: #007bff;
  color: white;
}

.projects-table tr:hover {
  background: #f1f1f1;
}
</style>
