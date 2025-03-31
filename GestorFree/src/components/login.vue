<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const numeroControl = ref("");
const password = ref("");
const errorMessage = ref("");

const login = async () => {
  if (!numeroControl.value || !password.value) {
    errorMessage.value = "Todos los campos son obligatorios";
    return;
  }

  const datos = {
    numero_control: numeroControl.value,
    password: password.value
  };

  try {
    const respuesta = await fetch("http://localhost/VueGestorFree/Controllers/login.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(datos),
    });

    const resultado = await respuesta.json();
    
    if (resultado.success) {
      // Guardar en localStorage para mantener la sesión en Vue
      localStorage.setItem("numero_control", numeroControl.value);
      
      router.push("/Inicio");
    } else {
      errorMessage.value = resultado.message || "Credenciales incorrectas";
    }

  } catch (error) {
    errorMessage.value = "Error de conexión con el servidor";
  }
};
</script>

<template>
  <div class="login-wrapper">
    <div class="container">
      <img src="../assets/ito-logo.jpg" alt="Logo-ITO" class="logo">
      <h2>Iniciar Sesión</h2>

      <form @submit.prevent="login">
        <label for="numero_control">Número de Control:</label>
        <input type="text" id="numero_control" v-model="numeroControl" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" v-model="password" required>

        <button type="submit">Iniciar Sesión</button>
        <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
      </form>
    </div>
  </div>
</template>


<style scoped>
.login-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f5f5f5;
}

.container {
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  text-align: center;
  width: 100%;
  max-width: 400px;
  padding: 30px;
}

.logo {
  max-width: 150px;
  margin-bottom: 20px;
}

h2 {
  margin-bottom: 1.5rem;
  font-size: 1.5rem;
  color: #333;
}

form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

label {
  font-size: 0.9rem;
  text-align: left;
  margin-bottom: -10px;
}

input {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

button {
  background: #EFB036;
  color: white;
  padding: 12px;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s;
  margin-top: 10px;
}

button:hover {
  background: #e0a42e;
}

.error {
  color: red;
  font-size: 0.9rem;
  margin-top: 10px;
}
</style>
