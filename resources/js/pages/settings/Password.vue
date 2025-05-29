<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Password settings',
        href: '/settings/password',
    },
];

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

// 1. Track selected language, load from localStorage if available
const selectedLanguage = ref(localStorage.getItem('language') || 'en');

// 2. Watch for changes and save to localStorage (if you add a language switcher here)
watch(selectedLanguage, (newLang) => {
    localStorage.setItem('language', newLang);
});

// 3. Translation dictionary
const translations = {
    en: {
        title: "Password Settings",
        updatePassword: "Update password",
        ensureSecurity: "Ensure your account is using a long, random password to stay secure",
        currentPassword: "Current password",
        newPassword: "New password",
        confirmPassword: "Confirm password",
        savePassword: "Save password",
        saved: "Saved.",
    },
    km: {
        title: "ការកំណត់ពាក្យសម្ងាត់",
        updatePassword: "ធ្វើបច្ចុប្បន្នភាពពាក្យសម្ងាត់",
        ensureSecurity: "ធ្វើឲ្យប្រាកដថាគណនីរបស់អ្នកកំពុងប្រើពាក្យសម្ងាត់យូរនិងចៃដន្យដើម្បីរក្សាសុវត្ថិភាព",
        currentPassword: "ពាក្យសម្ងាត់បច្ចុប្បន្ន",
        newPassword: "ពាក្យសម្ងាត់ថ្មី",
        confirmPassword: "បញ្ជាក់ពាក្យសម្ងាត់",
        savePassword: "រក្សាទុកពាក្យសម្ងាត់",
        saved: "បានរក្សាទុក។",
    }
};

// 4. Translation function
function t(key: string) {
    return translations[selectedLanguage.value][key] || key;
}

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (errors: any) => {
            if (errors.password) {
                form.reset('password', 'password_confirmation');
                if (passwordInput.value instanceof HTMLInputElement) {
                    passwordInput.value.focus();
                }
            }

            if (errors.current_password) {
                form.reset('current_password');
                if (currentPasswordInput.value instanceof HTMLInputElement) {
                    currentPasswordInput.value.focus();
                }
            }
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="t('title')" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall :title="t('updatePassword')" :description="t('ensureSecurity')" />

                <form @submit.prevent="updatePassword" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="current_password">{{ t('currentPassword') }}</Label>
                        <Input
                            id="current_password"
                            ref="currentPasswordInput"
                            v-model="form.current_password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="current-password"
                            :placeholder="t('currentPassword')"
                        />
                        <InputError :message="form.errors.current_password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">{{ t('newPassword') }}</Label>
                        <Input
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                            :placeholder="t('newPassword')"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">{{ t('confirmPassword') }}</Label>
                        <Input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                            :placeholder="t('confirmPassword')"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">{{ t('savePassword') }}</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">{{ t('saved') }}</p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>