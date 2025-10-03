<script setup lang="ts">

defineProps<{
    digit: number;
    focused: boolean;
}>();


</script>

<template>
    <div class="flex items-center justify-center p-8 input w-10 h-10">
        <p class="p-0 m-0 text-4xl aspect-square">{{ digit }}</p>
    </div>
</template>

