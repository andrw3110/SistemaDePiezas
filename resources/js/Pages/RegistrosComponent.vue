<script setup>
import { ref, reactive, watch } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps({
  registros: Array,
  usuarioNombre: String,
  bloques: Array
})

const showModal = ref(false)
const isEdit = ref(false)
const form = reactive({
  pieza: '',
  peso_teorico: '',
  peso_real: '',
  estado: 'Pendiente',
  id_bloque: '',
  fecha_registro: '',
  registrado_por: ''
})

function openCreateModal() {
  isEdit.value = false
  Object.assign(form, {
    pieza: '',
    peso_teorico: '',
    peso_real: '',
    estado: 'Pendiente',
    id_bloque: '',
    fecha_registro: new Date().toISOString().slice(0, 10),
    registrado_por: props.usuarioNombre
  })
  showModal.value = true
}

function openEditModal(registro) {
  isEdit.value = true
  Object.assign(form, { ...registro })
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  Object.assign(form, {
    pieza: '',
    peso_teorico: '',
    peso_real: '',
    estado: 'Pendiente',
    id_bloque: '',
    fecha_registro: '',
    registrado_por: ''
  })
}

function saveRegistro() {
  if (!form.pieza || !form.id_bloque || !form.peso_teorico) {
    alert('Por favor, completa todos los campos obligatorios.')
    return
  }

  const payload = {
    ...form,
    peso_teorico: parseFloat(form.peso_teorico),
    peso_real: form.peso_real ? parseFloat(form.peso_real) : null
  }

  const method = isEdit.value ? 'put' : 'post'
  const url = isEdit.value ? `/registros/${form.id_pieza}` : '/registros'

  router[method](url, payload, {
    onSuccess: () => {
      alert(isEdit.value ? 'Registro actualizado correctamente.' : 'Registro creado correctamente.')
      closeModal()
      router.reload({ only: ['registros'] })
    },
    onError: () => {
      alert(isEdit.value ? 'Ocurrió un error al actualizar el registro.' : 'Ocurrió un error al crear el registro.')
    }
  })
}

function deleteRegistro(id) {
  if (confirm('¿Estás seguro de eliminar este registro?')) {
    router.delete(`/registros/${id}`, {
      onSuccess: () => {
        alert('Registro eliminado correctamente.')
        router.reload({ only: ['registros'] })
      },
      onError: () => alert('Ocurrió un error al eliminar el registro.')
    })
  }
}

watch(showModal, (val) => {
  document.body.classList.toggle('overflow-hidden', val)
})
</script>

