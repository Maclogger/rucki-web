<script setup lang="ts">

const props = defineProps<{
    backgroundColorClass?: string | null,
    text?: string | null,
}>();

</script>

<template>
    <div class="grid-container">
        <div
            class="cell relative w-4 h-4 rounded-sm flex items-center justify-center"
            :class="backgroundColorClass ?? 'bg-[rgba(255, 255, 255, 0)]'"
        >
            <p v-if="text" class="p-0 m-0 text-white text-xs font-bold">{{text}}</p>
        </div>
    </div>
</template>

<style scoped>

</style>
