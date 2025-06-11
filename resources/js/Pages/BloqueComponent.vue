<script setup>
import { ref, reactive } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
  bloques: Array,
  proyectos: Array
})

const showModal = ref(false)
const isEdit = ref(false)
const form = reactive({
  id_bloque: null,
  nombre_bloque: '',
  id_proyecto: ''
})

// Abre modal para crear
function openCreateModal() {
  isEdit.value = false
  form.id_bloque = null
  form.nombre_bloque = ''
  form.id_proyecto = ''
  showModal.value = true
}

// Abre modal para editar
function openEditModal(bloque) {
  isEdit.value = true
  form.id_bloque = bloque.id_bloque
  form.nombre_bloque = bloque.nombre_bloque
  form.id_proyecto = bloque.id_proyecto
  showModal.value = true
}

// Guarda o actualiza bloque
function saveBloque() {
  const isEditing = isEdit.value
  const url = isEditing ? `/bloques/${form.id_bloque}` : '/bloques'
  const method = isEditing ? 'put' : 'post'

  router.visit(url, {
    method: method,
    data: form,
    preserveState: true,
    onSuccess: () => {
      alert(isEditing ? 'Bloque actualizado correctamente.' : 'Bloque creado correctamente.')
      showModal.value = false // ✅ Cierra el modal al terminar
    },
    onError: (errors) => {
      const mensajes = Object.values(errors).flat().join('\n')
      alert(`Errores de validación:\n${mensajes}`)
    },
    onFinish: () => {
      // Aquí puedes limpiar el formulario si quieres
    }
  })
}




// Elimina bloque
function deleteBloque(id) {
  if (confirm('¿Estás seguro de eliminar este bloque?')) {
    router.delete(`/bloques/${id}`, {
      onSuccess: () => {
        alert('Bloque eliminado correctamente.')
        router.reload({ only: ['bloques'] })
      },
      onError: () => {
        alert('Ocurrió un error al eliminar el bloque.')
      }
    })
  }
}
</script>

<template>
  <Head title="Bloques" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Listado de Bloques
      </h2>
    </template>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="mb-4">
        <button @click="openCreateModal" class="btn-primary">
          Agregar nuevo
        </button>
      </div>

      <table class="custom-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre Bloque</th>
            <th>Proyecto</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(bloque, index) in props.bloques" :key="bloque.id_bloque" class="hover-row">
            <td>{{ index + 1 }}</td>
            <td>{{ bloque.nombre_bloque }}</td>
            <td>{{ bloque.proyecto?.nombre ?? 'Sin proyecto' }}</td>
            <td>
              <button @click="openEditModal(bloque)" class="btn-outline-amber me-2">Editar</button>
              <button @click="deleteBloque(bloque.id_bloque)" class="btn-outline-danger">Eliminar</button>
            </td>
          </tr>
          <tr v-if="props.bloques.length === 0">
            <td colspan="4" class="text-center text-muted py-3">No hay bloques registrados</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal-overlay" role="dialog" aria-modal="true">
      <div class="modal-content">
        <h3 class="modal-title">{{ isEdit ? 'Editar Bloque' : 'Agregar Bloque' }}</h3>
        <form @submit.prevent="saveBloque" class="modal-form">
          <div>
            <label class="form-label">Nombre del bloque</label>
            <input
              v-model="form.nombre_bloque"
              type="text"
              maxlength="10"
              required
              class="form-input"
            />
          </div>
          <div>
            <label class="form-label">Proyecto</label>
            <select v-model="form.id_proyecto" required class="form-input">
              <option value="" disabled>Selecciona un proyecto</option>
              <option v-for="proyecto in props.proyectos" :key="proyecto.id_proyecto" :value="proyecto.id_proyecto">
                {{ proyecto.nombre }}
              </option>
            </select>
          </div>
          <div class="modal-actions">
            <button type="button" @click="showModal = false" class="btn-cancel">Cancelar</button>
            <button type="submit" class="btn-primary">
              {{ isEdit ? 'Actualizar' : 'Guardar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Estilos personalizados de tabla, botones y modal (ya definidos por ti, no se modifican) */
.custom-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  border-radius: 10px;
  border: 1px solid #fbbf24;
  box-shadow: 0 2px 10px rgb(251 191 36 / 0.15);
  background-color: white;
  overflow: hidden;
}
.custom-table thead {
  background-color: #fef3c7;
  color: #92400e;
  font-weight: 700;
}
.custom-table thead th {
  padding: 12px 15px;
  text-align: left;
}
.custom-table tbody td {
  padding: 12px 15px;
  border-top: 1px solid #fbbf24;
  color: #5c3d00;
}
.hover-row:hover {
  background-color: #fef3c7;
  cursor: pointer;
}
.btn-primary {
  background-color: #fbbf24;
  color: #92400e;
  border: 1px solid #f59e0b;
  padding: 0.4rem 1rem;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
}
.btn-primary:hover {
  background-color: #f59e0b;
  color: #7c2d12;
}
.btn-outline-amber {
  background: transparent;
  color: #fbbf24;
  border: 1px solid #fbbf24;
  padding: 0.3rem 0.8rem;
  border-radius: 5px;
  font-weight: 600;
  cursor: pointer;
}
.btn-outline-amber:hover {
  background-color: #fef3c7;
  color: #92400e;
}
.btn-outline-danger {
  background: transparent;
  color: #dc2626;
  border: 1px solid #dc2626;
  padding: 0.3rem 0.8rem;
  border-radius: 5px;
  font-weight: 600;
  cursor: pointer;
}
.btn-outline-danger:hover {
  background-color: #fee2e2;
  color: #b91c1c;
}
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
  padding: 1rem;
}
.modal-content {
  background-color: white;
  border-radius: 12px;
  padding: 1.5rem 2rem;
  max-width: 480px;
  width: 100%;
  box-shadow: 0 10px 25px rgba(251, 191, 36, 0.2);
}
.modal-title {
  color: #92400e;
  font-weight: 700;
  font-size: 1.25rem;
  margin-bottom: 1rem;
}
.modal-form > div {
  margin-bottom: 1rem;
}
.form-label {
  display: block;
  font-weight: 600;
  color: #5c3d00;
  margin-bottom: 0.3rem;
}
.form-input {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1.5px solid #fbbf24;
  border-radius: 8px;
  font-size: 1rem;
  color: #5c3d00;
}
.form-input:focus {
  outline: none;
  border-color: #92400e;
  box-shadow: 0 0 5px #fbbf24;
}
.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 1.5rem;
}
.btn-cancel {
  background-color: #f3f4f6;
  color: #6b7280;
  border-radius: 6px;
  padding: 0.4rem 1rem;
  font-weight: 600;
  border: 1px solid #d1d5db;
}
.btn-cancel:hover {
  background-color: #e5e7eb;
  color: #4b5563;
}
</style>
