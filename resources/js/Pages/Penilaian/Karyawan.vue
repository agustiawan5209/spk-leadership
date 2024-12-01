<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, watch, defineProps, inject, onMounted } from 'vue';

const swal = inject('$swal')
const page = usePage()

onMounted(() => {
    if (page.props.message !== null) {
        swal({
            icon: "success",
            title: 'Berhasil',
            text: page.props.message,
            showConfirmButton: true,
            timer: 2000
        });
    }
})
const props = defineProps({
    kategori: {
        type: Object,
        default: () => ({}),
    },
    aspek: {
        type: Object,
        default: () => ({}),
    },
    penilaian: {
        type: Object,
        default: () => ({}),
    },
    alternatif: {
        type: Object,
        default: () => ({}),
    },
    staffpenilai: {
        type: Object,
        default: () => ({}),
    },
    aspek_kriteria: {
        type: Object,
        default: () => ({}),
    },
})


function cekPenilaian(item) {
    // Asumsikan nilai default sebagai `true` (belum ada penilaian)
    for (let i = 0; i < props.penilaian.length; i++) {
        const element = props.penilaian[i];
        // Jika menemukan kecocokan, artinya penilaian sudah ada, maka kembalikan `false`
        // console.log(item.staff.id , element.staff_id , element.staff_penilai_id , props.staffpenilai.id)
        if (item.staff.id == element.staff_id && element.staff_penilai_id == props.staffpenilai.id) {
            return false;
        }
    }
    // Jika tidak ada kecocokan, artinya penilaian belum dilakukan
    return true;
}

// cekPenilaian(props.alternatif[8])
</script>

<template>

    <Head title="Form Penilaian" />

    <AuthenticatedLayout>
        <template #header>
            <h2>Daftar Karyawan Untuk Penilaian Kinerja: {{ kategori.nama }} </h2>
        </template>

        <div class="py-4 relative box-content">
            <!-- component -->
            <div class="container mx-auto bg-white">
                <div class="flex flex-col">
                    <section class="md:px-4 rounded-md border py-2">
                        <div class="relative w-full inline-block align-middle overflow-hidden">

                            <div class="w-full overflow-x-auto">
                                <table class=" min-w-full rounded-xl">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th scope="col"
                                                class="px-1 py-2 text-left text-xs leading-6 font-semibold text-gray-900 capitalize rounded-t-xl">
                                                Nama Karyawan </th>
                                            <th scope="col"
                                                class="px-1 py-2 text-left text-xs leading-6 font-semibold text-gray-900 capitalize">
                                                Departement - Jabatan </th>
                                            <th scope="col"
                                                class="px-1 py-2 text-left text-xs leading-6 font-semibold text-gray-900 capitalize">
                                                Penilaian </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-300 ">
                                        <template  v-for="(item, index) in alternatif">
                                            <tr class="bg-white transition-all duration-500 hover:bg-gray-50"
                                           v-if="item.staff_id != staffpenilai.id">
                                            <td
                                                class="px-1 py-2 whitespace-nowrap text-xs leading-6 font-medium text-gray-900 ">
                                                {{ item.staff.nama }}

                                            </td>
                                            <td
                                                class="px-1 py-2 whitespace-nowrap text-xs leading-6 font-medium text-gray-900">
                                                {{ item.staff.departement.nama }} - {{ item.staff.jabatan }} </td>

                                            <td
                                                class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-gray-900">
                                                <Link v-if="cekPenilaian(item)"
                                                    :href="route('Penilaian.create', { kategori: kategori.id, alternatif: item.id })">
                                                <button
                                                    class="relative select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-max p-2 rounded-lg text-gray-900 hover:bg-gray-900/10 active:bg-gray-900/20 border border-primary"
                                                    type="button">
                                                    <span class="text-xs flex items-center">
                                                        Buat Penilaian
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="currentColor" aria-hidden="true" class="h-4 w-4">
                                                            <path
                                                                d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z">
                                                            </path>
                                                        </svg>
                                                    </span>

                                                </button>
                                                </Link>
                                                <button v-else
                                                    class="relative select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-max p-2 rounded-lg text-gray-900 hover:bg-gray-900/10 active:bg-gray-900/20 border border-primary"
                                                    type="button" :disabled="true">
                                                    <span class="text-xs flex items-center text-black">
                                                        Penilaian Telah Dibuat
                                                        <font-awesome-icon :icon="['fas', 'check']" />
                                                    </span>

                                                </button>
                                            </td>

                                        </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
