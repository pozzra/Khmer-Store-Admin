<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { LogOut, Settings } from 'lucide-vue-next';

// --- Translation logic ---
import { ref } from 'vue';
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations = {
    en: {
        settings: "Settings",
        logout: "Log out",
    },
    km: {
        settings: "ការកំណត់",
        logout: "ចាកចេញ",
    }
};
function t(key: string) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

interface Props {
    user: User;
}

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full" :href="route('profile.edit')" prefetch as="button">
                <Settings class="mr-2 h-4 w-4" />
                {{ t('settings') }}
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true">
        <Link class="block w-full" method="post" :href="route('logout')" @click="handleLogout" as="button">
            <LogOut class="mr-2 h-4 w-4" />
            {{ t('logout') }}
        </Link>
    </DropdownMenuItem>
</template>
