<script setup lang="ts">
import { ref } from "vue";
import { useDragAndDrop } from "@formkit/drag-and-drop/vue";

// Listas de tareas
const todoItems = ref([
  "Schedule perm",
  "Rewind VHS tapes",
  "Make change for the arcade",
  "Get disposable camera developed",
  "Learn C++",
  "Return Nintendo Power Glove",
]);
const doneItems = ref(["Pickup new mix-tape from Beth"]);

// Drag and Drop
const [todoList, todos] = useDragAndDrop(todoItems, { group: "kanban" });
const [doneList, dones] = useDragAndDrop(doneItems, { group: "kanban" });
</script>

<template>
  <div class="kanban-board">
    <!-- Lista de tareas pendientes -->
    <ul ref="todoList" class="kanban-column">
      <li v-for="todo in todos" :key="todo" class="kanban-item">
        {{ todo }}
      </li>
    </ul>

    <!-- Lista de tareas completadas -->
    <ul ref="doneList" class="kanban-column">
      <li v-for="done in dones" :key="done" class="kanban-item">
        {{ done }}
      </li>
    </ul>
  </div>
</template>

<style scoped>
.kanban-board {
  display: flex;
  gap: 20px;
  padding: 20px;
}

.kanban-column {
  width: 250px;
  min-height: 300px;
  background: #f4f4f4;
  padding: 10px;
  border-radius: 8px;
  list-style: none;
}

.kanban-item {
  background: white;
  padding: 12px;
  margin-bottom: 8px;
  border-radius: 5px;
  cursor: grab;
  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease-in-out;
}

.kanban-item:active {
  transform: scale(1.05);
}
</style>
