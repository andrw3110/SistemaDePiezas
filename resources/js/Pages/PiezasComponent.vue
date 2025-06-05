<script setup>
import { ref, reactive } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps({
    piezas: Array,
    proyectos: Array,
    bloques: Array
})

const showModal = ref(false)
const isEdit = ref(false)
const form = reactive({
    id_pieza: null,
    pieza: '', 
    peso_teorico: '',
    peso_real: '',
    estado: 'Pendiente',
    id_proyecto: '',
    id_bloque: '',
    fecha_registro: ''
})

function openCreateModal() {
    isEdit.value = false
    Object.assign(form, {
        id_pieza: null,
        pieza: '',
        peso_teorico: '',
        peso_real: '',
        estado: 'Pendiente',
        id_proyecto: '',
        id_bloque: '',
        fecha_registro: ''
    })
    showModal.value = true
}

function openEditModal(pieza) {
    isEdit.value = true
    Object.assign(form, { ...pieza })
    showModal.value = true
}

function savePieza() {
    const payload = { ...form }
    if (isEdit.value) {
        router.put(`/piezas/${form.id_pieza}`, payload, {
            onSuccess: () => {
                alert('Pieza actualizada correctamente.')
                showModal.value = false
                router.reload({ only: ['piezas'] })
            },
            onError: () => {
                alert('Error al actualizar la pieza.')
            }
        })
    } else {
        router.post('/piezas', payload, {
            onSuccess: () => {
                alert('Pieza creada correctamente.')
                showModal.value = false
                router.reload({ only: ['piezas'] })
            },
            onError: () => {
                alert('Error al crear la pieza.')
            }
        })
    }
}

function deletePieza(id) {
    if (confirm('¿Estás seguro de eliminar esta pieza?')) {
        router.delete(`/piezas/${id}`, {
            onSuccess: () => {
                alert('Pieza eliminada correctamente.')
                router.reload({ only: ['piezas'] })
            },
            onError: () => {
                alert('Error al eliminar la pieza.')
            }
        })
    }
}
</script>

<template>
    <Head title="Piezas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Listado de Piezas</h2>
        </template>

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <button 
                  @click="openCreateModal" 
                  class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm transition">
                    Agregar nueva
                </button>
            </div>

            <table class="min-w-full bg-white border border-gray-300 rounded shadow-sm">
                <thead>
                    <tr class="bg-blue-100 text-left text-sm font-semibold text-blue-700">
                        <th class="px-3 py-2 border">#</th>
                        <th class="px-3 py-2 border">Pieza</th>
                        <th class="px-3 py-2 border">Proyecto</th>
                        <th class="px-3 py-2 border">Bloque</th>
                        <th class="px-3 py-2 border">Estado</th>
                        <th class="px-3 py-2 border">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(pieza, index) in props.piezas" :key="pieza.id_pieza" class="text-sm">
                        <td class="px-3 py-2 border">{{ index + 1 }}</td>
                        <td class="px-3 py-2 border">{{ pieza.pieza }}</td>
                        <td class="px-3 py-2 border">{{ pieza.proyecto?.nombre ?? 'Sin proyecto' }}</td>
                        <td class="px-3 py-2 border">{{ pieza.bloque?.nombre_bloque ?? 'Sin bloque' }}</td>
                        <td class="px-3 py-2 border">{{ pieza.estado }}</td>
                        <td class="px-3 py-2 border whitespace-nowrap">
                            <button @click="openEditModal(pieza)" class="text-blue-600 hover:underline mr-2 text-sm">
                                Editar
                            </button>
                            <button @click="deletePieza(pieza.id_pieza)" class="text-red-600 hover:underline text-sm">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    <tr v-if="props.piezas.length === 0">
                        <td colspan="6" class="text-center text-gray-500 py-3">No hay piezas registradas</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div 
          v-if="showModal" 
          class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3"
          style="overflow-y:auto;">
            <div class="bg-white rounded-lg shadow-lg p-5 w-full max-w-md max-h-[90vh] overflow-auto">
                <h3 class="text-lg font-semibold mb-4 text-blue-700">{{ isEdit ? 'Editar Pieza' : 'Agregar Pieza' }}</h3>
                <form @submit.prevent="savePieza" class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Pieza</label>
                        <input 
                          v-model="form.pieza" 
                          type="text" maxlength="10" required 
                          class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peso Teórico</label>
                        <input 
                          v-model="form.peso_teorico" 
                          type="number" step="0.01" required 
                          class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peso Real</label>
                        <input 
                          v-model="form.peso_real" 
                          type="number" step="0.01" 
                          class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select 
                          v-model="form.estado" 
                          class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="Fabricado">Fabricado</option>
                            <option value="Pendiente">Pendiente</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Proyecto</label>
                        <select 
                          v-model="form.id_proyecto" 
                          class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="" disabled>Selecciona un proyecto</option>
                            <option v-for="p in props.proyectos" :key="p.id_proyecto" :value="p.id_proyecto">{{ p.nombre }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bloque</label>
                        <select 
                          v-model="form.id_bloque" 
                          class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="" disabled>Selecciona un bloque</option>
                            <option v-for="b in props.bloques" :key="b.id_bloque" :value="b.id_bloque">{{ b.nombre_bloque }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Registro</label>
                        <input 
                          v-model="form.fecha_registro" 
                          type="date" required 
                          class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <div class="flex justify-end space-x-3 mt-4">
                        <button 
                        @click="showModal = false" 
                            type="button" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded text-sm transition">
                             Cancelar
                            </button>
                            <button 
                            type="submit" 
                            class="bg-blue-300 hover:bg-blue-400 text-white px-3 py-1.5 rounded text-sm transition">
                            {{ isEdit ? 'Actualizar' : 'Guardar' }}
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
