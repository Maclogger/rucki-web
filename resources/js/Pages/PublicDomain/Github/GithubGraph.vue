<script setup lang="ts">
import {computed} from "vue";
import {useGithubStore} from "@/stores/githubStore";
import {storeToRefs} from "pinia";
import GithubTile from "@/Pages/PublicDomain/Github/GithubTile.vue";
import type {GithubYearChart, GithubRecord} from "@/stores/githubStore";
import GithubLegendFakeTile from "@/Pages/PublicDomain/Github/GithubLegendFakeTile.vue";

const githubStore = useGithubStore();
const {selected_year, github_year_charts} = storeToRefs(githubStore);

const weekCount = computed(() => {



});


</script>

<template>
    <div class="flex flex-col gap-1 overflow-y-auto py-2 ">
<!--
        <div class="flex gap-1">
            <div
                v-for="week in weekCount + 1"
            >
                <GithubLegendFakeTile :text="getWeekLegendText(week)"/>
            </div>
        </div>
-->

<!--
        <div
            v-for="day in 7"
            :key="`row-${day}`"
            class="flex gap-1"
        >
            <div
                v-for="week in weekCount + 1"
                :key="`col-${day}-${week}`"
            >
                <GithubTile
                    :day="day"
                    :week="week"
                    :gitHubRecord="getRecord(week, day)"
                />
            </div>
        </div>
-->
    </div>
</template>

<style scoped>
/* sem môžeš pridať scoped štýly */
</style>
