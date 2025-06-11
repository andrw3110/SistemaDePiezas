<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'

// Obtenemos las props enviadas desde el controlador
const { props } = usePage()

const resumen = props.resumen
const piezasRecientes = props.piezasRecientes
</script>

<template>
    <Head title="Panel Principal" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-[#264653] leading-tight">Panel Principal</h2>
        </template>

        <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Tarjetas resumen -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-[#E9C46A] text-[#264653] rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold">Proyectos</h3>
                    <p class="text-3xl mt-2">{{ resumen.proyectos }}</p>
                </div>
                <div class="bg-[#F4A261] text-white rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold">Bloques</h3>
                    <p class="text-3xl mt-2">{{ resumen.bloques }}</p>
                </div>
                <div class="bg-[#2A9D8F] text-white rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold">Piezas</h3>
                    <p class="text-3xl mt-2">{{ resumen.piezas }}</p>
                </div>
            </div>

            <!-- Tabla de piezas recientes -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <h3 class="text-xl font-semibold text-[#264653] px-6 pt-6">Últimas piezas registradas</h3>
                <table class="min-w-full text-sm text-gray-800 mt-4">
                    <thead class="bg-[#E9C46A] text-[#264653]">
                        <tr>
                            <th class="px-6 py-3 text-left">#</th>
                            <th class="px-6 py-3 text-left">Pieza</th>
                            <th class="px-6 py-3 text-left">Proyecto</th>
                            <th class="px-6 py-3 text-left">Bloque</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="(pieza, index) in piezasRecientes" 
                            :key="pieza.id_pieza" 
                            class="hover:bg-[#fdf6e3] border-b"
                        >
                            <td class="px-6 py-2">{{ index + 1 }}</td>
                            <td class="px-6 py-2">{{ pieza.pieza }}</td>
                            <td class="px-6 py-2">{{ pieza.proyecto?.nombre ?? 'Sin proyecto' }}</td>
                            <td class="px-6 py-2">{{ pieza.bloque?.nombre_bloque ?? 'Sin bloque' }}</td>
                            <td class="px-6 py-2">{{ pieza.estado }}</td>
                        </tr>
                        <tr v-if="piezasRecientes.length === 0">
                            <td colspan="5" class="text-center py-4 text-gray-500">No hay piezas registradas aún.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

