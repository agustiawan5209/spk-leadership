<script setup>
// Importing components and utilities from other files
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import { ref, defineProps, watch, onMounted, inject } from 'vue';
import Perhitungan from './Perhitungan.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import PutusanForm from './PutusanForm.vue';
import Hasil from './Hasil.vue';
import Ranking from './Ranking.vue';
import LoadingSpinner from '@/Components/LoadingSpinner.vue';

// Getting the current page object
const page = usePage();

// Injecting the Swal library
const swal = inject('$swal');

// Running a function when the component is mounted
onMounted(() => {
    // If there's a message in the page props, show a success alert
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

// Defining props for this component
const props = defineProps({
    kategori: {
        type: Object,
        default: () => ({}) // Default value is an empty object
    },
    penilaian: {
        type: Object,
        default: () => ({}) // Default value is an empty object
    },
    hasilpenilaian: {
        type: Object,
        default: () => ({}) // Default value is an empty object
    },
    aspek: {
        type: Object,
        default: () => ({}) // Default value is an empty object
    },
    aspekkriteria: {
        type: Object,
        default: () => ({}) // Default value is an empty object
    },
    alternatif: {
        type: Object,
        default: () => ({}) // Default value is an empty object
    },
    perhitungan: {
        type: Object,
        default: () => ({}) // Default value is an empty object
    },
    keputusan: {
        type: Object,
        default: () => ({}) // Default value is an empty object
    },
    ranking: {
        type: Object,
        default: () => ({}) // Default value is an empty object
    },
    can: {
        type: Object,
        default: () => ({}) // Default value is an empty object
    },
    rank: {
        type: [Array, Object], // Can be either an array or an object
        default: () => ([]) // Default value is an empty array
    },
})

// Creating a new array from the kategori.penilaian prop
const dataPenilai = [...props.kategori.penilaian];

// Function to count the occurrences of each staff_penilai_id in an array
function findSameItem(array) {
    const count = {};

    array.forEach(item => {
        if (count[item.staff_penilai_id]) {
            count[item.staff_penilai_id]++;
        } else {
            count[item.staff_penilai_id] = 1;
        }
    });

    return count;
}

// Creating a new array from the rank prop, handling both array and object cases
let Rank = [];
if (Array.isArray(props.rank)) {
    Rank = [...props.rank]; // Spread operator to create a copy of the array
} else if (typeof props.rank === 'object') {
    // If props.rank is an object, convert it to an array
    Rank = Object.values(props.rank);
}

// Sorting the Rank array by the 'hasil' property
const HasilRank = Rank.sort((a, b) => a.hasil - b.hasil);

// Function to get the count of unique staff_penilai_id in an array
function uniqueField(array) {
    const seen = new Set();
    const result = [];

    array.forEach(item => {
        if (!seen.has(item.staff_penilai_id)) {
            seen.add(item.staff_penilai_id);
            result.push(item);
        }
    });

    return result.length;
}

// Getting the count of unique penilai
const Penilai = uniqueField(dataPenilai);

// Creating a reactive reference for the current tab
const tabAction = ref(2);

// Defining classes for active and non-active tabs
const tabActive = 'inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray -200 rounded-lg focus:ring-4 focus:ring-blue-300 active focus:outline-none';
const tabNonActive = 'inline-block w-full p-4 bg-white border-r border-gray-200 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none';

// Creating a reactive reference for the putusan modal
const varPutusan = ref(false);

// Function to toggle the putusan modal
function showPutusan() {
    varPutusan.value = !varPutusan.value
}

// Function to close the putusan modal
const handleClose = () => {
    varPutusan.value = false; // Emit event to close the modal
};

// Creating a form instance for the aspek
const FormAspek = useForm({
    slug: props.kategori.id,
    aspek: props.aspek.id,
})

// Creating a reactive reference for the aspek slug
const AspekSlug = ref(props.aspek.id);

// Watching the AspekSlug reference and updating the form when it changes
watch(AspekSlug, (value) => {
    FormAspek.aspek = value;
    FormAspek.get(route('admin.riwayat.show'), {
        preserveScroll: true,
        preserveState: true,
        onBefore: () => {
            spinnerPage.value = true;
        },
        onFinish: () => {
            spinnerPage.value = false
        }
    })
})

// Creating a reactive reference for the page spinner
const spinnerPage = ref(false);
</script>
<template>
    <LoadingSpinner v-if="spinnerPage" />

    <Head title="Riwayat Penilaian" />

    <AuthenticatedLayout>
        <template #header>
            <h2>Detail Riwayat Penilaian</h2>
        </template>

        <div class="md:py-4 relative box-content">
            <section class=" py-2 px-0 md:px-6  md:py-6 bg-primary text-dark">
                <form novalidate="" action="" class="container flex flex-col mx-auto space-y-12">
                    <fieldset class="grid grid-cols-3 gap-6 p-6 rounded-md shadow-sm bg-gray-50">
                        <div class="grid grid-cols-3 gap-4 col-span-full ">
                            <!-- Detail Evaluasi -->
                            <div class="col-span-full sm:col-span-3 ">
                                <ul class="flex flex-col space-y-20">
                                    <li class="flex gap-3 py-2 border-b">
                                        <span class="text-lg">Detail Riwayat Penilaian</span>
                                    </li>
                                </ul>

                                <table class="w-full table">
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold">Nama</td>
                                        <td class="text-sm border-b text-gray-900">: {{ kategori.nama }} </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold">Jadwal Penilaian</td>
                                        <td class="text-sm border-b text-gray-900">: {{ kategori.tanggal }} </td>
                                    </tr>

                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold">Jumlah Penilaian Yang Masuk </td>
                                        <td class="text-sm border-b text-gray-900">: {{ Penilai }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold">Status Penilaian </td>
                                        <td class="text-sm border-b text-gray-900"> :{{ kategori.status }}
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="text-sm border-b py-2 font-bold">Keterangan :</td>
                                        <td class="text-sm border-b text-gray-900" v-html="kategori.keterangan">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- End Detail Evaluasi -->

                            <!-- Tab Action 1 -->
                            <div class="col-span-full sm:col-span-3">
                                <div class="sm:hidden">
                                    <label for="tabs" class="sr-only">------</label>
                                    <select id="tabs"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="0">Data Karyawan</option>
                                        <option value="1">Perhitungan</option>
                                        <option value="2">Hasil</option>
                                        <option value="3">Putusan</option>
                                    </select>
                                </div>
                                <ul
                                    class="hidden text-sm font-medium text-center text-gray-500 rounded-lg shadow sm:flex">
                                    <li class="w-full focus-within:z-10" @click="tabAction = 0">
                                        <a href="#" :class="tabAction == 0 ? tabActive : tabNonActive"
                                            aria-current="page" class="text-xs">Data
                                            Karyawan</a>
                                    </li>
                                    <li class="w-full focus-within:z-10" @click="tabAction = 1">
                                        <a href="#" :class="tabAction == 1 ? tabActive : tabNonActive">Perhitungan</a>
                                    </li>
                                    <li class="w-full focus-within:z-10" @click="tabAction = 2">
                                        <a href="#" :class="tabAction == 2 ? tabActive : tabNonActive">Hasil</a>
                                    </li>
                                    <li class="w-full focus-within:z-10" @click="tabAction = 3">
                                        <a href="#" :class="tabAction == 3 ? tabActive : tabNonActive">Ranking</a>
                                    </li>
                                </ul>

                            </div>
                            <!-- Tab ACtion 1 -->


                            <div class="col-span-full sm:col-span-3" v-if="tabAction == 1">
                                <div class="flex w-full rounded-md shadow-sm" role="group">

                                    <template v-for="item in aspekkriteria">
                                        <button type="button" @click="AspekSlug = item.id"
                                            class="inline-flex w-2/6 items-center px-4 py-2 text-sm font-medium text-white bg-primary border border-gray-900 hover:bg-secondary hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-secondary focus:text-white transition-all">
                                            <svg class="w-3 h-3 me-2" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M4 12.25V1m0 11.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M4 19v-2.25m6-13.5V1m0 2.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M10 19V7.75m6 4.5V1m0 11.25a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM16 19v-2" />
                                            </svg>
                                            {{ item.nama }}
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <transition-group>
                                <div class="col-span-full overflow-x-auto mt-3" v-if="tabAction == 0">
                                    <table class="w-full text-xs text-left rtl:text-right text-gray-500">
                                        <thead class="text-xs text-white uppercase bg-primary ">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">
                                                    Departement
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Nama Karyawan
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Jabatan
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-white border-b" v-for="(item, index) in kategori.alternatif"
                                                :key="index">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium text-sm capitalize text-gray-900 whitespace-nowrap">
                                                    {{ item.staff.nama_departement }}
                                                </th>
                                                <td class="px-6 py-4">
                                                    {{ item.staff.nama }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    {{ item.staff.jabatan }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-span-full overflow-x-auto mt-3" v-if="tabAction == 1">
                                    <Perhitungan :perhitungan="perhitungan" :aspek="aspek" />
                                </div>
                                <div class="col-span-full overflow-x-auto mt-3" v-if="tabAction == 2">
                                    <Hasil :hasil="hasilpenilaian" :aspek="aspekkriteria" :alternatif="alternatif" />
                                </div>
                                <div class="col-span-full overflow-x-auto mt-3" v-if="tabAction == 3">
                                    <Ranking :rank="ranking" />
                                </div>
                            </transition-group>
                        </div>
                    </fieldset>
                </form>
            </section>
        </div>

        <!-- Modal Form Buat Keputusan -->
        <Modal :show="varPutusan" maxWidth="7xl">

            <PutusanForm :staff="HasilRank" :kategori="kategori" @close="varPutusan = false"></PutusanForm>
        </Modal>
        <!--  -->
    </AuthenticatedLayout>
</template>
