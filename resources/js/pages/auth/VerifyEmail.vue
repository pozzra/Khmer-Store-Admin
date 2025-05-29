<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

// --- Translation logic ---
import { ref } from 'vue';
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations = {
    en: {
        verifyTitle: "Verify email",
        verifyDesc: "Please verify your email address by clicking on the link we just emailed to you.",
        resent: "A new verification link has been sent to the email address you provided during registration.",
        resend: "Resend verification email",
        logout: "Log out",
        emailVerification: "Email verification",
    },
    km: {
        verifyTitle: "បញ្ជាក់អ៊ីមែល",
        verifyDesc: "សូមបញ្ជាក់អាសយដ្ឋានអ៊ីមែលរបស់អ្នកដោយចុចលើតំណភ្ជាប់ដែលយើងបានផ្ញើទៅអ៊ីមែលរបស់អ្នក។",
        resent: "តំណភ្ជាប់បញ្ជាក់ថ្មីត្រូវបានផ្ញើទៅអាសយដ្ឋានអ៊ីមែលដែលអ្នកបានផ្តល់នៅពេលចុះឈ្មោះ។",
        resend: "ផ្ញើអ៊ីមែលបញ្ជាក់ម្ដងទៀត",
        logout: "ចាកចេញ",
        emailVerification: "ការបញ្ជាក់អ៊ីមែល",
    }
};
function t(key: string) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};
</script>

<template>
    <AuthLayout :title="t('verifyTitle')" :description="t('verifyDesc')">
        <Head :title="t('emailVerification')" />

        <div v-if="status === 'verification-link-sent'" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ t('resent') }}
        </div>

        <form @submit.prevent="submit" class="space-y-6 text-center">
            <Button :disabled="form.processing" variant="secondary">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                {{ t('resend') }}
            </Button>

            <TextLink :href="route('logout')" method="post" as="button" class="mx-auto block text-sm">
                {{ t('logout') }}
            </TextLink>
        </form>
    </AuthLayout>
</template>
