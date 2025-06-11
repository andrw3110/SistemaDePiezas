<script setup>
import { ref, reactive, watch } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps({
  registros: Array,
  usuarioNombre: String,
  bloques: Array,
  piezas: Array 
})

const showModal = ref(false)
const isEdit = ref(false)
const isLoading = ref(false)

const form = reactive({
  id_pieza: '',
  pieza: '',
  peso_teorico: '',
  peso_real: '',
  diferencia: '',
  estado: 'Pendiente',
  id_bloque: '',
  fecha_registro: '',
  registrado_por: ''
})

const errors = reactive({ peso_teorico: '' })

const piezasFiltradas = ref([])
watch(() => form.id_bloque, val => {
  piezasFiltradas.value = props.piezas.filter(
    p => p.id_bloque === val && p.estado === 'Pendiente'
  )
})

watch(() => form.id_pieza, val => {
  const sel = props.piezas.find(p => p.id_pieza === val)
  if (sel) {
    form.pieza = sel.pieza
    form.peso_teorico = sel.peso_teorico
    form.diferencia = ''
  } else {
    form.pieza = ''
    form.peso_teorico = ''
    form.diferencia = ''
  }
})

watch(() => form.peso_real, val => {
  const pt = parseFloat(form.peso_teorico) || 0
  const pr = parseFloat(val) || 0
  form.diferencia = (pr - pt).toFixed(2)
})

function openCreateModal() {
  isEdit.value = false
  Object.assign(form, {
    id_pieza: '',
    pieza: '',
    peso_teorico: '',
    peso_real: '',
    diferencia: '',
    estado: 'Pendiente',
    id_bloque: '',
    fecha_registro: new Date().toISOString().slice(0, 10),
    registrado_por: props.usuarioNombre
  })
  errors.peso_teorico = ''
  showModal.value = true
}

function openEditModal(registro) {
  isEdit.value = true
  Object.assign(form, {
    ...registro,
    diferencia: (registro.peso_real - registro.peso_teorico).toFixed(2)
  })
  showModal.value = true
}

function saveRegistro() {
  errors.peso_teorico = ''
  const pt = parseFloat(form.peso_teorico)
  if (isNaN(pt) || pt <= 0) {
    errors.peso_teorico = 'El peso teórico debe ser mayor que cero.'
    form.peso_teorico = ''
    return
  }

  isLoading.value = true
  const payload = { ...form }

  const finish = () => {
    isLoading.value = false
    showModal.value = false
    router.reload({ only: ['registros'] })
    isEdit.value = false
  }

  const errorFn = () => {
    alert('Ocurrió un error al guardar.')
    isLoading.value = false
  }

  if (isEdit.value && form.id_registro) {
    router.put(`/registros/${form.id_registro}`, payload, { onSuccess: finish, onError: errorFn })
  } else {
    router.post('/registros', payload, { onSuccess: finish, onError: errorFn })
  }
}

function deleteRegistro(id) {
  if (confirm('¿Eliminar este registro?')) {
    router.delete(`/registros/${id}`, {
      onSuccess: () => router.reload({ only: ['registros'] }),
      onError: () => alert('Error al eliminar.')
    })
  }
}
</script>

