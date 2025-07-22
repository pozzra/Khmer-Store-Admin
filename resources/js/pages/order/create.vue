<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    username: '',
    phone: '',
    cart: '', // JSON string
});

function submit() {
    router.post('/order', form.data(), {
        onSuccess: () => {
            form.reset();
            router.visit('/order', { replace: true });
        },
    });
}
</script>

<template>
    <Head title="Create Order" />
    <AppLayout>
        <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-8 dark:bg-neutral-950">
            <div class="w-full max-w-lg rounded bg-white p-8 shadow-lg dark:bg-neutral-900">
                <h1 class="mb-8 text-center text-3xl font-bold text-gray-900 dark:text-gray-100">
                    Create Order
                </h1>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            Username
                        </label>
                        <input
                            v-model="form.username"
                            type="text"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                            autocomplete="off"
                        />
                        <InputError :message="form.errors.username" />
                    </div>
                    <div>
                        <label class="mb-1 block font-medium text-gray-700 dark:text-gray-200">
                            Phone
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
                            Cart (JSON)
                        </label>
                        <textarea
                            v-model="form.cart"
                            class="w-full rounded border bg-gray-50 p-2 text-gray-900 dark:bg-neutral-800 dark:text-gray-100"
                            required
                            rows="4"
                            placeholder='[{"name":"Product A","qty":1,"price":10}]'
                        />
                        <InputError :message="form.errors.cart" />
                    </div>
                    <div class="flex justify-end gap-3">
                        <Button class="cursor-pointer" type="submit" :disabled="form.processing">Save</Button>
                        <Button class="cursor-pointer" type="button" variant="secondary" @click="router.visit('/order')">
                            Cancel
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
