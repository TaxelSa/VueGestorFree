<script setup>
import { ref, onMounted } from "vue";

const equipos = ref([]);
const integrantes = ref([]);
const mensaje = ref("");
const mensajeTipo = ref("");

const obtenerEquipos = async () => {
  try {
    const respuesta = await fetch("http://localhost/GestorFree/app/controllers/teams-table.php");
    const data = await respuesta.json();
    equipos.value = data;
  } catch (error) {
    console.error("Error al obtener equipos:", error);
    mensaje.value = "Error al cargar los equipos.";
    mensajeTipo.value = "error";
  }
};

const obtenerIntegrantes = async (codigoEquipo) => {
  try {
    const respuesta = await fetch(`http://localhost/GestorFree/app/controllers/teams-integrantes.php?codigo=${codigoEquipo}`);
    const data = await respuesta.json();
    integrantes.value = data;
  } catch (error) {
    console.error("Error al obtener integrantes:", error);
    mensaje.value = "Error al cargar los integrantes.";
    mensajeTipo.value = "error";
  }
};

const crearEquipo = async (equipo) => {
  try {
    const respuesta = await fetch("http://localhost/GestorFree/app/controllers/teams-table-insert.php", {
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
    console.error("Error al crear equipo:", error);
  }
};

const unirseEquipo = async (codigo, numeroControl) => {
  try {
    const respuesta = await fetch("http://localhost/GestorFree/app/controllers/teams-table-unirseT.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ codigo_equipo: codigo, numero_control: numeroControl }),
    });

    if (respuesta.ok) {
      mensaje.value = "Te has unido al equipo exitosamente.";
      mensajeTipo.value = "success";
      obtenerEquipos();
    } else {
      mensaje.value = "Error al unirse al equipo.";
      mensajeTipo.value = "error";
    }
  } catch (error) {
    console.error("Error al unirse al equipo:", error);
  }
};

onMounted(() => {
  obtenerEquipos();
});
</script>

<template>
  <div class="container">
    <header>
      <h1>Gestión de Equipos</h1>
      <button @click="obtenerEquipos">🔄 Actualizar</button>
    </header>

    <div v-if="mensaje" :class="['message', mensajeTipo]">
      {{ mensaje }}
    </div>

    <!-- Formulario para crear equipo -->
    <div class="formulario">
      <h2>Crear un Equipo</h2>
      <form @submit.prevent="crearEquipo({
        codigo_equipo: $event.target.codigo_equipo.value,
        nombre_equipo: $event.target.nombre_equipo.value,
        descripcion: $event.target.descripcion.value,
        numero_control: $event.target.numero_control.value
      })">
        <input type="text" name="codigo_equipo" placeholder="Código del equipo" required />
        <input type="text" name="nombre_equipo" placeholder="Nombre del equipo" required />
        <input type="text" name="descripcion" placeholder="Descripción" required />
        <input type="text" name="numero_control" placeholder="Número de control" required />
        <button type="submit">Crear</button>
      </form>
    </div>

    <!-- Tabla de equipos -->
    <table class="teams-table">
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
            <button @click="obtenerIntegrantes(equipo.codigo_equipo)">👥 Ver Integrantes</button>
            <button @click="unirseEquipo(equipo.codigo_equipo, '123456')">➕ Unirse</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal de integrantes -->
    <div v-if="integrantes.length > 0" class="modal">
      <div class="modal-content">
        <h2>Integrantes del equipo</h2>
        <ul>
          <li v-for="integrante in integrantes" :key="integrante.numero_control">
            {{ integrante.nombre }} {{ integrante.apellido }} ({{ integrante.numero_control }})
          </li>
        </ul>
        <button @click="integrantes = []">Cerrar</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  padding: 20px;
  font-family: Arial, sans-serif;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.message {
  padding: 10px;
  margin: 10px 0;
  border-radius: 5px;
  color: white;
}

.success {
  background-color: #28a745;
}

.error {
  background-color: #dc3545;
}

.teams-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.teams-table th, .teams-table td {
  padding: 10px;
  border: 1px solid #ccc;
}

.teams-table th {
  background-color: #f4f4f4;
}

button {
  padding: 8px 16px;
  margin: 5px;
  border: none;
  cursor: pointer;
  color: white;
  background-color: #007bff;
  transition: 0.3s;
}

button:hover {
  background-color: #0056b3;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

input, button {
  padding: 10px;
  font-size: 14px;
}
</style>
