<script setup>
    import { useRoute } from "vue-router";
    import AlumnosList from "../../components/AlumnosList.vue";

    

const route = useRoute();
const instructorId = route.params.id;
</script>
<template>
    <AlumnosList
    :endpoint="`http://127.0.0.1:8000/api/instructores/${instructorId}/alumnos`"
    title="Tus alumnos"
  >
      <template #actions="{ alumno }">
    <button class="btn btn-sm btn-primary me-2" @click="verDetalle(alumno)">
      Ver
    </button>
    <button class="btn btn-sm btn-danger" @click="expulsar(alumno)">
      Quitar
    </button>
  </template>
  </AlumnosList>
</template>