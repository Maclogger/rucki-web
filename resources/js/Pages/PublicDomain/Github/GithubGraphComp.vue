<script setup lang="ts">

import { computed } from 'vue';
import { useGithubStore } from "@/stores/githubStore";
import { storeToRefs } from "pinia";

import GithubGraph from "@/Pages/PublicDomain/Github/GithubGraph.vue";
import { toNicelyFormattedDateAndTime } from "@/utils/dateHelper";
import GithubGraphYears from "@/Pages/PublicDomain/Github/GithubGraphYears.vue";
import GithubLegend from "@/Pages/PublicDomain/Github/GithubLegend.vue";
import GithubDaysLegend from "@/Pages/PublicDomain/Github/GithubDaysLegend.vue";
import { vysklonuj } from "@/utils/sklonovac";

const githubStore = useGithubStore();
const { last_update, selected_year, data_by_year } = storeToRefs(githubStore);

const contributionsCount = computed(() => {
    return data_by_year.value?.get(selected_year.value)?.total_contributions ?? 0;
})

</script>

<template>
    <p class="text-xl">
        <strong>
            {{ contributionsCount }}
        </strong>
        {{ vysklonuj(contributionsCount, 'príspevok', 'príspevky', 'príspevkov', true) }}
        v roku
        <strong>
            {{ selected_year }}
        </strong>
    </p>
    <div class="flex flex-row">
        <GithubDaysLegend />
        <GithubGraph />
        <GithubLegend />
    </div>
    <GithubGraphYears />
    <p>Aktualizované: <strong>{{ toNicelyFormattedDateAndTime(last_update) }}</strong></p>
</template>

<style scoped></style>
