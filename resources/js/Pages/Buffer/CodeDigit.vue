<script setup lang="ts">

import {computed} from "vue";

const props = defineProps<{
    digit: number;
    focused: boolean;
    value?: string;
}>();


const calculatedClass = computed(() => {
    return props.focused ? "border-2 border-my-white" : "";
});

const shouldAnimateClass = computed(() => {
    return props.focused ? "animate-pulse" : "";
});


</script>

<template>
    <div class="flex items-center justify-center p-8 input w-10 h-10" :class="calculatedClass">
        <p class="p-0 m-0 text-4xl" :class="shouldAnimateClass">{{ value ?? "_" }}</p>
    </div>
</template>

