<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations = {
    en: {
        createAdmin: 'Create Admin',
        name: 'Name',
        email: 'Email',
        password: 'Password',
        role: 'Role',
        save: 'Save',
        cancel: 'Cancel',
        selectRole: 'Select role',
        admin: 'Admin',
        user: 'User',
        success: 'Admin created successfully!',
    },
    km: {
        createAdmin: 'បង្កើតអ្នកគ្រប់គ្រង',
        name: 'ឈ្មោះ',
        email: 'អ៊ីមែល',
        password: 'ពាក្យសម្ងាត់',
        role: 'តួនាទី',
        save: 'រក្សាទុក',
        cancel: 'បោះបង់',
        selectRole: 'ជ្រើសរើសតួនាទី',
        admin: 'អ្នកគ្រប់គ្រង',
        user: 'អ្នកប្រើប្រាស់',
        success: 'បង្កើតអ្នកគ្រប់គ្រងបានជោគជ័យ!',
    },
};
function t(key) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'admin', // default role
});

const showSuccess = ref(false);

function submit() {
    form.post('/admin', {
        onSuccess: () => {
            form.reset();
            showSuccess.value = true;
        },
    });
}
</script>

<template>
    <Head :title="t('createAdmin')" />
    <AppLayout>
        <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-8 dark:bg-neutral-950">
            <div class="w-full max-w-lg rounded bg-white p-8 shadow-lg dark:bg-neutral-900">
                <h1 class="mb-8 text-center text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ t('createAdmin') }}
                </h1>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            {{ t('name') }}
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                            autocomplete="off"
                        />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            {{ t('email') }}
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                            autocomplete="off"
                        />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            {{ t('password') }}
                        </label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                            autocomplete="new-password"
                        />
                        <InputError :message="form.errors.password" />
                    </div>
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            {{ t('role') }}
                        </label>
                        <select
                            v-model="form.role"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                        >
                            <option value="" disabled>{{ t('selectRole') }}</option>
                            <option value="admin">{{ t('admin') }}</option>
                            <option value="user">{{ t('user') }}</option>
                        </select>
                        <InputError :message="form.errors.role" />
                    </div>
                    <div class="flex justify-end gap-3">
                        <Button class="cursor-pointer" type="submit" :disabled="form.processing">{{ t('save') }}</Button>
                        <Button class="cursor-pointer" type="button" variant="secondary" @click="$inertia.visit('/admin')">
                            {{ t('cancel') }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Success Dialog -->
        <div v-if="showSuccess" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-xs rounded-lg bg-white p-8 text-center shadow-lg dark:bg-neutral-900">
                <div class="mb-2 text-3xl text-green-600 dark:text-green-400">✔</div>
                <div class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ t('success') }}</div>
                <Button
                    @click="
                        showSuccess = false;
                        $inertia.visit('/admin');
                    "
                >
                    OK
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
