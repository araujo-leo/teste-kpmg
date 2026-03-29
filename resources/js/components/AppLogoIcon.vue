<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        viewBox="0 0 200 200"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        v-bind="$attrs"
    >
        <path
            d="M140 60C140 37.9086 122.091 20 100 20C77.9086 20 60 37.9086 60 60C60 100 140 100 140 140C140 162.091 122.091 180 100 180C77.9086 180 60 162.091 60 140"
            stroke="currentColor"
            stroke-width="24"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
        <circle cx="140" cy="60" r="16" fill="currentColor" />
        <circle cx="60" cy="140" r="16" fill="currentColor" />
    </svg>
</template>
