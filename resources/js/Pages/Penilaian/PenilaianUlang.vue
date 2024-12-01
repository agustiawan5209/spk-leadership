<script setup>
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

console.log(props.alternatif);
const Penilaian_karyawan = ref([]);

if (props.aspek.length > 0 && props.kriteria.length > 0) {

    // Jadikan Kriteria ke dalam bentuk array
    const Datakriteria = [...props.aspek];
    for (let i = 0; i < props.alternatif.length; i++) {
        Penilaian_karyawan.value[i] = Datakriteria.reduce((acc, curr, i) => ({
            ...acc, [i]: {
                aspek: curr.id,
                kriteria: [],
                data: curr.kriteriapenilaian,
            }
        }), {});
    }
};
const Form = useForm({
    kategori: props.kategori.id,
    // aspek_id: 1,
    alternatif: props.alternatif,
    tgl_penilaian: dateNow,
    kriteria: [],
})

function BobotPenilaian(index, idx, value) {
    const bobot = value.target.value;

    // Masukkan Bobot Value dari matrix karyawan
    Penilaian_karyawan.value[index].kriteria[idx] = bobot;
}

const emit = defineEmits(['closeModal'])

function submit() {
    Form.kriteria = Penilaian_karyawan.value;
    Form.post(route('Penilaian.storeUlang'), {
        onSuccess: () => {
            emit('closeModal')

        },
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
                        <section v-for="(alternatifs, idxalternatif) in alternatif" :key="index"
                            class="my-10 border-y-2 border-gray-800">
                            <div class="w-full overflow-x-auto">
                                <table>
                                    <tr>
                                        <th scope="col"
                                            class="px-1 py-2 text-left text-xs leading-6 font-semibold text-gray-900 capitalize rounded-t-xl">
                                            Nama Karyawan </th>
                                        <td scope="col"
                                            class="px-1 py-2 text-left text-xs leading-6 text-gray-900 capitalize">
                                            : {{ alternatifs.nama }} </td>

                                    </tr>
                                    <tr>
                                        <th scope="col"
                                            class="px-1 py-2 text-left text-xs leading-6 font-semibold text-gray-900 capitalize">
                                            Departement - Jabatan </th>
                                        <td scope="col"
                                            class="px-1 py-2 text-left text-xs leading-6 text-gray-900 capitalize">
                                            : {{ alternatifs.departement.nama }} </td>
                                    </tr>
                                </table>


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
                                    <tr v-for="(col, idx) in item.kriteriapenilaian">
                                        <td scope="col"
                                            class="px-1 py-2 text-left text-xs leading-6 font-normal text-gray-900 capitalize rounded-t-xl border">
                                            {{ col.nama }} </td>
                                        <td scope="col" v-for="num in 5"
                                            class="px-1 py-2 text-left text-xs leading-6 font-semibold text-gray-900 capitalize rounded-t-xl border">
                                            <div class="flex justify-center">
                                                <input :id="'radio' + idxalternatif + index + '-' + idx"
                                                    v-model="Penilaian_karyawan[idxalternatif][index].kriteria[idx]"
                                                    type="radio" :value="num"
                                                    :name="'radio' + idxalternatif + index + '-' + idx"
                                                    class="w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 "
                                                    required>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                    </div>
                    <PrimaryButton type="submit" class="w-full mt-5">
                        <span class="w-full text-center">Simpan</span>
                    </PrimaryButton>
                </form>
            </div>
        </div>
    </div>
</template>
