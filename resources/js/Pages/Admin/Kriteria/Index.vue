<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { ref, watch, defineProps, onMounted, inject } from 'vue';
import axios from 'axios';
import dropdownTable from '@/Components/dropdownTable.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
const swal = inject('$swal')

const page = usePage()

onMounted(() => {
    if (page.props.message !== null) {
        swal({
            icon: "info",
            title: 'Berhasil',
            text: page.props.message,
            showConfirmButton: true,
            timer: 2000
        });
    }
})
function messageDisplay(message, icon) {
    swal({
        icon: icon,
        title: 'Perhatian!!',
        text: message,
        showConfirmButton: true,
        timer: 2000
    });
}
const props = defineProps({
    search: {
        type: String,
        default: '',
    },
    order: {
        type: String,
        default: '',
    },
    aspek_id: {
        type: String,
        default: '',
    },
    aspek: {
        type: Object,
        default: () => ({}),
    },
    kriteria: {
        type: Object,
        default: () => ({}),
    },
    table_colums: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
})

const Form = useForm({
    search: props.search,
    aspek_id: props.aspek_id,
    order: props.order,
})

const search = ref(props.search)
const order = ref(props.order)
const aspek_id = ref(props.aspek_id)

// Filter By

watch(search, (value) => {
    Form.search = value
    Form.get(route('Kriteria.index'), {
        preserveScroll: true,
        preserveState: true,
    })
})
watch(order, (value) => {
    Form.order = value
    Form.get(route('Kriteria.index'), {
        preserveScroll: true,
        preserveState: true,
    })
})
watch(aspek_id, (value) => {
    Form.aspek_id = value
    Form.get(route('Kriteria.index'), {
        preserveScroll: true,
        preserveState: true,
    })
})

function resetFilter() {
    search.value = '';
    aspek_id.value = '';
}
//
const crud = ref({
    tambah: props.can.add,
    edit: props.can.edit,
    show: props.can.show,
    delete: props.can.delete,
    reset_password: props.can.reset,

})



// Modal Delete
const VarDeleteModal = ref(false);
const DeleteForm = useForm({
    slug: null,
    title: null,
})

function showDeleteModal(item) {

    DeleteForm.slug = item.id;
    DeleteForm.title = item.nama ?? item.name;
    VarDeleteModal.value = true;
}

function deleteItem() {
    DeleteForm.delete(route('kriteria.destroy'), {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            VarDeleteModal.value = false;
            DeleteForm.reset('slug');
            swal({
                icon: "success",
                title: 'Berhasil',
                text: page.props.message,
                showConfirmButton: true,
                timer: 2000
            });
        }
    })
}
</script>

