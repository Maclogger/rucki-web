<script setup lang="ts">

import {useGitHubStore} from "@/stores/githubStore";

const props = defineProps<{
    year: number
}>();

const gitHubStore = useGitHubStore();

const isSelected = () => {
    return gitHubStore.currently_displayed_year === props.year;
}

</script>

<template>

    <button class="btn" :class="isSelected() ? 'btn-primary' : 'btn-neutral'">
        {{props.year}}
    </button>

</template>

<style scoped>

</style>
