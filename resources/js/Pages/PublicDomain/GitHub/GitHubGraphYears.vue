<script setup lang="ts">
import { useGitHubStore } from "@/stores/githubStore";
import GitHubGraphYearButton from "@/Pages/PublicDomain/GitHub/GitHubGraphYearButton.vue";

const gitHubStore = useGitHubStore();

const currentYear = new Date().getFullYear();
const startYear = Number(gitHubStore.git_hub_year_from);

const yearsToDisplay = Array.from({ length: currentYear - startYear + 1 }, (_, i) => currentYear - i);
</script>

<template>
    <div class="flex gap-2">
        <div v-for="year in yearsToDisplay" :key="year">
            <GitHubGraphYearButton :year="year"/>
        </div>
    </div>
</template>

<style scoped></style>
