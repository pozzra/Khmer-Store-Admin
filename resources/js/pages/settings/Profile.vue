<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';
import { ref } from 'vue';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations = {
    en: {
        profileSettings: 'Profile settings',
        profileInformation: 'Profile information',
        updateProfile: 'Update your name and email address',
        name: 'Name',
        email: 'Email address',
        fullName: 'Full name',
        save: 'Save',
        saved: 'Saved.',
        unverified: 'Your email address is unverified.',
        resend: 'Click here to resend the verification email.',
        verificationSent: 'A new verification link has been sent to your email address.',
    },
    km: {
        profileSettings: 'ការកំណត់ប្រវត្តិរូប',
        profileInformation: 'ព័ត៌មានប្រវត្តិរូប',
        updateProfile: 'ធ្វើបច្ចុប្បន្នភាពឈ្មោះ និងអ៊ីមែលរបស់អ្នក',
        name: 'ឈ្មោះ',
        email: 'អាសយដ្ឋានអ៊ីមែល',
        fullName: 'ឈ្មោះពេញ',
        save: 'រក្សាទុក',
        saved: 'បានរក្សាទុក។',
        unverified: 'អាសយដ្ឋានអ៊ីមែលរបស់អ្នកមិនទាន់បានផ្ទៀងផ្ទាត់ទេ។',
        resend: 'ចុចទីនេះដើម្បីផ្ញើអ៊ីមែលផ្ទៀងផ្ទាត់ម្តងទៀត។',
        verificationSent: 'តំណភ្ជាប់ផ្ទៀងផ្ទាត់ថ្មីត្រូវបានផ្ញើទៅអាសយដ្ឋានអ៊ីមែលរបស់អ្នក។',
    },
};
function t(key: string) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('profileSettings'),
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="t('profileSettings')" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall :title="t('profileInformation')" :description="t('updateProfile')" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">{{ t('name') }}</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" :placeholder="t('fullName')" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">{{ t('email') }}</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            :placeholder="t('email')"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="text-muted-foreground -mt-4 text-sm">
                            {{ t('unverified') }}
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                {{ t('resend') }}
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            {{ t('verificationSent') }}
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">{{ t('save') }}</Button>

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

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
