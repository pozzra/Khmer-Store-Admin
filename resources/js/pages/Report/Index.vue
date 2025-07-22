<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Trash2, UserRoundPen, PackageSearch, Sheet, FileText    } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations: Record<string, Record<string, string>> = {
    en: {
        adminTitle: 'Products Management',
        adminDesc: 'Manage Products and permissions',
        addAdmin: 'Add Products',
        iamge: 'Image',
        name: 'Name',
        dsc: 'Dsc',
        price: 'Price',
        actions: 'Actions',
        edit: 'Edit',
        delete: 'Delete',
        role: 'Role',
        id: 'ID',
        searchPlaceholder: 'Search productss...',
        stock: 'Stock',
        deleteError: 'Error deleting products',
        AreYouSure: 'Are you sure you want to delete this products?'
    },
    km: {
        adminTitle: 'ការគ្រប់គ្រងផលិតផល​',
        adminDesc: 'គ្រប់គ្រងផលិតផល​ និងសិទ្ធិ',
        addAdmin: 'បន្ថែមសិស្ស',
        iamge: 'រូបភាព',
        name: 'ឈ្មោះ',
        dsc: 'ការពិពណ៌នា',
        price: 'តម្លៃ',
        actions: 'សកម្មភាព',
        edit: 'កែប្រែ',
        delete: 'លុប',
        role: 'ល.រ',
        id: 'ល.រ',
        searchPlaceholder: 'ស្វែងរកផលិតផល...',
        stock: 'ស្តុក',
        deleteError: 'បញ្ហាក្នុងការលុបផលិតផល',
        AreYouSure: 'តើអ្នកប្រាកដថាចង់លុបផលិតផលនេះឬ?' 
    },
};
function t(key: string): string {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const breadcrumbs: BreadcrumbItem[] = [{ title: t('adminTitle'), href: '/productss' }];

const page = usePage();
const productss = ref<any[]>(Array.isArray(page.props.productss) ? page.props.productss : []);
// Search logic
const search = ref('');
const filteredproductss = computed(() => productss.value.filter((products: any) => products.name?.toLowerCase().includes(search.value.toLowerCase())));

function deleteproducts(id: number) {
    if (window.confirm(t('AreYouSure'))) {
        router.delete(`/products/${id}`, {
            onSuccess: () => window.location.reload(),
            onError: (error) => {
                console.error('Error deleting products:', error);
                alert(t('deleteError'));
            },
        });
    }
}

const newProduct = ref({ mage: '', name: '', dsc: '', price: 0 });

function createProduct() {
  router.post('/products', newProduct.value, {
    onSuccess: () => {
      window.location.reload(); // or update productss.value
    },
    onError: (error) => {
      alert('Error creating product');
      console.error(error);
    }
  });
}

function exportExcel() {
  window.open('/report/export-excel', '_blank');
}
function exportPDF() {
  window.open('/report/export-pdf', '_blank');
}
</script>

<template>
    <Head :title="t('adminTitle')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ t('adminTitle') }}</h1>
                <button
                    @click="router.visit('/products/create')"
                    class="cursor-pointer rounded bg-blue-600 px-3 py-1 text-white transition hover:bg-blue-700 dark:bg-blue-800 dark:hover:bg-blue-700"
                >
                    <PackageSearch  class="inline" />
                    <!-- {{ t('addAdmin') }} -->
                </button>
            </div>
            <div class="mb-4 flex gap-2 items-center justify-between  ">
                <h1></h1>
               <div>
                <button
                    @click="exportExcel"
                    class="rounded bg-green-600 px-3 mr-1 py-1 text-white hover:bg-green-700 cursor-pointer "
                >
                <Sheet />
                </button>
                <button
                    @click="exportPDF"
                    class="rounded bg-red-600 px-3 py-1 text-white hover:bg-red-700 cursor-pointer "
                >
                    <FileText />

                </button>
               </div>
            </div>
            <div class="mb-4">
                <input v-model="search" type="text" :placeholder="t('searchPlaceholder')" class="w-full rounded border p-2 outline-none" />
            </div>
            <div class="overflow-x-auto rounded-lg bg-white shadow ring-1 ring-black/5 dark:bg-neutral-900 dark:ring-white/10">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('id') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('image') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('name') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('dsc') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('price') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('stock') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                        <tr v-for="(products, i) in filteredproductss" :key="products.id" class="transition hover:bg-gray-100 dark:hover:bg-neutral-800">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ i + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                <img :src="`/storage/${products.mage}`" alt="Product Image" class="h-10 w-10 object-cover rounded-full" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ products.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ products.dsc }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ products.price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ products.stock }}</td>
                            <td class="flex gap-2 px-6 py-4 whitespace-nowrap">
                                <button
                                    @click="router.visit(`/products/${products.id}/edit`)"
                                    class="cursor-pointer rounded bg-blue-100 p-1 text-blue-600 transition hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800"
                                >
                                    <UserRoundPen />
                                </button>
                                <button
                                    @click="deleteproducts(products.id)"
                                    class="cursor-pointer rounded bg-red-100 p-1 text-red-600 transition hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800"
                                >
                                    <Trash2 />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
