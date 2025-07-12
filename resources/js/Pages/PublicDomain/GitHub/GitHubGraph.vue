<script setup lang="ts">
import {computed} from "vue";
import {useGitHubStore} from "@/stores/githubStore";
import {storeToRefs} from "pinia";
import GitHubTile from "@/Pages/PublicDomain/GitHub/GitHubTile.vue";
import type {GitHubYearChart, GitHubRecord} from "@/stores/githubStore";

const gitHubStore = useGitHubStore();
const {currently_displayed_year, git_hub_year_charts} = storeToRefs(gitHubStore);

// 1) Dáta pre aktuálny rok
const currentYearData = computed<GitHubYearChart | null>(() => {
    return (
        git_hub_year_charts.value.get(currently_displayed_year.value) || null
    );
});

// 2) Počet týždňov (fallback na 0)
const weekCount = computed(() => currentYearData.value?.week_count ?? 0);

// 3) Rýchly lookup mapou (kľúč = "week-day")
const recordMap = computed(() => {
    const map = new Map<string, GitHubRecord>();
    currentYearData.value?.git_hub_records.forEach((rec) => {
        map.set(`${rec.week_of_the_year}-${rec.day_of_the_week}`, rec);
    });
    return map;
});

// 4) Funkcia na získanie jedného záznamu alebo null
function getRecord(week: number, day: number): GitHubRecord | null {
    return recordMap.value.get(`${week}-${day}`) ?? null;
}
</script>

<template>
    <div class="flex flex-col gap-1 overflow-y-auto py-2 mb-4">
        <div
            v-for="day in 7"
            :key="`row-${day}`"
            class="flex gap-1"
        >
            <div
                v-for="week in weekCount + 1"
                :key="`col-${day}-${week}`"
            >
                <GitHubTile
                    :day="day"
                    :week="week"
                    :gitHubRecord="getRecord(week, day)"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>
/* sem môžeš pridať scoped štýly */
</style>
