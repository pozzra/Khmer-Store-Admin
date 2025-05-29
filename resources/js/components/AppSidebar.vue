<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Users, Contact, BookUser, CalendarDays, CalendarCheck2, School, BookCopy, MessageSquareWarning, GraduationCap    } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { ref, watch } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
watch(selectedLanguage, (newLang) => {
    localStorage.setItem('language', newLang);
});

const translations = {
    en: {
        dashboard: "Dashboard",
        'manage-admins': "Manage Admins",
        'manage-students': "Manage Students",
        'manage-teachers': "Manage Teachers",
        'manage-courses': "Manage Courses",
        'manage-subjects': "Manage Subjects",
        'manage-classes': "Manage Classes",
        'github-repo': "Github Repo",
        documentation: "Documentation",
        repository: "Repository",
        report: "Report",
        'Student Registration': "Student Registration",
    },
    km: {
        dashboard: "ផ្ទាំងគ្រប់គ្រង",
        'manage-admins': "គ្រប់គ្រងអ្នកគ្រប់គ្រង",
        'manage-students': "គ្រប់គ្រងសិស្ស",
        'manage-teachers': "គ្រប់គ្រងគ្រូ",
        'manage-courses': "គ្រប់គ្រងវគ្គសិក្សា",
        'manage-subjects': "គ្រប់គ្រងមុខវិជ្ជា",
        'manage-classes': "គ្រប់គ្រងថ្នាក់",
        'github-repo': "Github Repo",
        documentation: "ឯកសារ",
        repository: "ឃ្លាំង",
        report: "របាយការណ៍",
        'Student Registration': "ចុះឈ្មោះសិស្ស",
    }
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
        href: '/admins',
        icon: Users,
    },
    {
        title: t('manage-students'),
        href: '/students',
        icon: BookUser,
    },
    {
        title: t('manage-teachers'),
        href: '/teachers',
        icon: Contact,
    },
    {
        title: t('manage-courses'),
        href: '/courses',
        icon: CalendarDays,
    },
    {
        title: t('manage-subjects'),
        href: '/subjects',
        icon: CalendarCheck2,
    },
    {
        title: t('manage-classes'),
        href: '/classes',
        icon: School,
    },
    {
        title: t('repository'),
        href: '/repository',
        icon: BookCopy ,
    },
    // Add more items as needed
    {
        title: t('report'),
        href: '/report',
        icon: MessageSquareWarning,
    },
    {
        title: t('Student Registration'),
        href: '/another-item',
        icon: GraduationCap ,
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
