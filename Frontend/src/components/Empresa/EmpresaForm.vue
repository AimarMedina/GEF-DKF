<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  show: Boolean,
  errorMessage: Object
})

const emit = defineEmits(['close', 'crear'])

const nuevaEmpresa = ref({
  Nombre: '',
  Direccion: '',
  CIF: '',
  Email: '',
  N_Tel: ''
})

watch(() => props.show, (val) => {
  if (!val) resetFormulario()
})

function crear() {
  emit('crear', { ...nuevaEmpresa.value })
}

function resetFormulario() {
  nuevaEmpresa.value = {
    Nombre: '',
    Direccion: '',
    CIF: '',
    Email: '',
    N_Tel: ''
  }
}
</script>

<template>
  <div v-if="show" class="modal fade show d-block">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Nueva empresa</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>

        <div class="modal-body">
          <div class="mb-2">
            <label>Nombre</label>
            <input v-model="nuevaEmpresa.Nombre" type="text" class="form-control">
          </div>
          <div class="mb-2">
            <label>Dirección</label>
            <input v-model="nuevaEmpresa.Direccion" type="text" class="form-control">
          </div>
          <div class="mb-2">
            <label>CIF</label>
            <input v-model="nuevaEmpresa.CIF" type="text" class="form-control">
          </div>
          <div class="mb-2">
            <label>Teléfono</label>
            <input v-model="nuevaEmpresa.N_Tel" type="text" class="form-control">
          </div>
          <div class="mb-2">
            <label>Email</label>
            <input v-model="nuevaEmpresa.Email" type="email" class="form-control">
          </div>

          <div v-if="errorMessage" class="alert alert-danger text-start">
            <span v-for="messages in errorMessage" :key="messages">
              <span v-for="msg in messages" :key="msg">{{ msg }}</span><br>
            </span>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
          <button class="btn btn-indigo" @click="crear">Guardar</button>
        </div>

      </div>
    </div>
  </div>

  <div v-if="show" class="modal-backdrop fade show"></div>
</template>
