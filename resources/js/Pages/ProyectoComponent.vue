<script setup>
import { ref, reactive, watch } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  proyectos: Array,
  flash: String
})

const page = usePage()
const showModal = ref(false)
const isEdit = ref(false)
const form = reactive({
  id_proyecto: '',
  original_id: '',
  nombre: ''
})

watch(() => page.props.flash?.message, (message) => {
  if (message) alert(message)
})

function openCreateModal() {
  isEdit.value = false
  form.id_proyecto = ''
  form.original_id = ''
  form.nombre = ''
  showModal.value = true
}

function openEditModal(proyecto) {
  isEdit.value = true
  form.id_proyecto = proyecto.id_proyecto
  form.original_id = proyecto.id_proyecto
  form.nombre = proyecto.nombre
  showModal.value = true
}

function saveProyecto() {
  const payload = {
    id_proyecto: form.id_proyecto,
    nombre: form.nombre
  }

  if (isEdit.value) {
    // When editing, the original_id is used for the PUT request URL
    // The id_proyecto in the form is what's being updated if the user changed it in the create modal
    // However, if the ID field is hidden during edit, form.id_proyecto will retain its original value
    // This is safe because original_id ensures the correct record is targeted.
    router.put(`/proyectos/${form.original_id}`, payload, {
      preserveScroll: true,
      onSuccess: () => (showModal.value = false)
    })
  } else {
    router.post('/proyectos', payload, {
      preserveScroll: true,
      onSuccess: () => (showModal.value = false)
    })
  }
}

function deleteProyecto(id) {
  if (confirm('¿Estás seguro de eliminar este proyecto?')) {
    router.delete(`/proyectos/${id}`, { preserveScroll: true })
  }
}
</script>

<template>
  <Head title="Proyectos" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-amber-900 leading-tight">
        Listado de Proyectos
      </h2>
    </template>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="mb-4">
        <button
          @click="openCreateModal"
          class="bg-amber-200 text-amber-900 px-4 py-2 rounded-lg hover:bg-amber-300 transition"
        >
          Agregar nuevo
        </button>
      </div>

      <table class="min-w-full bg-amber-50 border border-amber-200 shadow rounded-lg">
        <thead>
          <tr class="bg-amber-100 text-left text-sm font-semibold text-amber-900 rounded-t-lg">
            <th class="px-4 py-2 border-b border-amber-300 rounded-tl-lg">#</th>
            <th class="px-4 py-2 border-b border-amber-300">Nombre</th>
            <th class="px-4 py-2 border-b border-amber-300 rounded-tr-lg">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(proyecto, index) in props.proyectos"
            :key="proyecto.id_proyecto"
            class="text-sm hover:bg-amber-100 border-b border-amber-100 last:border-b-0"
            
          >
            <td class="px-4 py-2">{{ index + 1 }}</td>
            <td class="px-4 py-2">{{ proyecto.nombre }}</td>
            <td class="px-4 py-2">
              <div class="flex space-x-2">
                <button
                  @click="openEditModal(proyecto)"
                  class="bg-amber-200 text-amber-900 px-3 py-1 rounded-lg hover:bg-amber-300 transition"
                >
                  Editar
                </button>
                <button
                  @click="deleteProyecto(proyecto.id_proyecto)"
                  class="bg-red-200 text-red-900 px-3 py-1 rounded-lg hover:bg-red-300 transition"
                >
                  Eliminar
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="props.proyectos.length === 0">
            <td colspan="3" class="text-center py-4 text-gray-500">
              No hay proyectos registrados
            </td>
          </tr>
        </tbody>
      </table>

    </div>

    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4"
      role="dialog"
      aria-modal="true"
    >
      <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 overflow-y-auto max-h-[90vh]">
        <h3 class="text-lg font-semibold mb-4 text-amber-900">
          {{ isEdit ? 'Editar Proyecto' : 'Agregar Proyecto' }}
        </h3>
        <form @submit.prevent="saveProyecto" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Nombre del proyecto
            </label>
            <input
              v-model="form.nombre"
              type="text"
              maxlength="50"
              required
              class="w-full border border-amber-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-300"
            />
          </div>

          <div v-if="!isEdit">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              ID del Proyecto
            </label>
            <input
              v-model="form.id_proyecto"
              type="text"
              maxlength="20"
              required
              class="w-full border border-amber-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-300"
            />
          </div>

          <div class="flex justify-end space-x-2 pt-4">
            <button
              type="button"
              @click="showModal = false"
              class="px-4 py-2 bg-amber-100 text-amber-800 rounded hover:bg-amber-200 transition"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="px-5 py-2 bg-amber-400 text-white rounded-lg border border-amber-500 hover:bg-amber-500 transition"
            >
              {{ isEdit ? 'Actualizar' : 'Guardar' }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </AuthenticatedLayout>
</template>