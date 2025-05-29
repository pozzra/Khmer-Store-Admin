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
        editStudent: 'Edit Student',
        name: 'Name',
        email: 'Email',
        gender: 'Gender',
        phone: 'Phone',
        generation: 'Generation',
        address: 'Address',
        save: 'Save',
        cancel: 'Cancel',
        selectGender: 'Select gender',
        male: 'Male',
        female: 'Female',
        success: 'Student updated successfully!',
    },
    km: {
        editStudent: 'កែប្រែសិស្ស',
        name: 'ឈ្មោះ',
        email: 'អ៊ីមែល',
        gender: 'ភេទ',
        phone: 'ទូរស័ព្ទ',
        generation: 'ជំនាន់',
        address: 'ទីតាំង',
        save: 'រក្សាទុក',
        cancel: 'បោះបង់',
        selectGender: 'ជ្រើសរើសភេទ',
        male: 'ប្រុស',
        female: 'ស្រី',
        success: 'បានកែប្រែសិស្សដោយជោគជ័យ!',
    },
};
function t(key) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

// Get the student data from the backend (passed as props)
const page = usePage();
const student = page.props.student;

// Dialog state
const showSuccess = ref(false);

// Initialize form with student data
const form = useForm({
    name: student?.name || '',
    email: student?.email || '',
    gender: student?.gender || '',
    phone: student?.phone || '',
    generation: student?.generation || '',
    address: student?.address || '',
});

function submit() {
    form.put(`/students/${student.id}`, {
        onSuccess: () => {
            showSuccess.value = true;
            setTimeout(() => {
                showSuccess.value = false;
            }, 3000);
        },
    });
}
</script>

<template>
    <Head :title="t('editStudent')" />
    <AppLayout>
        <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-8 dark:bg-neutral-950">
            <div class="w-full max-w-lg rounded bg-white p-8 shadow-lg dark:bg-neutral-900">
                <h1 class="mb-8 text-center text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ t('editStudent') }}
                </h1>
                <form @submit.prevent="submit" class="cursor-pointer space-y-6">
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
                            {{ t('gender') }}
                        </label>
                        <select
                            v-model="form.gender"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                        >
                            <option value="" disabled>{{ t('selectGender') }}</option>
                            <option value="male">{{ t('male') }}</option>
                            <option value="female">{{ t('female') }}</option>
                        </select>
                        <InputError :message="form.errors.gender" />
                    </div>
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            {{ t('phone') }}
                        </label>
                        <input
                            v-model="form.phone"
                            type="text"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors.phone" />
                    </div>
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            {{ t('generation') }}
                        </label>
                        <input
                            v-model="form.generation"
                            type="text"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors.generation" />
                    </div>
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            {{ t('address') }}
                        </label>
                        <input
                            v-model="form.address"
                            type="text"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors.address" />
                    </div>
                    <div class="flex justify-end gap-3">
                        <Button class="cursor-pointer" type="submit" :disabled="form.processing">{{ t('save') }}</Button>
                        <Button class="cursor-pointer" type="button" variant="secondary" @click="$inertia.visit('/students')">
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
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
