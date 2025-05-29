<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { ref, onMounted, watch } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

// --- Global Font Size Logic ---
const fontSize = ref(Number(localStorage.getItem('fontSize')) || 16);

function setRootFontSize(size: number) {
    document.documentElement.style.fontSize = size + 'px';
}

onMounted(() => {
    setRootFontSize(fontSize.value);
});

watch(fontSize, (newVal) => {
    setRootFontSize(newVal);
});

// Listen for changes from other tabs or the Appearance page
window.addEventListener('storage', (event) => {
    if (event.key === 'fontSize') {
        fontSize.value = Number(event.newValue) || 16;
        setRootFontSize(fontSize.value);
    }
});
</script>

<template>
    <AppLayout v-bind="props">
        <slot />
    </AppLayout>
</template>
