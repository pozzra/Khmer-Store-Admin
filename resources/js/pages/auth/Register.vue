<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations = {
    en: {
        createAccount: "Create an account",
        enterDetails: "Enter your details below to create your account",
        name: "Name",
        email: "Email address",
        password: "Password",
        confirmPassword: "Confirm password",
        create: "Create account",
        alreadyHave: "Already have an account?",
        login: "Log in",
        fullName: "Full name",
        emailPlaceholder: "email@example.com",
        passwordPlaceholder: "Password",
        confirmPasswordPlaceholder: "Confirm password",
    },
    km: {
        createAccount: "បង្កើតគណនីថ្មី",
        enterDetails: "បញ្ចូលព័ត៌មានរបស់អ្នកដើម្បីបង្កើតគណនី",
        name: "ឈ្មោះ",
        email: "អាសយដ្ឋានអ៊ីមែល",
        password: "ពាក្យសម្ងាត់",
        confirmPassword: "បញ្ជាក់ពាក្យសម្ងាត់",
        create: "បង្កើតគណនី",
        alreadyHave: "មានគណនីរួចហើយ?",
        login: "ចូល",
        fullName: "ឈ្មោះពេញ",
        emailPlaceholder: "email@example.com",
        passwordPlaceholder: "ពាក្យសម្ងាត់",
        confirmPasswordPlaceholder: "បញ្ជាក់ពាក្យសម្ងាត់",
    }
};
function t(key: string) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase :title="t('createAccount')" :description="t('enterDetails')">
        <Head :title="t('createAccount')" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">{{ t('name') }}</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        v-model="form.name"
                        :placeholder="t('fullName')"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">{{ t('email') }}</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        v-model="form.email"
                        :placeholder="t('emailPlaceholder')"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">{{ t('password') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        :placeholder="t('passwordPlaceholder')"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">{{ t('confirmPassword') }}</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        :placeholder="t('confirmPasswordPlaceholder')"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{ t('create') }}
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                {{ t('alreadyHave') }}
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">{{ t('login') }}</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
