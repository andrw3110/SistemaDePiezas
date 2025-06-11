<script setup>
import { ref, reactive } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps({
    piezas: Array,
    proyectos: Array,
    bloques: Array,
    usuarioNombre: String
})

const showModal = ref(false)
const isEdit = ref(false)
const form = reactive({
    pieza: '', 
    peso_teorico: '',
    peso_real: '',
    estado: 'Pendiente',
    id_proyecto: '',
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
        id_proyecto: '',
        id_bloque: '',
        fecha_registro: new Date().toISOString().slice(0, 10),
        registrado_por: props.usuarioNombre
    })
    showModal.value = true
}

function openEditModal(pieza) {
    isEdit.value = true
    // Ensure `id_pieza` is copied if it's the identifier for updates
    // Assuming `pieza` object from props already contains `id_pieza`
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
            }
        })
    } else {
        router.post('/piezas', payload, {
            onSuccess: () => {
                alert('Pieza creada correctamente.')
                showModal.value = false
                router.reload({ only: ['piezas'] })
            },
            onError: () => alert('Ocurrió un error al crear el registro.')
        })
    }
}

function deletePieza(id) {
    if (confirm('¿Estás seguro de eliminar esta pieza?')) {
        router.delete(`/piezas/${id}`, {
            onSuccess: () => {
                alert('Pieza eliminada correctamente.')
                router.reload({ only: ['piezas'] })
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
                <button @click="openCreateModal" class="bg-amber-200 text-amber-900 px-4 py-2 rounded-lg hover:bg-amber-300 transition">Agregar nueva</button>
            </div>

            <table class="min-w-full bg-amber-50 border border-amber-200 shadow rounded-lg">
                <thead>
                    <tr class="bg-amber-100 text-left text-sm font-semibold text-amber-900 rounded-t-lg">
                        <th class="px-4 py-2 border-b border-amber-300 rounded-tl-lg">#</th>
                        <th class="px-4 py-2 border-b border-amber-300">Pieza</th>
                        <th class="px-4 py-2 border-b border-amber-300">Proyecto</th>
                        <th class="px-4 py-2 border-b border-amber-300">Bloque</th>
                        <th class="px-4 py-2 border-b border-amber-300">Estado</th>
                        <th class="px-4 py-2 border-b border-amber-300 rounded-tr-lg">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(pieza, index) in props.piezas" :key="pieza.id_pieza" class="text-sm">
                        <td class="px-4 py-2 border-b border-amber-100">{{ index + 1 }}</td>
                        <td class="px-4 py-2 border-b border-amber-100">{{ pieza.pieza }}</td>
                        <td class="px-4 py-2 border-b border-amber-100">{{ pieza.proyecto?.nombre ?? 'Sin proyecto' }}</td>
                        <td class="px-4 py-2 border-b border-amber-100">{{ pieza.bloque?.nombre_bloque ?? 'Sin bloque' }}</td>
                        <td class="px-4 py-2 border-b border-amber-100">{{ pieza.estado }}</td>
                        <td class="px-4 py-2 border-b border-amber-100">
                            <div class="flex space-x-2">
                                <button @click="openEditModal(pieza)" class="bg-amber-200 text-amber-900 px-3 py-1 rounded-lg hover:bg-amber-300 transition">Editar</button>
                                <button @click="deletePieza(pieza.id_pieza)" class="bg-red-200 text-red-900 px-3 py-1 rounded-lg hover:bg-red-300 transition">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="props.piezas.length === 0">
                        <td colspan="6" class="text-center py-4 text-gray-500">
                            No hay piezas registradas
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4" role="dialog" aria-modal="true">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 overflow-y-auto max-h-[90vh]">
                <h3 class="text-lg font-semibold mb-4">{{ isEdit ? 'Editar Pieza' : 'Agregar Pieza' }}</h3>
                <form @submit.prevent="savePieza" class="space-y-4">
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Pieza</label>
                        <input v-model="form.pieza" type="text" class="w-full border border-amber-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-300" maxlength="10" required />
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peso Teórico</label>
                        <input v-model="form.peso_teorico" type="number" step="0.01" class="w-full border border-amber-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-300" required />
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peso Real</label>
                        <input v-model="form.peso_real" type="number" step="0.01" class="w-full border border-amber-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-300" />
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select v-model="form.estado" class="w-full border border-amber-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-300">
                            <option value="Fabricado">Fabricado</option>
                            <option value="Pendiente">Pendiente</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Proyecto</label>
                        <select v-model="form.id_proyecto" class="w-full border border-amber-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-300">
                            <option value="" disabled>Selecciona un proyecto</option>
                            <option v-for="p in props.proyectos" :key="p.id_proyecto" :value="p.id_proyecto">{{ p.nombre }}</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bloque</label>
                        <select v-model="form.id_bloque" class="w-full border border-amber-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-300">
                            <option value="" disabled>Selecciona un bloque</option>
                            <option v-for="b in props.bloques" :key="b.id_bloque" :value="b.id_bloque">{{ b.nombre_bloque }}</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Registro</label>
                        <input
                            v-model="form.fecha_registro"
                            type="date"
                            required
                            class="w-full border border-amber-300 rounded px-3 py-2 bg-amber-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-amber-300"
                            disabled
                        />
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Registrado por
                        </label>
                        <input
                            v-model="form.registrado_por"
                            type="text"
                            maxlength="50"
                            class="w-full border border-amber-300 rounded px-3 py-2 bg-amber-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-amber-300"
                            disabled
                        >
                    </div>
                    <div class="flex justify-end space-x-2 pt-4">
                        <button @click="showModal = false" type="button" class="px-4 py-2 bg-amber-100 text-amber-800 rounded hover:bg-amber-200 transition">Cancelar</button>
                        <button type="submit" class="px-5 py-2 bg-amber-400 text-white rounded-lg border border-amber-500 hover:bg-amber-500 transition">{{ isEdit ? 'Actualizar' : 'Guardar' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>