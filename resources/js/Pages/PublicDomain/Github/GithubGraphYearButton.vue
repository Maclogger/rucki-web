<script setup lang="ts">

import {useGithubStore} from "@/stores/githubStore";

const props = defineProps<{
    year: number
}>();

const githubStore = useGithubStore();

const isSelected = () => {
    return githubStore.selected_year === props.year;
}

const selectThisYear = () => {
    if (!props.year) {
        return;
    }
    githubStore.setNewSelectedYear(props.year);
}

</script>

<template>
    <button @click="selectThisYear" class="btn" :class="isSelected() ? 'btn-primary' : 'btn-neutral'">
        {{props.year}}
    </button>
</template>

<style scoped>

</style>
