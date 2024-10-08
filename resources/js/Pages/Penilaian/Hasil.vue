<script setup>
import { defineProps } from 'vue';
const props = defineProps(['hasil', 'aspek'])



console.log(props.aspek)
</script>

<template>
    <div class="col-span-full overflow-x-auto mt-3">
        <div class=" p-2 rounded-md list-item space-y-10">
            <table class="bg-white w-full text-xs text-left rtl:text-right text-gray-500" v-for="(col,idx) in hasil">
                <caption class="text-lg text-black">
                    <span>Hasil Penilaian Aspek: {{ idx }}</span>
                    <!-- <p>Nilai Pada Factory Dihitung dari jumlah factory yang sama lalu dilakukan dengan</p> -->
                </caption>
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
                        <th scope="col" class="px-6 py-3">
                            Point
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b" v-for="(item, index) in col"
                        :key="index">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-sm capitalize text-gray-900 whitespace-nowrap">
                            {{ item.staff.nama_departement }}
                        </th>
                        <td class="px-6 py-4 text-sm">
                            {{ item.staff.nama }}
                        </td>
                        <td class="px-6 py-4">
                            {{ item.staff.jabatan }}
                        </td>
                        <td class="px-6 py-4">
                            {{ item.hasil }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
