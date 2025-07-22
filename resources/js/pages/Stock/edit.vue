<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations: Record<string, Record<string, string>> = {
    en: {
        editProduct: 'Edit Product',
        mage: 'Image',
        name: 'Name',
        dsc: 'Description',
        price: 'Price',
        save: 'Save',
        cancel: 'Cancel',
        success: 'Product updated successfully!',
    },
    km: {
        editProduct: 'កែប្រែផលិតផល',
        mage: 'រូបភាព',
        name: 'ឈ្មោះ',
        dsc: 'ការពិពណ៌នា',
        price: 'តម្លៃ',
        save: 'រក្សាទុក',
        cancel: 'បោះបង់',
        success: 'កែប្រែផលិតផលបានជោគជ័យ!',
    },
};
function t(key: string): string {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const page = usePage();
const product = page.props.product;

const showSuccess = ref(false);

const form = useForm({
    name: product?.name || '',
    dsc: product?.dsc || '',
    price: product?.price || '',
    stock: product?.stock || '', // Add stock field
});

const imageFile = ref<File | null>(null);

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
    data.append('_method', 'PUT'); // <-- Add this line

    router.post(`/products/${product.id}`, data, {
        forceFormData: true,
        onSuccess: () => {
            showSuccess.value = true;
            setTimeout(() => {
                showSuccess.value = false;
                router.visit('/products');
            }, 1500);
        },
    });
}
</script>

<template>
    <Head :title="t('editProduct')" />
    <AppLayout>
        <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-8 dark:bg-neutral-950">
            <div class="w-full max-w-lg rounded bg-white p-8 shadow-lg dark:bg-neutral-900">
                <h1 class="mb-8 text-center text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ t('editProduct') }}
                </h1>
                <form @submit.prevent="submit" class="cursor-pointer space-y-6">
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            {{ t('mage') }}
                        </label>
                        <div v-if="product.mage" class="mb-2">
                            <img :src="`/storage/${product.mage}`" alt="Current Image" class="h-16 w-16 object-cover rounded-full" />
                        </div>
                        <input
                            type="file"
                            accept="image/*"
                            @change="onFileChange"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
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
