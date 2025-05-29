<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Trash2, UserRoundPen, UserRoundPlus } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations = {
    en: {
        adminTitle: 'Students Management',
        adminDesc: 'Manage student users and permissions',
        addAdmin: 'Add Student',
        name: 'Name',
        gender: 'Gender',
        phone: 'Phone',
        email: 'Email',
        generation: 'Generation',
        address: 'Address',
        actions: 'Actions',
        edit: 'Edit',
        delete: 'Delete',
        role: 'Role',
        id: 'ID',
        searchPlaceholder: 'Search students...',
    },
    km: {
        adminTitle: 'ការគ្រប់គ្រងសិស្ស',
        adminDesc: 'គ្រប់គ្រងអ្នកប្រើសិស្ស និងសិទ្ធិ',
        addAdmin: 'បន្ថែមសិស្ស',
        name: 'ឈ្មោះ',
        gender: 'ភេទ',
        phone: 'ទូរស័ព្ទ',
        generation: 'ជំនាន់',
        email: 'អ៊ីមែល',
        address: 'ទីតាំង',
        actions: 'សកម្មភាព',
        edit: 'កែប្រែ',
        delete: 'លុប',
        role: 'តួនាទី',
        id: 'ល.រ',
        searchPlaceholder: 'ស្វែងរកសិស្ស...',
    },
};
function t(key) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const breadcrumbs: BreadcrumbItem[] = [{ title: t('adminTitle'), href: '/students' }];

const page = usePage();
const students = ref(page.props.students || []);
// Search logic
const search = ref('');
const filteredStudents = computed(() => students.value.filter((student) => student.name?.toLowerCase().includes(search.value.toLowerCase())));

function deleteStudent(id) {
    if (window.confirm(t('Are you sure you want to delete this student?'))) {
        router.delete(`/students/${id}`, {
            onSuccess: () => window.location.reload(),
            onError: (error) => {
                console.error('Error deleting student:', error);
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
                    @click="$inertia.visit('/students/create')"
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
                                {{ t('gender') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('phone') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('generation') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('email') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('address') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-700 uppercase dark:text-gray-200">
                                {{ t('actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                        <tr v-for="(student, i) in filteredStudents" :key="student.id" class="transition hover:bg-gray-100 dark:hover:bg-neutral-800">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ i + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ student.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ student.gender }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ student.phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ student.generation }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ student.email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ student.address }}</td>
                            <td class="flex gap-2 px-6 py-4 whitespace-nowrap">
                                <button
                                    @click="$inertia.visit(`/students/${student.id}/edit`)"
                                    class="cursor-pointer rounded bg-blue-100 p-1 text-blue-600 transition hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800"
                                >
                                    <UserRoundPen />
                                </button>
                                <button
                                    @click="deleteStudent(student.id)"
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
