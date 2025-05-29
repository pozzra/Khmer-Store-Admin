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
        createStudent: 'Create Student',
        name: 'Name',
        gender: 'Gender',
        phone: 'Phone',
        generation: 'Generation',
        email: 'Email',
        address: 'Address',
        save: 'Save',
        cancel: 'Cancel',
        success: 'Student created successfully!',
        selectGender: 'Select gender',
        male: 'Male',
        female: 'Female',
    },
    km: {
        createStudent: 'បង្កើតសិស្ស',
        name: 'ឈ្មោះ',
        gender: 'ភេទ',
        phone: 'ទូរស័ព្ទ',
        generation: 'ជំនាន់',
        email: 'អ៊ីមែល',
        address: 'ទីតាំង',
        save: 'រក្សាទុក',
        cancel: 'បោះបង់',
        success: 'បង្កើតសិស្សបានជោគជ័យ!',
        selectGender: 'ជ្រើសរើសភេទ',
        male: 'ប្រុស',
        female: 'ស្រី',
    },
};
function t(key) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const form = useForm({
    name: '',
    gender: '',
    phone: '',
    generation: '',
    email: '',
    address: '',
});

const showSuccess = ref(false);

function submit() {
    form.post('/students', {
        onSuccess: () => {
            form.reset();
            showSuccess.value = true;
        },
    });
}
</script>

<template>
    <Head :title="t('createStudent')" />
    <AppLayout>
        <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-8 dark:bg-neutral-950">
            <div class="w-full max-w-lg rounded bg-white p-8 shadow-lg dark:bg-neutral-900">
                <h1 class="mb-8 text-center text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ t('createStudent') }}
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
                            {{ t('email') }}
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors.email" />
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
                        $inertia.visit('/students');
                    "
                >
                    OK
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
