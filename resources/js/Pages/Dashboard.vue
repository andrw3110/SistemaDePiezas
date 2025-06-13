<script setup>
import { computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from '@inertiajs/vue3' // Eliminado Link si ya no se usa, o mantenido si hay otros Links. Por ahora, lo dejaré.

const { props } = usePage()

const resumen = computed(() => props.resumen ?? {});
const piezasRecientes = computed(() => props.piezasRecientes ?? []);

const totalPiezas = computed(() => (resumen.value.piezas_fabricadas ?? 0) + (resumen.value.piezas_pendientes ?? 0));

const fabricadoPorcentaje = computed(() => {
    if (totalPiezas.value === 0) return 0;
    return ((resumen.value.piezas_fabricadas ?? 0) / totalPiezas.value) * 100;
});

const pendientePorcentaje = computed(() => {
    if (totalPiezas.value === 0) return 0;
    return 100 - fabricadoPorcentaje.value;
});

const donutStyle = computed(() => {
    const colorFabricado = '#2A9D8F';
    const colorPendiente = '#E76F51';

    const fabricadoStop = fabricadoPorcentaje.value;

    return {
        backgroundImage: `conic-gradient(
            ${colorFabricado} 0% ${fabricadoStop}%,
            ${colorPendiente} ${fabricadoStop}% 100%
        )`
    };
});
</script>

<template>
    <Head title="Panel Principal" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-amber-900 leading-tight">Panel Principal</h2>
        </template>

        <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-amber-100 text-amber-900 rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold">Proyectos</h3>
                    <p class="text-3xl mt-2">{{ resumen.proyectos }}</p>
                </div>
                <div class="bg-amber-200 text-amber-900 rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold">Bloques</h3>
                    <p class="text-3xl mt-2">{{ resumen.bloques }}</p>
                </div>
                <div class="bg-amber-300 text-amber-900 rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold">Piezas Totales</h3>
                    <p class="text-3xl mt-2">{{ resumen.piezas }}</p>
                </div>
                <div class="bg-green-200 text-green-900 rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold">Piezas Fabricadas</h3>
                    <p class="text-3xl mt-2">{{ resumen.piezas_fabricadas ?? 0 }}</p>
                </div>
                <div class="bg-red-200 text-red-900 rounded-xl p-6 shadow-md">
                    <h3 class="text-lg font-bold">Piezas Pendientes</h3>
                    <p class="text-3xl mt-2">{{ resumen.piezas_pendientes ?? 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center justify-center">
                    <h3 class="text-xl font-semibold text-amber-900 mb-4">Resumen de Estado de Piezas</h3>

                    <div v-if="totalPiezas > 0" class="relative w-48 h-48 rounded-full flex items-center justify-center overflow-hidden"
                            :style="donutStyle">
                        <div class="absolute w-32 h-32 bg-white rounded-full z-10"></div>
                        <div class="absolute text-center text-amber-900 font-bold text-2xl z-20">
                            {{ totalPiezas }}
                            <span class="block text-sm font-normal text-gray-600">Total</span>
                        </div>
                    </div>
                    <div v-else class="w-48 h-48 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-center">
                        No hay piezas registradas.
                    </div>

                    <div class="mt-6 flex flex-wrap justify-center gap-4 text-amber-800">
                        <div class="flex items-center">
                            <span class="w-4 h-4 rounded-full bg-[#2A9D8F] mr-2"></span>
                            Fabricado ({{ fabricadoPorcentaje.toFixed(1) }}%)
                        </div>
                        <div class="flex items-center">
                            <span class="w-4 h-4 rounded-full bg-[#E76F51] mr-2"></span>
                            Pendiente ({{ pendientePorcentaje.toFixed(1) }}%)
                        </div>
                    </div>

                    <div class="mt-8"> <a :href="$route('reportes.piezas_por_proyecto')"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out inline-flex items-center"
                           target="_blank"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-9.293a1 1 0 011.414 0L10 10.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v7a1 1 0 11-2 0V3a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Exportar Reporte
                        </a>
                    </div>
                </div>

                <div class="bg-amber-50 rounded-xl shadow-md overflow-hidden p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-amber-900">Últimas piezas registradas</h3>
                        <a href="/piezas" class="text-amber-700 hover:text-amber-900 font-medium transition duration-150 ease-in-out">Ver todas las piezas &rarr;</a>
                    </div>
                    <table class="min-w-full text-sm text-amber-800">
                        <thead class="bg-amber-100 text-left text-sm font-semibold text-amber-900">
                            <tr>
                                <th class="px-4 py-3 text-left rounded-tl-lg">#</th>
                                <th class="px-4 py-3 text-left">Pieza</th>
                                <th class="px-4 py-3 text-left">Proyecto</th>
                                <th class="px-4 py-3 text-left">Bloque</th>
                                <th class="px-4 py-3 text-left rounded-tr-lg">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(pieza, index) in piezasRecientes"
                                :key="pieza.id_pieza"
                                class="hover:bg-amber-100 border-b border-amber-100 last:border-b-0"
                            >
                                <td class="px-4 py-2">{{ index + 1 }}</td>
                                <td class="px-4 py-2">{{ pieza.pieza }}</td>
                                <td class="px-4 py-2">{{ pieza.proyecto?.nombre ?? 'Sin proyecto' }}</td>
                                <td class="px-4 py-2">{{ pieza.bloque?.nombre_bloque ?? 'Sin bloque' }}</td>
                                <td class="px-4 py-2">{{ pieza.estado }}</td>
                            </tr>
                            <tr v-if="piezasRecientes.length === 0">
                                <td colspan="5" class="text-center py-4 text-gray-500">No hay piezas registradas aún.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>