<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations: Record<string, Record<string, string>> = {
    en: {
        createProduct: 'Create Product',
        mage: 'Image',
        name: 'Name',
        dsc: 'Description',
        price: 'Price',
        save: 'Save',
        cancel: 'Cancel',
        success: 'Product created successfully!',
    },
    km: {
        createProduct: 'បង្កើតផលិតផល',
        mage: 'រូបភាព',
        name: 'ឈ្មោះ',
        dsc: 'ការពិពណ៌នា',
        price: 'តម្លៃ',
        save: 'រក្សាទុក',
        cancel: 'បោះបង់',
        success: 'បង្កើតផលិតផលបានជោគជ័យ!',
    },
};
function t(key: string): string {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const form = useForm<{ name: string; dsc: string; price: string; stock: string; mage?: File | string }>({
    name: '',
    dsc: '',
    price: '',
    stock: '',
    mage: '',
});

const imageFile = ref<File | null>(null);
const showSuccess = ref(false);

function onFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        imageFile.value = target.files[0];
    }
}

function submit() {
    const data = new FormData();
    if (imageFile.value) {
        data.append('mage', imageFile.value);
    }
    data.append('name', form.name);
    data.append('dsc', form.dsc);
    data.append('price', form.price);
    data.append('stock', form.stock); // Send stock

    router.post('/products', data, {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            imageFile.value = null;
            showSuccess.value = true;
        },
    });
}
</script>

<template>
    <Head :title="t('createProduct')" />
    <AppLayout>
        <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-8 dark:bg-neutral-950">
            <div class="w-full max-w-lg rounded bg-white p-8 shadow-lg dark:bg-neutral-900">
                <h1 class="mb-8 text-center text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ t('createProduct') }}
                </h1>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            {{ t('mage') }}
                        </label>
                        <input
                            type="file"
                            accept="image/*"
                            @change="onFileChange"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors.mage" />
                    </div>
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
                            {{ t('dsc') }}
                        </label>
                        <input
                            v-model="form.dsc"
                            type="text"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors.dsc" />
                    </div>
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            {{ t('price') }}
                        </label>
                        <input
                            v-model="form.price"
                            type="number"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors.price" />
                    </div>
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            Stock
                        </label>
                        <input
                            v-model="form.stock"
                            type="number"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                        />
                        <InputError :message="form.errors.stock" />
                    </div>
                    <div class="flex justify-end gap-3">
                        <Button class="cursor-pointer" type="submit" :disabled="form.processing">{{ t('save') }}</Button>
                        <Button class="cursor-pointer" type="button" variant="secondary" @click="router.visit('/products')">
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
                        router.visit('/products');
                    "
                >
                    OK
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
