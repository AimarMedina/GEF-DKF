<template>
  <form class="d-flex flex-column justify-content-center align-items-center vh-100" @submit.prevent="login">
    <h3>GEF-DKA</h3>
    <div class="p-4 border rounded shadow">
      <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-9">
          <input type="email" class="form-control" id="inputEmail3" v-model="email">
        </div>
      </div>

      <div class="row mb-3">
        <label for="inputPassword3" class="col-sm-3 col-form-label">Contraseña</label>
        <div class="col-sm-9">
          <input type="password" class="form-control" id="inputPassword3" v-model="password">
          <div v-if="errorMessage" class="alert alert-danger mt-2" role="alert">
            {{ errorMessage }}
          </div>
        </div>
      </div>

      <input type="submit" class="btn btn-primary w-100"/>
    </div>
  </form>
</template>


<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();

const login = async () => {
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/login', {
      email: email.value,
      password: password.value
    });

    if (response.data.status === 'success') {
      errorMessage.value = '';
      let token = response.data.token
      localStorage.setItem('token', token);

      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      router.push('/home'); // redirige a vista protegida
    } else {
      errorMessage.value = response.data.message;
    }
  } catch (error) {
    console.error(error);
    errorMessage.value = 'Ocurrió un error al iniciar sesión';
  }
};

</script>

<style scoped></style>
