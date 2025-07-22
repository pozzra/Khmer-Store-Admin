<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { ref } from 'vue';

// --- Translation logic ---
const selectedLanguage = ref(localStorage.getItem('language') || 'en');
const translations = {
    en: {
        appName: "Khmer Store",
    },
    km: {
        appName: "ហាងខ្មែរ",
    }
};
function t(key: string) {
    return translations[selectedLanguage.value][key] || key;
}
// --- End translation logic ---
</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
        <AppLogoIcon class="size-5 fill-current text-white dark:text-black" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm overflow-hidden" style="width: 180px;">
        <div class="marquee-wrapper">
            <span class="marquee-text">
                {{ t('appName') }}
            </span>
        </div>
    </div>
</template>

<style scoped>
.marquee-wrapper {
  width: 100%;
  overflow: hidden;
  position: relative;
  height: 1.5em;
}
.marquee-text {
  display: inline-block;
  white-space: nowrap;
  position: absolute;
  will-change: transform;
  animation: marquee 10s linear infinite;
}
@keyframes marquee {
  0%   { transform: translateX(100%); }
  100% { transform: translateX(-100%); }
}
</style>
