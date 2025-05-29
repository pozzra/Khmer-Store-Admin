<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
watch(selectedLanguage, (newLang) => {
    localStorage.setItem('language', newLang);
});

const translations = {
    en: {
        settings: "Settings",
        manage: "Manage your profile and account settings",
        profile: "Profile",
        password: "Password",
        appearance: "Appearance",
    },
    km: {
        settings: "ការកំណត់",
        manage: "គ្រប់គ្រងប្រវត្តិរូប និងការកំណត់គណនីរបស់អ្នក",
        profile: "ប្រវត្តិរូប",
        password: "ពាក្យសម្ងាត់",
        appearance: "រូបរាង",
    }
};

function t(key: string) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const sidebarNavItems: NavItem[] = [
    {
        title: t('profile'),
        href: '/settings/profile',
    },
    {
        title: t('password'),
        href: '/settings/password',
    },
    {
        title: t('appearance'),
        href: '/settings/appearance',
    },
];

const page = usePage();
const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div class="px-4 py-6">
        <Heading :title="t('settings')" :description="t('manage')" />

        <div class="flex flex-col space-y-8 md:space-y-0 lg:flex-row lg:space-x-12 lg:space-y-0">
            <aside class="w-full max-w-xl lg:w-48">
                <nav class="flex flex-col space-x-0 space-y-1">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="ghost"
                        :class="['w-full justify-start', { 'bg-muted': currentPath === item.href }]"
                        as-child
                    >
                        <Link :href="item.href">
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="my-6 md:hidden" />

            <div class="flex-1 md:max-w-2xl">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