<template>

    <Head title="kriteria" />
    <Modal :show="VarDeleteModal">
        <div class="flex flex-col max-w-full gap-2 p-6 rounded-md shadow-md bg-gray-50 text-gray-800">
            <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight tracking-wide">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                    class="w-6 h-6 fill-current shrink-0 text-violet-600">
                    <path
                        d="M451.671,348.569,408,267.945V184c0-83.813-68.187-152-152-152S104,100.187,104,184v83.945L60.329,348.568A24,24,0,0,0,81.432,384h86.944c-.241,2.636-.376,5.3-.376,8a88,88,0,0,0,176,0c0-2.7-.135-5.364-.376-8h86.944a24,24,0,0,0,21.1-35.431ZM312,392a56,56,0,1,1-111.418-8H311.418A55.85,55.85,0,0,1,312,392ZM94.863,352,136,276.055V184a120,120,0,0,1,240,0v92.055L417.137,352Z">
                    </path>
                    <rect width="32" height="136" x="240" y="112"></rect>
                    <rect width="32" height="32" x="240" y="280"></rect>
                </svg>
                <span>Apakah anda yakin ingin menghapus {{ DeleteForm.title }}</span>
            </h2>
            <!-- <p class="flex-1 text-gray-600">Mauris et lorem at elit tristique dignissim et ullamcorper elit. In sed feugiat mi. Etiam ut lacinia dui.</p> -->
            <div class="flex flex-col justify-end gap-3 mt-6 sm:flex-row">
                <button class="px-6 py-2 rounded-sm" @click="VarDeleteModal = false">Tidak</button>
                <button class="px-6 py-2 rounded-sm shadow-sm bg-violet-600 text-gray-50"
                    @click="deleteItem()">Ya</button>
            </div>
        </div>
    </Modal>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">Data kriteria</h2>
        </template>

        <div class="py-4 relative box-content">
            <div class="w-full overflow-hidden rounded-lg shadow-xs divide-y divide-gray-700 bg-white">
                <div class="py-3 px-4" v-if="crud.tambah">
                    <div class="relative max-w-xs">
                        <Link :href="route('Kriteria.create')">
                        <PrimaryButton type="button">Tambah Data</PrimaryButton>
                        </Link>
                    </div>
                </div>
                <div class="py-3 px-4 flex justify-between">
                    <div class="flex gap-4">
                        <div class="relative max-w-xs">
                            <label class="sr-only">Search</label>
                            <input type="search" name="hs-table-with-pagination-search"
                                id="hs-table-with-pagination-search" v-model="search"
                                class="pl-2 py-1 md:pl-8 md:py-2 ps-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                placeholder="Cari Data.........">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.3-4.3"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative max-w-xs">
                            <label class="mr-1">Aspek Kriteria</label>
                            <select id="order" v-model="aspek_id"
                                class="px-2 py-1 md:px-3 md:py-2 placeholder-gray-400 border focus:outline-none sm:w-40 sm:text-sm border-gray-200 shadow-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none ">
                                <option value="">-----</option>
                                <option v-for="item in aspek" :value="item.id">{{ item.nama }}</option>
                            </select>
                        </div>
                        <div class="relative max-w-xs">
                            <button type="reset" @click="resetFilter()"
                                class="py-1 md:py-2 border px-2 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">Reset</button>
                        </div>
                    </div>
                    <div class="relative max-w-xs flex items-center gap-2">
                        <label class="capitalize text-xs md:text-sm">Urutkan</label>

                        <select id="order" v-model="order"
                            class="px-2 py-1 md:px-3 md:py-2 placeholder-gray-400 border focus:outline-none sm:w-40 sm:text-sm border-gray-200 shadow-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none ">
                            <option value="">-----</option>
                            <option value="desc">Terbaru</option>
                            <option value="asc">Terlama</option>
                        </select>
                        <div class="absolute inset-y-0 end-3 flex items-center pointer-events-none ps-3">
                            <font-awesome-icon :icon="['fas', 'filter']" class="text-gray-400" />
                        </div>
                    </div>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left uppercase border-b border-gray-700  text-gray-900 bg-white">
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Nama Aspek</th>
                                <th class="px-4 py-3">Nama Kriteria</th>
                                <th class="px-4 py-3">Persentase</th>
                                <th class="px-4 py-3">Bobot Ideal</th>
                                <th class="px-4 py-3">Factory</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class=" divide-y divide-gray-700 bg-white" v-if="kriteria.data.length"
                            :class="{ 'opacity-75 blur-sm': Form.processing }">
                            <tr class="text-gray-900" v-for="(item, index) in kriteria.data">
                                <td class="px-4 py-3 border-x border-gray-600">
                                    {{ (kriteria.current_page - 1) * kriteria.per_page + index + 1 }}
                                </td>
                                <td class="px-4 py-3 border-x border-gray-600 text-sm">
                                    {{ item.nama_aspek }}

                                </td>
                                <td class="px-4 py-3 border-x border-gray-600 text-sm">
                                    {{ item.nama }}
                                </td>
                                <td class="px-4 py-3 border-x border-gray-600 text-sm">
                                    {{ item.persentase }}
                                </td>
                                <td class="px-4 py-3 border-x border-gray-600 text-sm">
                                    {{ item.nilai_target }}
                                </td>
                                <td class="px-4 py-3 border-x border-gray-600 text-sm">
                                    {{ item.factory }}
                                </td>
                                <td class="px-4 py-3 border-x border-gray-600 text-sm">
                                    <div class="ml-3 relative z-50 flex flex-row gap-2"
                                        v-if="crud.edit || crud.show || crud.delete || crud.reset_password">
                                        <Link v-if="crud.edit" :href="route('Kriteria.edit', { slug: item.id })"
                                            class="flex justify-start ">
                                        <font-awesome-icon class="text-base text-green-500 hover:text-green-700"
                                            :icon="['fas', 'pen-to-square']" />
                                        </Link>

                                        <Link v-if="crud.show" :href="route('Kriteria.show', { slug: item.id })"
                                            class="flex justify-start ">
                                        <font-awesome-icon class="text-base text-blue-500 hover:text-blue-700"
                                            :icon="['fas', 'eye']" />
                                        </Link>
                                        <button type="button" v-if="crud.delete" @click="showDeleteModal(item)"
                                            class="flex justify-start">
                                            <font-awesome-icon class="text-base text-red-500 hover:text-red-700"
                                                :icon="['fas', 'trash-can']" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="divide-y divide-gray-700 bg-gray-200" v-else>
                            <tr>
                                <td colspan="7" class="p-5 text-gray-900 text-center">Data Kosong</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="py-1 px-4 ">
                    <div class="flex flex-wrap">
                        <template v-for="(link, key) in kriteria.links">
                            <div v-if="link.url === null" :key="key"
                                class="mb-1 mr-1 px-4 py-3 text-gray-900 text-sm leading-4 border rounded"
                                v-html="link.label" />
                            <Link v-else :key="`link-${key}`"
                                class="mb-1 mr-1 px-4 py-3 focus:text-indigo-500 text-sm leading-4 active:border-blue-400 hover:bg-gray-200 border focus:border-indigo-500 rounded"
                                :class="{ 'bg-white border-blue-500 text-black': link.active }" preserve-state
                                preserve-scroll :data="{ search, order }" :href="link.url" v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
