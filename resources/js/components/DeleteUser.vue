<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

// Components
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
watch(selectedLanguage, (newLang) => {
    localStorage.setItem('language', newLang);
});

const translations = {
    en: {
        deleteAccount: "Delete account",
        deleteAccountDesc: "Delete your account and all of its resources",
        warning: "Warning",
        warningDesc: "Please proceed with caution, this cannot be undone.",
        areYouSure: "Are you sure you want to delete your account?",
        confirmDelete: "Once your account is deleted, all of its resources and data will also be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.",
        password: "Password",
        cancel: "Cancel",
        delete: "Delete account",
    },
    km: {
        deleteAccount: "លុបគណនី",
        deleteAccountDesc: "លុបគណនីរបស់អ្នក និងធនធានទាំងអស់របស់វា",
        warning: "ការព្រមាន",
        warningDesc: "សូមប្រុងប្រយ័ត្ន វានឹងមិនអាចត្រឡប់វិញបានទេ។",
        areYouSure: "តើអ្នកប្រាកដថាចង់លុបគណនីរបស់អ្នកមែនទេ?",
        confirmDelete: "បន្ទាប់ពីគណនីរបស់អ្នកត្រូវបានលុប ទិន្នន័យទាំងអស់នឹងត្រូវបានលុបចោលជាអចិន្ត្រៃយ៍។ សូមបញ្ចូលពាក្យសម្ងាត់របស់អ្នកដើម្បីបញ្ជាក់។",
        password: "ពាក្យសម្ងាត់",
        cancel: "បោះបង់",
        delete: "លុបគណនី",
    }
};

function t(key) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const deleteUser = (e: Event) => {
    e.preventDefault();

    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="space-y-6">
        <HeadingSmall :title="t('deleteAccount')" :description="t('deleteAccountDesc')" />
        <div class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10">
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-medium">{{ t('warning') }}</p>
                <p class="text-sm">{{ t('warningDesc') }}</p>
            </div>
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="destructive">{{ t('deleteAccount') }}</Button>
                </DialogTrigger>
                <DialogContent>
                    <form class="space-y-6" @submit="deleteUser">
                        <DialogHeader class="space-y-3">
                            <DialogTitle>{{ t('areYouSure') }}</DialogTitle>
                            <DialogDescription>
                                {{ t('confirmDelete') }}
                            </DialogDescription>
                        </DialogHeader>

                        <div class="grid gap-2">
                            <Label for="password" class="sr-only">{{ t('password') }}</Label>
                            <Input id="password" type="password" name="password" ref="passwordInput" v-model="form.password" :placeholder="t('password')" />
                            <InputError :message="form.errors.password" />
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary" @click="closeModal"> {{ t('cancel') }} </Button>
                            </DialogClose>

                            <Button variant="destructive" :disabled="form.processing">
                                <button type="submit">{{ t('delete') }}</button>
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
