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
    <div class="flex items-center justify-center lg:p-8 sm:p-4 md:p-4 input aspect-square flex-grow flex-shrink basis-1/6"
         :class="calculatedClass">
        <p class="p-0 m-0 sm:text-2xl lg:text-3xl" :class="shouldAnimateClass">{{ value ?? "_" }}</p>
    </div>
</template>

