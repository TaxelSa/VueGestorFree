<script>
  export default {
    data() {
      return {
        numeroControl: '',
        password: '',
        errorMessage: ''
      };
    },
    methods: {
      async login() {
        try {
          const response = await fetch('http://localhost/Ejmascota/php/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
              numero_control: this.numeroControl,
              password: this.password
            })
          });
          
          const result = await response.json();
          if (result.success) {
            window.location.href = '/GestorFree/app/dashboard.php';
          } else {
            this.errorMessage = result.message || 'Error al iniciar sesión';
          }
        } catch (error) {
          this.errorMessage = 'Hubo un problema con la conexión';
        }
      }
    }
  };
  </script>

<template>
    <div class="container">

        <img src="../assets/ito-logo.jpg" alt="Logo-ITO" class="logo">

        <h2>Iniciar Sesión</h2>

            <form action="/GestorFree/app/controllers/login.php" method="POST">
                <label for="numero_control">Número de Control:</label>
                <input type="text" name="numero_control" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" name="password" required>
                
                <button type="submit">Iniciar Sesión</button>
            </form>
    </div>
   
    <main class="content">
        
      <router-view to="App"></router-view>
    </main>
</template>
  
<style scoped>
     body {
        font-family: Arial, sans-serif;
        height: 100vh;
        margin: 0;
        display: block;
        justify-content: center; /* Centra horizontalmente */
        align-items: center; /* Centra verticalmente */
        background-color: #f5f5f5;
        }

        .container {
            background: white;          
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
            height: 500px;
            padding: 20px;
        }

        h2 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 0.9rem;
            margin-bottom: 5px;
            text-align: left;
        }
        input {
            padding: 8px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        button {
            background: #EFB036;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
</style>