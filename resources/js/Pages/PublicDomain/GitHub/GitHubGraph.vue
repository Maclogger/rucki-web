<script setup lang="ts">

import {useGitHubStore, GitHubRecord} from "@/stores/githubStore";
import {storeToRefs} from "pinia";
import GitHubTile from "@/Pages/PublicDomain/GitHub/GitHubTile.vue";
import {computed} from "vue";

const gitHubStore = useGitHubStore();


const {} = storeToRefs(gitHubStore)

/*
const {git_hub_records} = storeToRefs(gitHubStore);

const recordMap = computed(() => {
    const map = new Map<string, GitHubRecord>();
    gitHubRecords.value.forEach(record => {
        const key = `${record.day_of_the_week}-${record.week_of_the_year}`;
        map.set(key, record);
    });
    return map;
});


const getGitHubRecord = (day: number, week: number):  => {
    const key = `${day}-${week}`;
    return recordMap.value.get(key);
};

const getContributionCount = (day: number, week: number): number => {
    const key = `${day}-${week}`;
    return recordMap.value.get(key) || 0;
};

const getYearLevel = (day: number, week: number): number => {
    const key = `${day}-${week}`;
    return recordMap.value.get(key) || 0;
};
*/


</script>

<template>
    <div class="h-full">
        <div class="p-2">
            <div class="flex flex-col gap-1">
<!--
                <div v-for="dayIndex in numRows" :key="`row-${dayIndex}`" class="flex gap-1">
                    <div v-for="weekIndex in numCols" :key="`col-${dayIndex}-${weekIndex}`">
                        <GitHubTile
                            :day="dayIndex"
                            :week="weekIndex"
                            :contributionCount="getContributionCount(dayIndex, weekIndex)"
                            :year_level="get"
                        />
                    </div>
                </div>
-->
            </div>
        </div>
    </div>
</template>


<style scoped>

</style>