<template>
  <Head title="Registros" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-bold text-orange-600">Listado de Registros</h2>
    </template>

    <div class="py-6 max-w-7xl mx-auto px-6">
      <button @click="openCreateModal" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded shadow mb-4">
        Agregar nuevo
      </button>

      <table class="min-w-full bg-yellow-50 border border-orange-200 rounded-lg shadow">
        <thead>
          <tr class="bg-orange-100 text-left text-sm font-semibold text-orange-800">
            <th class="px-4 py-2">#</th>
            <th class="px-4 py-2">Pieza</th>
            <th class="px-4 py-2">Teórico</th>
            <th class="px-4 py-2">Real</th>
            <th class="px-4 py-2">Diferencia</th>
            <th class="px-4 py-2">Estado</th>
            <th class="px-4 py-2">Bloque</th>
            <th class="px-4 py-2">Fecha de registro</th>
            <th class="px-4 py-2">Registrado por</th>
            <th class="px-4 py-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(reg, i) in props.registros" :key="reg.id_registro" class="hover:bg-orange-50 transition">
            <td class="px-4 py-2 border border-orange-100">{{ i + 1 }}</td>
            <td class="px-4 py-2 border border-orange-100">{{ reg.pieza }}</td>
            <td class="px-4 py-2 border border-orange-100">{{ reg.peso_teorico }}</td>
            <td class="px-4 py-2 border border-orange-100">{{ reg.peso_real ?? '-' }}</td>
            <td class="px-4 py-2 border border-orange-100">
              {{ reg.peso_real !== null ? (reg.peso_real - reg.peso_teorico).toFixed(2) : '-' }}
            </td>
            <td class="px-4 py-2 border border-orange-100">{{ reg.estado }}</td>
            <td class="px-4 py-2 border border-orange-100">{{ reg.bloque.nombre_bloque }}</td>
            <td class="px-4 py-2 border border-orange-100">{{ reg.fecha_registro }}</td>
            <td class="px-4 py-2 border border-orange-100">{{ reg.registrado_por }}</td>
            <td class="px-4 py-2 border border-orange-100">
              <button @click="openEditModal(reg)" class="text-blue-600 hover:underline mr-2">Editar</button>
              <button @click="deleteRegistro(reg.id_registro)" class="text-red-600 hover:underline">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 overflow-y-auto max-h-[90vh] border border-orange-200">
        <h3 class="text-lg font-bold text-orange-600 mb-4">{{ isEdit ? 'Editar Registro' : 'Agregar Registro' }}</h3>

        <form @submit.prevent="saveRegistro" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Bloque</label>
            <select v-model="form.id_bloque" class="w-full border border-gray-300 bg-gray-50 rounded px-3 py-2">
              <option value="" disabled>Seleccione bloque</option>
              <option v-for="b in props.bloques" :key="b.id_bloque" :value="b.id_bloque">
                {{ b.nombre_bloque }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Pieza</label>
            <select v-model="form.id_pieza" class="w-full border border-gray-300 bg-gray-50 rounded px-3 py-2" :disabled="piezasFiltradas.length === 0">
              <option disabled value="">Seleccione una pieza</option>
              <option v-for="pieza in piezasFiltradas" :key="pieza.id_pieza" :value="pieza.id_pieza">
                {{ pieza.pieza }}
              </option>
            </select>
          </div>

          <div class="flex space-x-4">
            <div class="flex-1">
              <label class="block text-sm font-semibold text-gray-700">Peso Teórico</label>
              <input v-model="form.peso_teorico" disabled class="w-full bg-gray-100 rounded px-3 py-2" />
            </div>
            <div class="flex-1">
              <label class="block text-sm font-semibold text-gray-700">Peso Real</label>
              <input v-model="form.peso_real" type="number" step="0.01" class="w-full border border-gray-300 bg-white rounded px-3 py-2" />
            </div>
            <div class="flex-1">
              <label class="block text-sm font-semibold text-gray-700">Diferencia</label>
              <input v-model="form.diferencia" disabled class="w-full bg-gray-100 rounded px-3 py-2" />
            </div>
          </div>
          <p v-if="errors.peso_teorico" class="text-red-600 text-sm mt-1">{{ errors.peso_teorico }}</p>

          <div>
            <label class="block text-sm font-semibold text-gray-700">Estado</label>
            <select v-model="form.estado" class="w-full border border-gray-300 bg-white rounded px-3 py-2">
              <option value="Fabricado">Fabricado</option>
              <option value="Pendiente">Pendiente</option>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700">Fecha de registro</label>
              <input v-model="form.fecha_registro" type="date" disabled class="w-full bg-gray-100 rounded px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700">Registrado por</label>
              <input v-model="form.registrado_por" disabled class="w-full bg-gray-100 rounded px-3 py-2" />
            </div>
          </div>

          <div class="flex justify-end space-x-2 pt-4">
            <button @click="showModal = false" type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded">
              Cancelar
            </button>
            <button type="submit" :disabled="isLoading" class="px-4 py-2 bg-orange-500 text-white hover:bg-orange-600 rounded">
              {{ isLoading ? 'Guardando...' : (isEdit ? 'Actualizar' : 'Guardar') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
