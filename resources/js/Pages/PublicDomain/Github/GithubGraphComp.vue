<script setup lang="ts">

import {useGithubStore} from "@/stores/githubStore";
import {storeToRefs} from "pinia";

import GithubGraph from "@/Pages/PublicDomain/Github/GithubGraph.vue";
import {toNicelyFormattedDateAndTime} from "@/utils/dateHelper";
import GithubGraphYears from "@/Pages/PublicDomain/Github/GithubGraphYears.vue";
import GithubLegend from "@/Pages/PublicDomain/Github/GithubLegend.vue";
import GithubDaysLegend from "@/Pages/PublicDomain/Github/GithubDaysLegend.vue";
import GithubTopRepositories from "@/Pages/PublicDomain/Github/GithubTopRepositories.vue";

const githubStore = useGithubStore();
const {last_update} = storeToRefs(githubStore);

</script>

<template>
    <p class="text-xl"><strong>307</strong> príspevkov v roku <strong>2025</strong></p>
    <div class="flex flex-row">
        <GithubDaysLegend/>
        <GithubGraph/>
        <GithubLegend/>
    </div>
    <GithubGraphYears/>
    <p>Aktualizované: <strong>{{ toNicelyFormattedDateAndTime(last_update) }}</strong></p>
    <p class="text-xl mt-12">Moje <strong>TOP</strong> repozitáre, na ktoré sa oplatí pozrieť.</p>
    <GithubTopRepositories class="mt-5"/>
</template>

<style scoped>

</style>
