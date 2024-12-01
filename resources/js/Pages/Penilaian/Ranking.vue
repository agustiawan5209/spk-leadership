<script setup>
import { defineProps, ref } from 'vue';
import PenilaianUlang from './PenilaianUlang.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';

const props = defineProps(['rank' , 'kategori'])

const ranking = [...props.rank]
const findDuplicate = (index, nilai)=>{
    return ranking.some((obj, idx)=> obj.hasil === nilai && idx !== index);
}
// Fungsi untuk mengecek jika terdapat hasil dengan nilai yang sama
const hasDuplicate = ranking.some((obj, index) =>
  ranking.findIndex(item => item.hasil === obj.hasil) !== index
);

const grouped = ranking.reduce((acc, obj) => {
  acc[obj.hasil] = acc[obj.hasil] || [];
  acc[obj.hasil].push(obj.data);
  return acc;
}, {});

// Mengambil hanya grup yang memiliki lebih dari 1 anggota
const alternatifDuplicate = Object.values(grouped).filter(group => group.length > 1);

console.log(alternatifDuplicate)
const showModal = ref(false);

const penilaian = ref([]);
const alternatif = ref([]);
const aspek = ref([]);
const kriteria = ref([]);
const aspek_kriteria = ref([]);
const OpenModal = async ()=>{
    const response = await axios.get(route('api.staff.penilaian', {kategori: props.kategori.id}))
    if(response.status == 200){
        const resData = response.data;
        penilaian.value  = resData.penilaian;
        aspek.value  = resData.aspek;
        kriteria.value  = resData.kriteria;
        aspek_kriteria.value  = resData.aspek_kriteria;

        showModal.value = true;
    }
}

const closeModal = ()=>{
    showModal.value = false;
}

</script>

<template>

    <Modal :show="showModal" >
        <div class="relative w-max">
            <PrimaryButton class="w-full bg-red-500" @click="closeModal" type="button">Tutup</PrimaryButton>
        </div>

        <PenilaianUlang @closeModal="closeModal" :alternatif="alternatifDuplicate[0]" :kategori="kategori" :aspek="aspek" :kriteria="kriteria" :aspek_kriteria="aspek_kriteria" />
    </Modal>
    <div class="col-span-full overflow-x-auto mt-3">
        <div class=" p-2 rounded-md list-item space-y-10">
            <table class="bg-white w-full text-xs text-left rtl:text-right text-gray-500" >
                <caption class="text-lg text-black">
                    <span>Hasil Penilaian Aspek: </span>
                    <!-- <p>Nilai Pada Factory Dihitung dari jumlah factory yang sama lalu dilakukan dengan</p> -->
                </caption>
                <thead class="text-xs text-white uppercase bg-primary ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Rank
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Departement
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Karyawan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Point
                        </th>
                        <th v-if="hasDuplicate" scope="col" class="px-6 py-3">
                            Penilaian Tambahan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b" v-for="(item, index) in rank"
                        :key="index">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-sm capitalize text-gray-900 whitespace-nowrap">
                            {{ index + 1}}
                        </th>
                        <th scope="row"
                            class="px-6 py-4 font-medium text-sm capitalize text-gray-900 whitespace-nowrap">
                            {{ item.data.nama_departement }}
                        </th>
                        <td class="px-6 py-4 text-sm">
                            {{ item.data.nama }}
                        </td>
                        <td class="px-6 py-4">
                            {{ item.data.jabatan }}
                        </td>
                        <td class="px-6 py-4">
                            {{ item.hasil }}
                        </td>
                        <td class="px-6 py-4" v-if="hasDuplicate">
                            <span v-if="findDuplicate(index, item.hasil)">
                                <PrimaryButton type="button" @click="OpenModal">Tambah Penilaian</PrimaryButton>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
