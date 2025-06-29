<script setup lang="ts">
import {useGitHubStore} from "@/stores/githubStore";
import GitHubGraphYearButton from "@/Pages/PublicDomain/GitHub/GitHubGraphYearButton.vue";
import {computed} from "vue";

const gitHubStore = useGitHubStore();

const currentYear = new Date().getFullYear();
const startYear = computed(() => {
    return Number(gitHubStore.git_hub_year_from);
});

const yearsToDisplay = Array.from({length: currentYear - startYear.value + 1}, (_, i) => currentYear - i);
</script>

<template>
    <div class="flex gap-2">
        <div v-for="year in yearsToDisplay" :key="year">
            <GitHubGraphYearButton :year="year"/>
            {{ yearsToDisplay }}
            {{ startYear }}
            {{ currentYear }}
        </div>
    </div>
</template>

<style scoped></style>
