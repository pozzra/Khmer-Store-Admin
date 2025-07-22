<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Trash2, UserRoundPen, PackageSearch  } from 'lucide-vue-next';
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
        id: 'ល.រ',
        searchPlaceholder: 'ស្វែងរកផលិតផល...',
    },
};
function t(key: string) {
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
    if (window.confirm(t('Are you sure you want to delete this products?'))) {
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
</script>

<template>
    <Head :title="t('adminTitle')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ t('adminTitle') }}</h1>

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
                                Stock
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
