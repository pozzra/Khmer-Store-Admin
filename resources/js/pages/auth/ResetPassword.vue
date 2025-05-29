<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

// --- Translation logic ---
import { ref } from 'vue';
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations = {
    en: {
        resetTitle: "Reset password",
        resetDesc: "Please enter your new password below",
        email: "Email",
        password: "Password",
        confirmPassword: "Confirm Password",
        reset: "Reset password",
    },
    km: {
        resetTitle: "កំណត់ពាក្យសម្ងាត់ថ្មី",
        resetDesc: "សូមបញ្ចូលពាក្យសម្ងាត់ថ្មីរបស់អ្នកខាងក្រោម",
        email: "អ៊ីមែល",
        password: "ពាក្យសម្ងាត់",
        confirmPassword: "បញ្ជាក់ពាក្យសម្ងាត់",
        reset: "កំណត់ពាក្យសម្ងាត់ថ្មី",
    }
};
function t(key: string) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

interface Props {
    token: string;
    email: string;
}

const props = defineProps<Props>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <AuthLayout :title="t('resetTitle')" :description="t('resetDesc')">
        <Head :title="t('resetTitle')" />

        <form @submit.prevent="submit">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">{{ t('email') }}</Label>
                    <Input id="email" type="email" name="email" autocomplete="email" v-model="form.email" class="mt-1 block w-full" readonly />
                    <InputError :message="form.errors.email" class="mt-2" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">{{ t('password') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        v-model="form.password"
                        class="mt-1 block w-full"
                        autofocus
                        :placeholder="t('password')"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">{{ t('confirmPassword') }}</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        class="mt-1 block w-full"
                        :placeholder="t('confirmPassword')"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-4 w-full" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{ t('reset') }}
                </Button>
            </div>
        </form>
    </AuthLayout>
</template>
