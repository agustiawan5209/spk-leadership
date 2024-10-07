<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, watch, defineProps, inject } from 'vue';
const swal = inject('$swal')
const props = defineProps({
    kategori: {
        type: Object,
        default: () => ({}),
    },
    aspek: {
        type: Object,
        default: () => ({}),
    },
    kriteria: {
        type: Object,
        default: () => ({}),
    },
    alternatif: {
        type: Object,
        default: () => ({}),
    },
    aspek_kriteria: {
        type: Object,
        default: () => ({}),
    },
})
const dateNow = new Date().toISOString().slice(0, 10);
const aspekID = ref(props.aspek_kriteria.id)
const aspekForm = useForm({
    aspek_id: '',
})

watch(aspekID, (value) => {
    aspekForm.aspek_id = value;
    aspekForm.get(route('Penilaian.create', { kategori: props.kategori.id }), {
        preserveState: true,
        // preserveScroll: true,
        onFinish: () => {
            console.log(props.kriteria)
        }
    })
})

const Penilaian_karyawan = ref([]);

if (props.aspek.length > 0 && props.kriteria.length > 0) {

    // Jadikan Kriteria ke dalam bentuk array
    const Datakriteria = [...props.aspek];
    Penilaian_karyawan.value = Datakriteria.reduce((acc, curr, i) => ({
        ...acc, [i]: {
            aspek:curr.id,
            kriteria: [],
            data: curr.kriteriapenilaian,
        }
    }), {});
};
console.log(Penilaian_karyawan.value)
const Form = useForm({
    kategori: props.kategori.id,
    // aspek_id: 1,
    alternatif: props.alternatif.id,
    tgl_penilaian: dateNow,
    kriteria: [],
})

function BobotPenilaian(index, idx, value) {
    const bobot = value.target.value;

    // Masukkan Bobot Value dari matrix karyawan
    Penilaian_karyawan.value[index].kriteria[idx] = bobot;
}


function submit() {
    Form.kriteria = Penilaian_karyawan.value;
    Form.post(route('Penilaian.store'), {
        onError: (err) => {
            console.log(err)
            var txt = "<ul>"
            Object.keys(err).forEach((item, val) => {
                txt += `<li class="text-xs leading-7">${err[item]}</li>`
            });
            txt += "</ul>";
            console.log(txt)
            swal({
                title: "Peringatan",
                icon: "error",
                html: txt,
                showCloseButton: true,
                showCancelButton: true,
            });
        }
    })
}
</script>

<template>

    <Head title="Form Penilaian" />

    <AuthenticatedLayout>
        <template #header>
            <h2>Form Penilaian {{ kategori.nama }} </h2>
        </template>

        <div class="py-4 relative box-content">
            <!-- component -->
            <div class="container mx-auto bg-white">
                <div class="flex flex-col">
                    <form @submit.prevent="submit()" class="md:px-4 rounded-md border py-2">
                        <div class="relative w-full inline-block align-middle overflow-hidden">
                            <div class="grid grid-cols-2 gap-7 items-center">

                                <div class="relative  text-gray-500 focus-within:text-gray-900 mb-4">
                                    <InputLabel for="tgl_penilaian" value="Tanggal Penilaian" />
                                    <TextInput type="date" v-model="Form.tgl_penilaian" :readonly="true" />
                                </div>
                            </div>
                            <div class="w-full overflow-x-auto">
                                <table>
                                    <tr>
                                        <th scope="col"
                                            class="px-1 py-2 text-left text-xs leading-6 font-semibold text-gray-900 capitalize rounded-t-xl">
                                            Nama Karyawan </th>
                                        <td scope="col"
                                            class="px-1 py-2 text-left text-xs leading-6 text-gray-900 capitalize">
                                            : {{ alternatif.staff.nama }} </td>

                                    </tr>
                                    <tr>
                                        <th scope="col"
                                            class="px-1 py-2 text-left text-xs leading-6 font-semibold text-gray-900 capitalize">
                                            Departement - Jabatan </th>
                                        <td scope="col"
                                            class="px-1 py-2 text-left text-xs leading-6 text-gray-900 capitalize">
                                            : {{ alternatif.staff.departement.nama }} </td>
                                    </tr>
                                </table>


                                <!-- <div class="flex w-full rounded-md shadow-sm" role="group">

                                    <button type="button" v-for="item in aspek"
                                        class="inline-flex w-2/6 items-center px-4 py-2 text-sm font-medium text-gray-900 bg-transparent border border-gray-900 hover:bg-secondary hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-secondary focus:text-white transition-all">
                                        <svg class="w-3 h-3 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 12.25V1m0 11.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M4 19v-2.25m6-13.5V1m0 2.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M10 19V7.75m6 4.5V1m0 11.25a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM16 19v-2" />
                                        </svg>
                                        {{ item.nama }}
                                    </button>
                                </div> -->
                            </div>

                            <table class="table w-full border rounded-md">
                                <caption>Tabel Penilaian</caption>
                                <thead>
                                    <th scope="col"
                                        class="px-1 py-2 border text-left text-xs leading-6 font-semibold text-gray-900 capitalize">
                                        Nama Kriteria </th>
                                    <th scope="col" v-for="num in 5"
                                        class="px-1 py-2 border text-center text-xs leading-6 font-semibold text-gray-900 capitalize">
                                        {{ num }} </th>
                                </thead>
                                <tbody v-for="(item, index) in aspek">
                                    <tr>
                                        <td colspan="6" scope="col"
                                            class="px-1 py-2 bg-primary text-left text-sm leading-6 font-semibold text-white capitalize rounded-t-xl border pl-6">
                                            Aspek {{ item.nama }} </td>
                                    </tr>
                                    <tr v-for="(col,idx) in item.kriteriapenilaian">
                                        <td scope="col"
                                            class="px-1 py-2 text-left text-xs leading-6 font-normal text-gray-900 capitalize rounded-t-xl border">
                                            {{ col.nama }} </td>
                                        <td scope="col" v-for="num in 5"
                                            class="px-1 py-2 text-left text-xs leading-6 font-semibold text-gray-900 capitalize rounded-t-xl border">
                                            <div class="flex justify-center">
                                                <input :id="'radio' + index +'-' + idx" v-model="Penilaian_karyawan[index].kriteria[idx]" type="radio" :value="num"
                                                    :name="'radio' + index +'-' + idx"
                                                    class="w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 " required>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <PrimaryButton type="submit" class="w-full mt-5">
                            <span class="w-full text-center">Simpan</span>
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
