<script setup lang="ts">
import { useGithubStore } from "@/stores/githubStore";
import { storeToRefs } from "pinia";
import { computed } from "vue";
import GithubGraphYearButton from "@/Pages/PublicDomain/Github/GithubGraphYearButton.vue";

const githubStore = useGithubStore();
const { github_year_from } = storeToRefs(githubStore);

const currentYear = new Date().getFullYear();

const startYear = computed(() => Number(github_year_from.value));

const yearsToDisplay = computed<number[]>(() => {
    const start = startYear.value;
    const len = currentYear - start + 1;
    return Array.from({ length: len }, (_, i) => currentYear - i);
});
</script>

<template>
    <div class="flex gap-2 py-2 overflow-y-auto">
        <div v-for="year in yearsToDisplay" :key="year">
            <GithubGraphYearButton :year="year" />
        </div>
    </div>
</template>

<style scoped>
</style>
