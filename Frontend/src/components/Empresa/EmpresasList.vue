<template>
    <div class="col-md-3 mt-3">
        <button class="btn btn-secondary mb-2" @click="mostrarModal = true">
            <i class="bi bi-building-fill-add"></i> Añadir
        </button>

        <EmpresaForm :show="mostrarModal" :errorMessage="errores" @close="mostrarModal = false" @crear="crearEmpresa" />

        <ul v-if="empresas.length" class="list-group">
            <button type="button" class="list-group-item list-group-item-action text-white bg-indigo"
                @click="emit('seleccionarEmpresa', null)">
                <h3>Empresas</h3>
            </button>

            <button v-for="empresa in empresas" :key="empresa.id" type="button"
                class="list-group-item list-group-item-action" @click="emit('seleccionarEmpresa', empresa)">
                {{ empresa.Nombre }}
            </button>
        </ul>

        <p v-else>
            Cargando empresas
        <ul class="carga">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        </p>
    </div>
</template>


<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'
import EmpresaForm from './EmpresaForm.vue'

const empresas = ref([])
const mostrarModal = ref(false)
const errores = ref(null)

const emit = defineEmits(['seleccionarEmpresa'])

const cargarEmpresas = async () => {
  const response = await axios.get('http://localhost:8000/api/empresas')
  empresas.value = response.data
}

onMounted(cargarEmpresas)

const crearEmpresa = async (empresa) => {
  try {
    const response = await axios.post('http://localhost:8000/api/empresa/create', {
        ...empresa
    })
    console.log(response.data);
    
    mostrarModal.value = false
    errores.value = null
    cargarEmpresas()
  } catch (e) {
    if (e.response?.status === 422) {
      errores.value = e.response.data.errors
    }
  }
}
</script>

