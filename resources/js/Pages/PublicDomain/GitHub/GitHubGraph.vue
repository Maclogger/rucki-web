<script setup lang="ts">

import {useGitHubStore} from "@/stores/githubStore";
import {storeToRefs} from "pinia";
import GitHubTile from "@/Pages/PublicDomain/GitHub/GitHubTile.vue";

import type {GitHubYearChart, GitHubRecord} from "@/stores/githubStore";

const gitHubStore = useGitHubStore();


const {} = storeToRefs(gitHubStore)

const {currently_displayed_year, git_hub_year_chart} = storeToRefs(gitHubStore);


const getCurrentYearData = (): GitHubYearChart | null => {
    const currentYear = currently_displayed_year.value;
    const currentYearData = git_hub_year_chart.value.get(currentYear);
    if (currentYearData == undefined) {
        console.log("Current GitHubYearChart is NULL!!!");
        return null;
    }
    return currentYearData;
}

const getDate = (weekOfTheYear: number, dayOfTheWeek: number): GitHubRecord | null => {


};


const getGitHubRecord = (weekOfTheYear: number, dayOfTheWeek: number): GitHubRecord | null => {
    const currentYearData = getCurrentYearData();
    if (!currentYearData) return null;

    for (let gitHubRecord of currentYearData.git_hub_records) {
        if (gitHubRecord.week_of_the_year == weekOfTheYear && gitHubRecord.day_of_the_week == dayOfTheWeek) {
            return gitHubRecord;
        }
    }
    new Date(2025, 0, 1).getDay()

    return null;
}

</script>

<template>
    <div class="p-2">
        <div class="flex flex-col gap-1">
            <div v-for="dayIndex in 7" :key="`row-${dayIndex}`" class="flex gap-1">
                <div v-for="weekIndex in getCurrentYearData()?.week_count" :key="`col-${dayIndex}-${weekIndex}`">
                    <GitHubTile
                        :day="dayIndex"
                        :week="weekIndex"
                        :gitHubRecord="getGitHubRecord(weekIndex, dayIndex)"
                    />
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>

</style>