<template>
  <Head title="Registros" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-amber-700 leading-tight">
        Listado de Registros
      </h2>
    </template>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="mb-6">
        <button
          @click="openCreateModal"
          class="bg-amber-500 text-white px-5 py-2 rounded-lg border border-amber-600 hover:bg-amber-600 shadow-md transition"
        >
          Agregar nuevo
        </button>
      </div>

      <table class="min-w-full bg-white border border-amber-300 shadow-md rounded-lg overflow-hidden">
        <thead>
          <tr class="bg-amber-100 text-left text-sm font-semibold text-amber-800">
            <th class="px-5 py-3 border border-amber-300">#</th>
            <th class="px-5 py-3 border border-amber-300">Pieza</th>
            <th class="px-5 py-3 border border-amber-300">Peso Teórico</th>
            <th class="px-5 py-3 border border-amber-300">Peso Real</th>
            <th class="px-5 py-3 border border-amber-300">Estado</th>
            <th class="px-5 py-3 border border-amber-300">Bloque</th>
            <th class="px-5 py-3 border border-amber-300">Fecha</th>
            <th class="px-5 py-3 border border-amber-300">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(registro, index) in props.registros"
            :key="registro.id_pieza"
            class="text-sm hover:bg-amber-50 transition-colors"
          >
            <td class="px-5 py-3 border border-amber-300">{{ index + 1 }}</td>
            <td class="px-5 py-3 border border-amber-300">{{ registro.pieza }}</td>
            <td class="px-5 py-3 border border-amber-300">{{ registro.peso_teorico }}</td>
            <td class="px-5 py-3 border border-amber-300">{{ registro.peso_real ?? '-' }}</td>
            <td class="px-5 py-3 border border-amber-300">{{ registro.estado }}</td>
            <td class="px-5 py-3 border border-amber-300">
              {{
                props.bloques.find(b => b.id_bloque === registro.id_bloque)?.nombre_bloque || 'Sin bloque'
              }}
            </td>
            <td class="px-5 py-3 border border-amber-300">{{ registro.fecha_registro }}</td>
            <td class="px-5 py-3 border border-amber-300 flex space-x-2">
              <button
                @click="openEditModal(registro)"
                class="text-amber-700 border border-amber-400 px-3 py-1 rounded-lg hover:bg-amber-200 transition"
              >
                Editar
              </button>
              <button
                @click="deleteRegistro(registro.id_pieza)"
                class="text-red-600 border border-red-400 px-3 py-1 rounded-lg hover:bg-red-100 transition"
              >
                Eliminar
              </button>
            </td>
          </tr>
          <tr v-if="props.registros.length === 0">
            <td colspan="8" class="text-center py-6 text-gray-400 italic">
              No hay registros
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 px-4"
      role="dialog"
      aria-modal="true"
    >
      <div
        class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-7 overflow-y-auto max-h-[90vh] border border-amber-200"
      >
        <h3 class="text-xl font-semibold text-amber-700 mb-5">
          {{ isEdit ? 'Editar Registro' : 'Agregar Registro' }}
        </h3>
        <form @submit.prevent="saveRegistro" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Nombre de Pieza
            </label>
            <input
              v-model="form.pieza"
              type="text"
              maxlength="50"
              required
              class="w-full border border-amber-300 rounded-lg px-4 py-2 focus:ring-amber-400 focus:border-amber-400 transition"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Peso Teórico
            </label>
            <input
              v-model="form.peso_teorico"
              type="number"
              step="0.01"
              required
              class="w-full border border-amber-300 rounded-lg px-4 py-2 focus:ring-amber-400 focus:border-amber-400 transition"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Peso Real
            </label>
            <input
              v-model="form.peso_real"
              type="number"
              step="0.01"
              class="w-full border border-amber-300 rounded-lg px-4 py-2 focus:ring-amber-400 focus:border-amber-400 transition"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Estado
            </label>
            <select
              v-model="form.estado"
              class="w-full border border-amber-300 rounded-lg px-4 py-2 focus:ring-amber-400 focus:border-amber-400 transition"
            >
              <option value="Fabricado">Fabricado</option>
              <option value="Pendiente">Pendiente</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Bloque
            </label>
            <select
              v-model="form.id_bloque"
              class="w-full border border-amber-300 rounded-lg px-4 py-2 focus:ring-amber-400 focus:border-amber-400 transition"
              required
            >
              <option value="" disabled>Selecciona un bloque</option>
              <option
                v-for="bloque in props.bloques"
                :key="bloque.id_bloque"
                :value="bloque.id_bloque"
              >
                {{ bloque.nombre_bloque }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Fecha Registro
            </label>
            <input
              v-model="form.fecha_registro"
              type="date"
              required
              class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed"
              disabled
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Registrado por
            </label>
            <input
              v-model="form.registrado_por"
              type="text"
              maxlength="50"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 cursor-not-allowed"
              disabled
            />
          </div>

          <div class="flex justify-end space-x-3 pt-6">
            <button
              type="button"
              @click="closeModal"
              class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 border border-gray-400 transition"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="px-5 py-2 bg-amber-500 text-white rounded-lg border border-amber-600 hover:bg-amber-600 transition"
            >
              {{ isEdit ? 'Actualizar' : 'Guardar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
