<script setup>
import { ref, onMounted } from "vue";

const equipos = ref([]);
const mensaje = ref("");
const mensajeTipo = ref(""); // Agregado para manejar el tipo de mensaje (success o error)
const codigoEquipo = ref("");
const nombreEquipo = ref("");
const descripcion = ref("");
const numeroControl = ref("");

// Obtener equipos
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

// Insertar equipo
const crearEquipo = async (equipo) => {
  console.log("Equipo a insertar:", equipo); // Para verificar el objeto que se está enviando
  try {
    const respuesta = await fetch("http://localhost/vueGestorFree/GestorFree/php/teams-table-insert.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(equipo),
    });

    if (respuesta.ok) {
      const resultado = await respuesta.json();
      console.log("Respuesta del servidor:", resultado); // Ver la respuesta del servidor
      mensaje.value = resultado.mensaje || "Equipo creado exitosamente.";
      mensajeTipo.value = "success";
      obtenerEquipos();  // Recargar la lista después de crear
    } else {
      const errorData = await respuesta.json();
      console.error("Error en la creación del equipo:", errorData); // Ver el error si existe
      mensaje.value = errorData.error || "Error al crear equipo.";
      mensajeTipo.value = "error";
    }
  } catch (error) {
    console.error("Error al crear equipo:", error);
    mensaje.value = "❌ Error de conexión con el servidor.";
    mensajeTipo.value = "error";
  }
};

// Limpiar formulario
const limpiarFormulario = () => {
  codigoEquipo.value = "";
  nombreEquipo.value = "";
  descripcion.value = "";
  numeroControl.value = "";
};

// Ejecutar al montar el componente
onMounted(() => {
  obtenerEquipos();
});
</script>

<template>
  <div class="container">
    <h1>Gestión de Equipos</h1>

    <!-- Formulario para insertar equipo -->
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

    <!-- Mensaje de éxito o error -->
    <p v-if="mensaje" :class="mensajeTipo" class="mensaje">{{ mensaje }}</p>

    <!-- Tabla para mostrar equipos -->
    <h2>Equipos Registrados</h2>
    <table v-if="equipos.length > 0" class="teams-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Líder</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="equipo in equipos" :key="equipo.codigo_equipo">
          <td>{{ equipo.codigo_equipo }}</td>
          <td>{{ equipo.nombre_equipo }}</td>
          <td>{{ equipo.descripcion }}</td>
          <td>{{ equipo.numero_control }}</td>
        </tr>
      </tbody>
    </table>

    <p v-else>No hay equipos registrados.</p>
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

.teams-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background: white;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.teams-table th, .teams-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.teams-table th {
  background: #007bff;
  color: white;
}

.teams-table tr:hover {
  background: #f1f1f1;
}
</style>
