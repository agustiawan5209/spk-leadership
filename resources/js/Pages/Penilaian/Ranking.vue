<script setup>
import { defineProps } from 'vue';
const props = defineProps(['rank'])

console.log(props.rank)
</script>

<template>
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
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
