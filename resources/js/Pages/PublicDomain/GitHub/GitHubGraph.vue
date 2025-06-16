<script setup lang="ts">

import {useGitHubStore} from "@/stores/githubStore";
import {storeToRefs} from "pinia";
import GitHubTile from "@/Pages/PublicDomain/GitHub/GitHubTile.vue";
import {computed} from "vue";

const gitHubStore = useGitHubStore();

const {gitHubRecords} = storeToRefs(gitHubStore);

const contributionsMap = computed(() => {
    const map = new Map<string, number>();
    gitHubRecords.value.forEach(record => {
        const key = `${record.day_of_the_week}-${record.week_of_the_year}`;
        map.set(key, record.contributions_count);
    });
    return map;
});

const getContributionCount = (day: number, week: number): number => {
    const key = `${day}-${week}`;
    return contributionsMap.value.get(key) || 0;
};

const numRows = 8;
const numCols = 54;

</script>

<template>
    <div class="h-full">
        <div class="p-2">
            <div class="flex flex-col gap-1">
                <!-- Riadky reprezentujú dni v týždni (napr. 1-7) + jeden pre záhlavie alebo iné -->
                <!-- Predpokladajme, že r = deň v týždni (1-7), r=0 pre label riadok -->
                <div v-for="dayIndex in numRows" :key="`row-${dayIndex}`" class="flex gap-1">
                    <!-- Stĺpce reprezentujú týždne v roku (napr. 1-54) + jeden pre záhlavie -->
                    <!-- Predpokladajme, že s = týždeň v roku (1-54) -->
                    <div v-for="weekIndex in numCols" :key="`col-${dayIndex}-${weekIndex}`">
                        <!-- Odovzdávame konkrétny deň a týždeň, aby GitHubTile vedel, kde je -->
                        <!-- A hlavne odovzdávame contributionCount priamo! -->
                        <GitHubTile
                            :day="dayIndex"
                            :week="weekIndex"
                            :contributionCount="getContributionCount(dayIndex, weekIndex)"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>

</style>
