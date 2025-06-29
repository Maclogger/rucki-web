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

    <Button :severity='isSelected() ? "success" : "primary"' class="">
        {{props.year}}
    </Button>

</template>

<style scoped>

</style>
