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

// Bandera para saber si se está actualizando un proyecto
const editando = ref(false);

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

// Insertar o actualizar proyecto
const guardarProyecto = async () => {
  const proyecto = {
    id_proyecto: idProyecto.value,
    nombre_proyecto: nombreProyecto.value,
    descripcion: descripcion.value,
    fecha_entrega: fechaEntrega.value,
    id_usuario: idUsuario.value,
    id_estado: idEstado.value,
    id_materia: idMateria.value,
    id_equipo: idEquipo.value,
  };

  const url = editando.value
    ? "http://localhost/vueGestorFree/GestorFree/php/teams-table-update-proyecto.php"
    : "http://localhost/vueGestorFree/GestorFree/php/teams-table-insert-proyectos.php";

  const metodo = editando.value ? "PUT" : "POST";

  try {
    const respuesta = await fetch(url, {
      method: metodo,
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(proyecto),
    });

    if (respuesta.ok) {
      const resultado = await respuesta.json();
      mensaje.value = resultado.mensaje || (editando.value ? "✅ Proyecto actualizado." : "✅ Proyecto creado.");
      mensajeTipo.value = "success";
      limpiarFormulario();
      obtenerProyectos();
    } else {
      const errorData = await respuesta.json();
      mensaje.value = errorData.error || "❌ Error al procesar la solicitud.";
      mensajeTipo.value = "error";
    }
  } catch (error) {
    console.error("Error en la solicitud:", error);
    mensaje.value = "❌ Error de conexión con el servidor.";
    mensajeTipo.value = "error";
  }
};

// Eliminar proyecto
const eliminarProyecto = async (id) => {
  if (!confirm("¿Estás seguro de eliminar este proyecto?")) return;

  try {
    const respuesta = await fetch("http://localhost/vueGestorFree/GestorFree/php/teams-table-delete-proyecto.php", {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_proyecto: id }),
    });

    if (respuesta.ok) {
      const resultado = await respuesta.json();
      mensaje.value = resultado.mensaje || "✅ Proyecto eliminado.";
      mensajeTipo.value = "success";
      obtenerProyectos();
    } else {
      const errorData = await respuesta.json();
      mensaje.value = errorData.error || "❌ Error al eliminar.";
      mensajeTipo.value = "error";
    }
  } catch (error) {
    console.error("Error al eliminar:", error);
    mensaje.value = "❌ Error de conexión con el servidor.";
    mensajeTipo.value = "error";
  }
};

// Cargar datos en el formulario para editar
const editarProyecto = (proyecto) => {
  idProyecto.value = proyecto.id_proyecto;
  nombreProyecto.value = proyecto.nombre_proyecto;
  descripcion.value = proyecto.descripcion;
  fechaEntrega.value = proyecto.fecha_entrega;
  idUsuario.value = proyecto.id_usuario;
  idEstado.value = proyecto.id_estado;
  idMateria.value = proyecto.id_materia;
  idEquipo.value = proyecto.id_equipo;
  editando.value = true;
};

// Limpiar formulario
const limpiarFormulario = () => {
  idProyecto.value = "";
  nombreProyecto.value = "";
  descripcion.value = "";
  fechaEntrega.value = "";
  idUsuario.value = "";
  idEstado.value = "";
  idMateria.value = "";
  idEquipo.value = "";
  editando.value = false;
};

onMounted(() => {
  obtenerProyectos();
});
</script>

<template>
  <div class="container">
    <h1>Gestión de Proyectos</h1>
    
    <div class="formulario">
      <h2>{{ editando ? "Editar Proyecto" : "Crear Nuevo Proyecto" }}</h2>
      <input v-model="idProyecto" placeholder="ID del Proyecto" required :disabled="editando" />
      <input v-model="nombreProyecto" placeholder="Nombre del Proyecto" required />
      <input v-model="descripcion" placeholder="Descripción" required />
      <input v-model="fechaEntrega" type="date" required />
      <input v-model="idUsuario" placeholder="ID Usuario" required />
      <input v-model="idEstado" placeholder="ID Estado" required />
      <input v-model="idMateria" placeholder="ID Materia" required />
      <input v-model="idEquipo" placeholder="ID Equipo" required />
      <button @click="guardarProyecto">{{ editando ? "Actualizar Proyecto" : "Agregar Proyecto" }}</button>
      <button v-if="editando" @click="limpiarFormulario" class="cancel">Cancelar</button>
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
          <th>Operaciones</th>
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
          <td>
            <button @click="editarProyecto(proyecto)" class="edit">Modificar</button>
            <button @click="eliminarProyecto(proyecto.id_proyecto)" class="delete">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
    <p v-else>No hay proyectos registrados.</p>
  </div>
</template>

<style scoped>
button {
  margin-right: 5px;
}

button.edit {
  background: #ffc107;
  color: black;
}

button.delete {
  background: #dc3545;
}

button.cancel {
  background: #6c757d;
}

button:hover {
  opacity: 0.8;
}

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
