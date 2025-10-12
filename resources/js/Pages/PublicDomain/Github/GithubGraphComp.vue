<script setup lang="ts">

import GithubGraph from "@/Pages/PublicDomain/Github/GithubGraph.vue";
import GithubGraphYears from "@/Pages/PublicDomain/Github/GithubGraphYears.vue";
import GithubLegend from "@/Pages/PublicDomain/Github/GithubLegend.vue";
import GithubDaysLegend from "@/Pages/PublicDomain/Github/GithubDaysLegend.vue";
import GitHubContributionText from "@/Pages/PublicDomain/Github/GitHubContributionText.vue";


</script>

<template>
    <div class="border rounded-xl p-4">
        <GitHubContributionText/>
        <div class="flex flex-row">
            <GithubDaysLegend />
            <!-- make GithubGraph horizontally scrollable when it's wider than the container -->
            <div class="flex-1 min-w-0 overflow-x-auto">
                <!-- `min-w-0` on the flex child allows it to shrink; `overflow-x-auto` shows a scrollbar -->
                <GithubGraph />
            </div>
            <GithubLegend/>
        </div>
        <GithubGraphYears/>
<!--        <p class="">Aktualizované: <strong>{{ toNicelyFormattedDateAndTime(last_update) }}</strong></p>-->
    </div>
</template>

<style scoped></style>
