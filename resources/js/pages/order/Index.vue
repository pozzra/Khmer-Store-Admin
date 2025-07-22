<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Trash2, UserRoundPen, ShoppingCart } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations: Record<string, Record<string, string>> = {
    en: {
        orderTitle: 'Order Management',
        id: 'ID',
        username: 'Username',
        phone: 'Phone',
        cart: 'Cart',
        actions: 'Actions',
        createOrder: 'Create Order',
        submit: 'Submit',
        userOrder: 'Place an Order',
    },
    km: {
        orderTitle: 'ការគ្រប់គ្រងការបញ្ជាទិញ',
        id: 'ល.រ',
        username: 'ឈ្មោះ',
        phone: 'ទូរស័ព្ទ',
        cart: 'កន្ត្រក',
        actions: 'សកម្មភាព',
        createOrder: 'បង្កើតការបញ្ជាទិញ',
        submit: 'ដាក់បញ្ជាទិញ',
        userOrder: 'បញ្ជាទិញ',
    },
};
function t(key: string): string {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const breadcrumbs: BreadcrumbItem[] = [{ title: t('orderTitle'), href: '/order' }];

const page = usePage();
const orders = ref<any[]>(Array.isArray(page.props.orders) ? page.props.orders : []);

// User order form (frontend)
const userOrderForm = ref({
    username: '',
    phone: '',
    cart: '', // JSON string
});
const userOrderSuccess = ref('');
const userOrderError = ref('');

async function submitUserOrder() {
    userOrderSuccess.value = '';
    userOrderError.value = '';
    try {
        const response = await fetch('/api/orders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                username: userOrderForm.value.username,
                phone: userOrderForm.value.phone,
                cart: JSON.parse(userOrderForm.value.cart),
            }),
        });
        const data = await response.json();
        if (data.success) {
            userOrderSuccess.value = 'Order placed successfully!';
            userOrderForm.value.username = '';
            userOrderForm.value.phone = '';
            userOrderForm.value.cart = '';
        } else {
            userOrderError.value = data.message || 'Order failed.';
        }
    } catch (e) {
        userOrderError.value = 'Invalid cart JSON or network error.';
    }
}

function parseCart(cart: string) {
  try {
    return JSON.parse(cart);
  } catch {
    return [];
  }
}

// Add deleteOrder method
function deleteOrder(id: number) {
    if (window.confirm('Are you sure you want to delete this order?')) {
        router.delete(`/order/${id}`, {
            onSuccess: () => window.location.reload(),
        });
    }
}
</script>

<template>
    <Head :title="t('orderTitle')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
           

            <!-- Admin order table -->
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ t('orderTitle') }}</h1>
                <button
                    @click="router.visit('/order/create')"
                    class="cursor-pointer rounded bg-blue-600 px-3 py-1 text-white transition hover:bg-blue-700 dark:bg-blue-800 dark:hover:bg-blue-700"
                >
                    <ShoppingCart class="inline" />
                    {{ t('createOrder') }}
                </button>
            </div>
            <div class="overflow-x-auto rounded-lg bg-white shadow ring-1 ring-black/5 dark:bg-neutral-900 dark:ring-white/10">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('id') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('username') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('phone') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('cart') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                        <tr v-for="order in orders" :key="order.id" class="transition hover:bg-gray-100 dark:hover:bg-neutral-800">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ order.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ order.username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ order.phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                <ul>
                                    <li v-for="item in parseCart(order.cart)" :key="item.name">
                                        {{ item.name }} x{{ item.qty }} = {{ item.price }}
                                    </li>
                                </ul>
                            </td>
                            <td class="flex gap-2 px-6 py-4 whitespace-nowrap">
                                <button
                                    @click="router.visit(`/order/${order.id}/edit`)"
                                    class="cursor-pointer rounded bg-blue-100 p-1 text-blue-600 transition hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800"
                                >
                                    <UserRoundPen />
                                </button>
                                <button
                                    @click="deleteOrder(order.id)"
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
