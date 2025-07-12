<script setup lang="ts">
import { useGitHubStore } from "@/stores/githubStore";
import { storeToRefs } from "pinia";
import { computed } from "vue";
import GitHubGraphYearButton from "@/Pages/PublicDomain/GitHub/GitHubGraphYearButton.vue";

const gitHubStore = useGitHubStore();
const { git_hub_year_from } = storeToRefs(gitHubStore);

const currentYear = new Date().getFullYear();

const startYear = computed(() => Number(git_hub_year_from.value));

const yearsToDisplay = computed<number[]>(() => {
    const start = startYear.value;
    const len = currentYear - start + 1;
    return Array.from({ length: len }, (_, i) => currentYear - i);
});
</script>

<template>
    <div class="flex gap-2 py-2 overflow-y-auto">
        <div v-for="year in yearsToDisplay" :key="year">
            <GitHubGraphYearButton :year="year" />
        </div>
    </div>
</template>

<style scoped>
</style>
