<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    BookCopy,
    BookOpen,
    BookUser,
    CalendarCheck2,
    CalendarDays,
    Contact,
    Folder,
    GraduationCap,
    LayoutGrid,
    MessageSquareWarning,
    School,
    Users,
    PackageSearch, 
    ShoppingCart,
    Warehouse,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import AppLogo from './AppLogo.vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
watch(selectedLanguage, (newLang) => {
    localStorage.setItem('language', newLang);
});

const translations: Record<string, Record<string, string>> = {
    en: {
        dashboard: 'Dashboard',
        'manage-admins': 'Manage Admins',
        'manage-products': 'Manage Products',
        'manage-stock': 'Manage Stock',
        'manage-order': 'Manage Order',
        'manage-repot': 'Manage Reort',
        'github-repo': 'Github Repo',
        documentation: 'Documentation',
        repository: 'Repository',
        report: 'report',
        'Student Registration': 'Student Registration',
    },
    km: {
        dashboard: 'ផ្ទាំងគ្រប់គ្រង',
        'manage-admins': 'គ្រប់គ្រងអ្នកគ្រប់គ្រង',
        'manage-products': 'ផលិតផល​',
        'manage-stock': 'គ្រប់គ្រងផលិតផល​',
        'manage-order': 'គ្រប់គ្រងលំដាប់​',
        'manage-repot': 'របាយការណ៍',
        'github-repo': 'Github Repo',
        documentation: 'ឯកសារ',
        repository: 'ឃ្លាំង',
        report: 'របាយការណ៍',
        'Student Registration': 'ចុះឈ្មោះសិស្ស',
    },
};

function t(key: string) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---

const mainNavItems: NavItem[] = [
    {
        title: t('dashboard'),
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: t('manage-admins'),
        href: '/admin',
        icon: Users,
    },
    {
        title: t('manage-products'),
        href: '/products',
        icon: PackageSearch,
    },
    {
        title: t('stock'),
        href: '/stock',
        icon: Warehouse,
    },
    {
        title: t('order'),
        href: '/order',
        icon: ShoppingCart,
    },
    {
        title: t('report'),
        href: '/report',
        icon: MessageSquareWarning,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: t('github-repo'),
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: t('documentation'),
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
