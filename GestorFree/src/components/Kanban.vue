<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useDragAndDrop } from "@formkit/drag-and-drop/vue";

interface Task {
  id_tarea: number;
  nombre_tarea: string;
  fecha_entrega: string;
  hora_entrega: string;
  descripcion: string;
  prioridad: number;
  id_usuario: string;
  id_estado: number;
  id_materia: number;
  id_proyecto: number;
}

// Listas de tareas
const todoItems = ref<Task[]>([]);
const inProgressItems = ref<Task[]>([]);
const doneItems = ref<Task[]>([]);

// Cargar tareas desde la base de datos
const fetchTasks = async () => {
  try {
    console.log('Fetching tasks...');
    const response = await fetch('http://localhost/VueGestorFree/GestorFree/php/tareas.php');
    const data = await response.json();
    console.log('Response:', data);
    const tasks = data.data;
    
    if (!tasks || tasks.length === 0) {
      console.log('No tasks found in response');
      return;
    }
    
    // Clasificar tareas según su estado
    todoItems.value = tasks.filter(task => task.id_estado === 1);
    inProgressItems.value = tasks.filter(task => task.id_estado === 2);
    doneItems.value = tasks.filter(task => task.id_estado === 3);
    
    console.log('Tasks sorted:', {
      todo: todoItems.value,
      inProgress: inProgressItems.value,
      done: doneItems.value
    });
  } catch (error) {
    console.error('Error al cargar las tareas:', error);
  }
};

onMounted(() => {
  fetchTasks();
});

// Drag and Drop
const [todoList, todos] = useDragAndDrop(todoItems, { 
  group: "kanban",
  drop: async (items, info) => {
    // Aquí puedes actualizar el estado en la base de datos
    console.log(items, info);
  }
});

const [inProgressList, inProgress] = useDragAndDrop(inProgressItems, { 
  group: "kanban",
  drop: async (items, info) => {
    console.log(items, info);
  }
});

const [doneList, dones] = useDragAndDrop(doneItems, { 
  group: "kanban",
  drop: async (items, info) => {
    console.log(items, info);
  }
});
</script>

<template>
  <div class="kanban-board">
    <!-- To Do Column -->
    <div class="kanban-column">
      <h2 class="column-header">To Do</h2>
      <ul ref="todoList" class="task-list">
        <li v-for="todo in todos" :key="todo.id_tarea" class="kanban-item">
          <div class="task-header">{{ todo.nombre_tarea }}</div>
          <div class="task-details">
            <p>{{ todo.descripcion }}</p>
            <div class="task-meta">
              <span>Fecha: {{ todo.fecha_entrega }}</span>
              <span>Prioridad: {{ todo.prioridad }}</span>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- In Progress Column -->
    <div class="kanban-column">
      <h2 class="column-header">In Progress</h2>
      <ul ref="inProgressList" class="task-list">
        <li v-for="item in inProgress" :key="item.id_tarea" class="kanban-item">
          <div class="task-header">{{ item.nombre_tarea }}</div>
          <div class="task-details">
            <p>{{ item.descripcion }}</p>
            <div class="task-meta">
              <span>Fecha: {{ item.fecha_entrega }}</span>
              <span>Prioridad: {{ item.prioridad }}</span>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- Done Column -->
    <div class="kanban-column">
      <h2 class="column-header">Done</h2>
      <ul ref="doneList" class="task-list">
        <li v-for="done in dones" :key="done.id_tarea" class="kanban-item">
          <div class="task-header">{{ done.nombre_tarea }}</div>
          <div class="task-details">
            <p>{{ done.descripcion }}</p>
            <div class="task-meta">
              <span>Fecha: {{ done.fecha_entrega }}</span>
              <span>Prioridad: {{ done.prioridad }}</span>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>
.kanban-board {
  display: flex;
  gap: 20px;
  padding: 20px;
  background: #f0f2f5;
  min-height: 400px;
  overflow-x: auto;
}

.kanban-column {
  width: 280px;
  min-height: 300px;
  background: #ebecf0;
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
}

.column-header {
  padding: 12px 16px;
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: #172b4d;
  background: rgba(9, 30, 66, 0.04);
  border-radius: 8px 8px 0 0;
}

.task-list {
  flex-grow: 1;
  padding: 8px;
  margin: 0;
  list-style: none;
}

.kanban-item {
  background: white;
  border-radius: 6px;
  padding: 12px;
  margin-bottom: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  cursor: grab;
}

.kanban-item:hover {
  background: #fafbfc;
}

.task-header {
  font-weight: 600;
  margin-bottom: 8px;
}

.task-details p {
  font-size: 14px;
  color: #5e6c84;
  margin: 4px 0;
}

.task-meta {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  color: #6b778c;
  margin-top: 8px;
}
</style>