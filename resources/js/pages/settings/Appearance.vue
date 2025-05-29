<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

// 1. Load language from localStorage, default to 'en'
const selectedLanguage = ref(localStorage.getItem('language') || 'en');

// 2. Watch for changes and save to localStorage
watch(selectedLanguage, (newLang, oldLang) => {
    if (newLang !== oldLang) {
        localStorage.setItem('language', newLang);
        setTimeout(() => {
            window.location.reload();
        }, 500); // 500ms delay before reload
    }
});

// 3. Translation dictionary
const translations = {
    en: {
        appearanceSettings: 'Appearance settings',
        updateAppearance: "Update your account's appearance settings",
        changeLanguage: 'Change language',
        selectLanguage: 'Select your preferred language',
        selected: 'Selected',
        increaseFont: 'Increase font',
        decreaseFont: 'Decrease font',
        backgroundImage: 'Background image',
        chooseImage: 'Choose an image',
        removeImage: 'Remove background',
    },
    km: {
        appearanceSettings: 'ការកំណត់រូបរាង',
        updateAppearance: 'ធ្វើបច្ចុប្បន្នភាពការកំណត់រូបរាងគណនីរបស់អ្នក',
        changeLanguage: 'ប្ដូរភាសា',
        selectLanguage: 'ជ្រើសរើសភាសាដែលអ្នកចូលចិត្ត',
        selected: 'បានជ្រើសរើស',
        increaseFont: 'បន្ថែមទំហំអក្សរ',
        decreaseFont: 'បន្ថយទំហំអក្សរ',
        backgroundImage: 'រូបភាពផ្ទៃខាងក្រោយ',
        chooseImage: 'ជ្រើសរូបភាព',
        removeImage: 'យករូបភាពចេញ',
    },
};

function t(key: string) {
    return translations[selectedLanguage.value][key] || key;
}

// Font size logic
const fontSize = ref(Number(localStorage.getItem('fontSize')) || 16);

// Only keep font size watcher
watch(fontSize, (newVal) => {
    document.documentElement.style.fontSize = newVal + 'px';
    localStorage.setItem('fontSize', newVal.toString());
});
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: t('appearanceSettings'), href: '/settings/appearance' }]">
        <Head :title="t('appearanceSettings')" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall :title="t('appearanceSettings')" :description="t('updateAppearance')" />
                <AppearanceTabs />

                <!-- Font Size Controls -->
                <div class="mt-4 flex items-center gap-4">
                    <label for="font-size-slider" class="text-sm text-gray-700 dark:text-gray-300"> {{ t('appearanceSettings') }} Font Size </label>
                    <input
                        id="font-size-slider"
                        type="range"
                        min="10"
                        max="32"
                        step="1"
                        v-model="fontSize"
                        @input="localStorage.setItem('fontSize', fontSize.toString())"
                        class="w-40 accent-blue-600"
                    />
                    <span class="ml-2 text-sm text-gray-500">{{ fontSize }}px</span>
                </div>

                <!-- Change Language Section -->
                <div class="mt-8">
                    <HeadingSmall :title="t('changeLanguage')" :description="t('selectLanguage')" />
                    <select
                        class="mt-2 cursor-pointer rounded border bg-gray-50 px-3 py-2 text-black dark:bg-gray-600 dark:text-white"
                        v-model="selectedLanguage"
                    >
                        <option class="cursor-pointer" value="en">English</option>
                        <option class="cursor-pointer" value="km">Khmer</option>
                    </select>
                    <div class="mt-2 text-sm text-gray-500">{{ t('selected') }}: {{ selectedLanguage }}</div>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
