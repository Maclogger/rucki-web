<script setup lang="ts">

const props = defineProps<{
    backgroundColorClass: string,
}>();

</script>

<template>
    <div class="grid-container">
        <div
            class="cell relative w-4 h-4 rounded-sm"
            :class="backgroundColorClass"
        ></div>
    </div>
</template>

<style scoped>

</style>
