<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()
const tutorId = userStore.user?.id

const entregas = ref([])
const mensaje = ref('')

async function fetchEntregas() {
  if (!tutorId) return

  try {
    // Obtener los grados que tutorea
    const gradosResponse = await axios.get(`http://localhost:8000/api/tutor/${tutorId}/grados`)
    const grados = gradosResponse.data

    entregas.value = []

    // Obtener entregas de cada grado
    for (const grado of grados) {
      const resResponse = await axios.get(`http://localhost:8000/api/grado/${grado.id}/entregas`)
      const res = resResponse.data

      // Mapear alumno_entrega a alumnoEntrega
      res.forEach(function (e) {
        e.alumnoEntrega = e.alumno_entrega || []
        e.alumnoEntrega.forEach(a => {
          if (!a.nota) a.nota = { Nota: 0 }
        })
      })

      entregas.value.push(...res)
    }
  } catch (err) {
    console.error(err)
    mensaje.value = 'Error cargando entregas'
  }
}

async function guardarNota(alumnoEntrega) {
  try {
    await axios.post('http://localhost:8000/api/nota-cuaderno', {
      ID_Cuaderno: alumnoEntrega.ID_Entrega,
      Nota: alumnoEntrega.nota?.Nota || 0,
      ID_Tutor: tutorId
    })
    alert('Nota guardada correctamente')
  } catch (err) {
    console.error(err)
    alert('Error al guardar la nota')
  }
}

onMounted(fetchEntregas)
</script>

<template>
  <div>
    <h3>Cuadernos de Alumnos</h3>
    <div v-if="mensaje" class="text-danger mb-3">{{ mensaje }}</div>

    <div v-if="entregas.length">
      <div v-for="entrega in entregas" :key="entrega.id" class="card mb-4 shadow-sm">

        <!-- HEADER -->
        <div class="card-header bg-indigo d-flex justify-content-between align-items-center">
          <div>
            <h5 class="text-white mb-1">
              {{ entrega.Descripcion }}
            </h5>
            <small class="text-white">
              Fecha límite: {{ entrega.Fecha_Limite }}
            </small>
          </div>

          <span class="badge" :class="entrega.alumnoEntrega.length ? 'bg-success' : 'bg-danger'">
            {{ entrega.alumnoEntrega.length
              ? `Entregas: ${entrega.alumnoEntrega.length}`
              : 'Sin entregas' }}
          </span>
        </div>

        <!-- DESCRIPCIÓN -->
        <div class="card-body pb-2">


          <!-- TABLA -->
          <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>Alumno</th>
                  <th>PDF</th>
                  <th class="text-center">Nota</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="alumnoEntrega in entrega.alumnoEntrega" :key="alumnoEntrega.id">
                  <td>
                    {{ alumnoEntrega.alumno?.usuario?.nombre ?? '—' }}
                  </td>

                  <td>
                    <a v-if="alumnoEntrega.URL_Cuaderno"
                      :href="`http://localhost:8000/api/alumno/entregas/descargar/${alumnoEntrega.id}`" target="_blank"
                      class="link-primary">
                      Descargar PDF
                    </a>
                    <span v-else class="text-danger">
                      No entregado
                    </span>
                  </td>

                  <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                      <input v-model.number="alumnoEntrega.nota.Nota" type="number" min="0" max="10"
                        class="form-control form-control-sm w-50" />
                      <button @click="guardarNota(alumnoEntrega)" class="btn btn-outline-secondary btn-sm">
                        Guardar
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- SIN ENTREGAS -->
                <tr v-if="entrega.alumnoEntrega.length === 0">
                  <td colspan="3" class="text-center text-muted py-3">
                    Ningún alumno ha entregado este cuaderno
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>

    <div v-else class="text-center text-muted mt-3">
      No hay entregas asignadas para tus grados.
    </div>
  </div>
</template>
