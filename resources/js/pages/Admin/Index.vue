<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Trash2, UserRoundPen, UserRoundPlus } from 'lucide-vue-next';
// --- Translation logic ---
import { computed, ref } from 'vue';
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations = {
    en: {
        adminTitle: 'Admin Management',
        adminDesc: 'Manage admin users and permissions',
        addAdmin: 'Add Admin',
        name: 'Name',
        email: 'Email',
        actions: 'Actions',
        edit: 'Edit',
        delete: 'Delete',
        role: 'Role',
        id: 'ID',
        searchPlaceholder: 'Search admins...',
    },
    km: {
        adminTitle: 'ការគ្រប់គ្រងអ្នកគ្រប់គ្រង',
        adminDesc: 'គ្រប់គ្រងអ្នកគ្រប់គ្រង និងសិទ្ធិ',
        addAdmin: 'បន្ថែមអ្នកគ្រប់គ្រង',
        name: 'ឈ្មោះ',
        email: 'អ៊ីមែល',
        actions: 'សកម្មភាព',
        edit: 'កែប្រែ',
        delete: 'លុប',
        role: 'តួនាទី',
        id: 'ល.រ',
        searchPlaceholder: 'ស្វែងរកអ្នកគ្រប់គ្រង...',
    },
};
function t(key) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const breadcrumbs: BreadcrumbItem[] = [{ title: t('adminTitle'), href: '/admin' }];

// Get admins from backend (passed as props from Laravel controller)
const page = usePage();
const admins = ref(page.props.admins || []);
// Search logic
const search = ref('');
const filteredAdmins = computed(() => admins.value.filter((admin) => admin.name?.toLowerCase().includes(search.value.toLowerCase())));
function deleteAdmin(id) {
    // add window.confirm for confirmation
    if (window.confirm(t('Are you sure you want to delete this admin?'))) {
        router.delete(`/admin/${id}`, {
            onSuccess: () => {
                // Optionally, you can show a success message or refresh the page
                window.location.reload();
            },
            onError: (error) => {
                console.error('Error deleting admin:', error);
                alert(t('deleteError'));
            },
        });
    }
}
</script>

<template>
    <Head :title="t('adminTitle')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ t('adminTitle') }}</h1>
                <button
                    @click="$inertia.visit('/admin/create')"
                    class="cursor-pointer rounded bg-blue-600 px-3 py-1 text-white transition hover:bg-blue-700 dark:bg-blue-800 dark:hover:bg-blue-700"
                >
                    <UserRoundPlus class="inline" />
                    <!-- {{ t('addAdmin') }} -->
                </button>
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
                                {{ t('name') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('email') }}
                            </th>
                            <!-- <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('password') }}
                            </th> -->
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('role') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                        <tr v-for="(admin, i) in filteredAdmins" :key="admin.id" class="transition hover:bg-gray-100 dark:hover:bg-neutral-800">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ i + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ admin.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ admin.email }}</td>
                            <!-- <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ admin.password }}</td> -->
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    v-if="admin.role === 'admin'"
                                    class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200"
                                >
                                    {{ t('admin') }}
                                </span>
                                <span
                                    v-else-if="admin.role === 'user'"
                                    class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200"
                                >
                                    {{ t('user') }}
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-200"
                                >
                                    {{ admin.role }}
                                </span>
                            </td>
                            <td class="flex gap-2 px-6 py-4 whitespace-nowrap">
                                <button
                                    @click="$inertia.visit(`/admin/${admin.id}/edit`)"
                                    class="cursor-pointer rounded bg-blue-100 p-1 text-blue-600 transition hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800"
                                >
                                    <UserRoundPen />
                                </button>
                                <button
                                    @click="deleteAdmin(admin.id)"
                                    class="rounded bg-red-100 p-1 text-red-600 transition hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 cursor-pointer"
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
