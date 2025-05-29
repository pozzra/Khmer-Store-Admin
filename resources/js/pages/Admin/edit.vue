<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations = {
    en: {
        editAdmin: 'Edit Admin',
        name: 'Name',
        email: 'Email',
        password: 'Password',
        role: 'Role',
        save: 'Save',
        cancel: 'Cancel',
        selectRole: 'Select role',
        admin: 'Admin',
        user: 'User',
        success: 'Admin updated successfully!',
    },
    km: {
        editAdmin: 'កែប្រែអ្នកគ្រប់គ្រង',
        name: 'ឈ្មោះ',
        email: 'អ៊ីមែល',
        password: 'ពាក្យសម្ងាត់',
        role: 'តួនាទី',
        save: 'រក្សាទុក',
        cancel: 'បោះបង់',
        selectRole: 'ជ្រើសរើសតួនាទី',
        admin: 'អ្នកគ្រប់គ្រង',
        user: 'អ្នកប្រើប្រាស់',
        success: 'បានកែប្រែអ្នកគ្រប់គ្រងដោយជោគជ័យ!',
    },
};
function t(key) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

// Get the admin data from the backend (passed as props)
const page = usePage();
const admin = page.props.admin;

// Dialog state
const showSuccess = ref(false);

// Initialize form with admin data
const form = useForm({
    name: admin?.name || '',
    email: admin?.email || '',
    password: '',
    role: admin?.role || 'admin',
});

function submit() {
    form.put(`/admin/${admin.id}`, {
        onSuccess: () => {
            form.reset('password');
            showSuccess.value = true;
            setTimeout(() => {
                showSuccess.value = false;
            }, 3000);
        },
    });
}
</script>

<template>
    <Head :title="t('editAdmin')" />
    <AppLayout>
        <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-8 dark:bg-neutral-950">
            <div class="w-full max-w-lg rounded bg-white p-8 shadow-lg dark:bg-neutral-900">
                <h1 class="mb-8 text-center text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ t('editAdmin') }}
                </h1>
                <form @submit.prevent="submit" class="space-y-6 cursor-pointer">
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
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />
                        <InputError :message="form.errors.password" />
                        <div class="mt-1 text-xs text-gray-400">{{ t('password') }} (leave blank to keep current)</div>
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
                    <div class="flex justify-end gap-3 ">
                        <Button class="cursor-pointer" type="submit" :disabled="form.processing">{{ t('save') }}</Button>
                        <Button class="cursor-pointer" type="button" variant="secondary" @click="$inertia.visit('/admin')">
                            {{ t('cancel') }}
                        </Button>
                    </div>
                </form>
                <transition name="fade">
                    <div v-if="showSuccess" class="mt-4 rounded bg-green-100 p-4 text-green-700" role="alert">
                        {{ t('success') }}
                    </div>
                </transition>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
}
</style>
