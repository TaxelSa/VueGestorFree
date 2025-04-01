<template>
  <div class="container">
    <h1>Gestión de Equipos</h1>

    <div class="formulario">
      <h2>Crear Nuevo Equipo</h2>
      <input v-model="codigoEquipo" placeholder="Código del equipo" />
      <input v-model="nombreEquipo" placeholder="Nombre del equipo" />
      <input v-model="descripcion" placeholder="Descripción" />
      <input v-model="numeroControl" placeholder="Número de control (líder)" />
      <button @click="crearEquipo({
        codigo_equipo: codigoEquipo,
        nombre_equipo: nombreEquipo,
        descripcion: descripcion,
        numero_control: numeroControl
      })">Agregar Equipo</button>
    </div>

    <p v-if="mensaje" :class="mensajeTipo" class="mensaje">{{ mensaje }}</p>

    <h2>Equipos Registrados</h2>
    <table v-if="equipos.length > 0" class="teams-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Líder</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="equipo in equipos" :key="equipo.codigo_equipo">
          <td>{{ equipo.codigo_equipo }}</td>
          <td>{{ equipo.nombre_equipo }}</td>
          <td>{{ equipo.descripcion }}</td>
          <td>{{ equipo.numero_control }}</td>
          <td>
            <button @click="eliminarEquipo(equipo.codigo_equipo)" class="btn-eliminar">Eliminar</button>
            <button @click="abrirModal(equipo)" class="btn-editar">Editar</button>
          </td>
        </tr>
      </tbody>
    </table>
    
    <p v-else>No hay equipos registrados.</p>

    <!-- Modal de edición -->
    <div v-if="modalVisible" class="modal-overlay">
      <div class="modal">
        <h2>Editar Equipo</h2>
        <input v-model="equipoEditado.nombre_equipo" placeholder="Nombre del equipo" />
        <input v-model="equipoEditado.descripcion" placeholder="Descripción" />
        <input v-model="equipoEditado.numero_control" placeholder="Número de control (líder)" />
        <div class="modal-buttons">
          <button @click="guardarEdicion" class="btn-guardar">Guardar</button>
          <button @click="cerrarModal" class="btn-cancelar">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const equipos = ref([]);
const mensaje = ref("");
const mensajeTipo = ref("");
const codigoEquipo = ref("");
const nombreEquipo = ref("");
const descripcion = ref("");
const numeroControl = ref("");
const modalVisible = ref(false);
const equipoEditado = ref({});

const obtenerEquipos = async () => {
  try {
    const respuesta = await fetch("http://localhost/vueGestorFree/GestorFree/php/teams-table.php");
    if (!respuesta.ok) throw new Error("Error al obtener equipos.");

    const datos = await respuesta.json();
    equipos.value = Array.isArray(datos) ? datos : [];
  } catch (error) {
    mensaje.value = "❌ Error al obtener los equipos.";
  }
};

const crearEquipo = async (equipo) => {
  try {
    const respuesta = await fetch("http://localhost/vueGestorFree/GestorFree/php/teams-table-insert.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(equipo),
    });

    if (respuesta.ok) {
      mensaje.value = "Equipo creado exitosamente.";
      mensajeTipo.value = "success";
      obtenerEquipos();
    } else {
      mensaje.value = "Error al crear equipo.";
      mensajeTipo.value = "error";
    }
  } catch (error) {
    mensaje.value = "❌ Error de conexión con el servidor.";
    mensajeTipo.value = "error";
  }
};

const eliminarEquipo = async (codigo) => {
  try {
    const respuesta = await fetch(`http://localhost/vueGestorFree/GestorFree/php/teams-table-delete.php?codigo_equipo=${codigo}`, { method: "DELETE" });
    if (respuesta.ok) {
      mensaje.value = "Equipo eliminado exitosamente.";
      mensajeTipo.value = "success";
      obtenerEquipos();
    } else {
      mensaje.value = "Error al eliminar el equipo.";
      mensajeTipo.value = "error";
    }
  } catch (error) {
    mensaje.value = "Error de conexión con el servidor.";
    mensajeTipo.value = "error";
  }
};

const abrirModal = (equipo) => {
  equipoEditado.value = { ...equipo };
  modalVisible.value = true;
};

const cerrarModal = () => {
  modalVisible.value = false;
};

const guardarEdicion = async () => {
  try {
    const respuesta = await fetch("http://localhost/vueGestorFree/GestorFree/php/teams-table-update.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(equipoEditado.value),
    });
    if (respuesta.ok) {
      mensaje.value = "Equipo actualizado correctamente.";
      mensajeTipo.value = "success";
      obtenerEquipos();
      cerrarModal();
    } else {
      mensaje.value = "Error al actualizar equipo.";
      mensajeTipo.value = "error";
    }
  } catch (error) {
    mensaje.value = "❌ Error de conexión con el servidor.";
    mensajeTipo.value = "error";
  }
};

onMounted(() => {
  obtenerEquipos();
});
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
  text-align: center;
}

.formulario, .teams-table {
  border: 1px solid #ddd;
  padding: 20px;
  border-radius: 10px;
  background: #f9f9f9;
  margin-bottom: 20px;
}

input {
  display: block;
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 5px;
}

button {
  background: #007bff;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.teams-table {
  width: 100%;
  border-collapse: collapse;
}

.teams-table th, .teams-table td {
  border: 1px solid #ddd;
  padding: 10px;
}

.teams-table th {
  background: #007bff;
  color: white;
}
</style>