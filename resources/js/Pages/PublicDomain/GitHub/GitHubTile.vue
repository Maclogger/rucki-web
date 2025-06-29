<script setup lang="ts">
import type {GitHubRecord} from "@/stores/githubStore";
import {computed} from "vue";
import {useGitHubStore} from "@/stores/githubStore";
import {toNicelyFormattedDateAndTime, getDateFromWeekAndDayISO} from "@/utils/dateHelper";

const gitHubStore = useGitHubStore();

const props = defineProps<{
    day: number,
    week: number,
    gitHubRecord: GitHubRecord | null,
}>();

const date = computed(() => {
    return getDateFromWeekAndDayISO(gitHubStore.currently_displayed_year, props.week, props.day);
});

const getColor = computed(() => {
    const isCurrentYear: boolean = date.value.getFullYear() === gitHubStore.currently_displayed_year;
    if (!isCurrentYear) {
        return "";
    }


    const level = props.gitHubRecord?.year_level ?? 0;

    if (level === 0) {
        return '#ebedf0';
    } else if (level > 0 && level <= 0.25) {
        return '#9be9a8';
    } else if (level > 0.25 && level <= 0.5) {
        return '#40c463';
    } else if (level > 0.5 && level <= 0.75) {
        return '#30a14e';
    } else {
        return '#216e39';
    }
});


</script>

<template>
    <div
        class="w-4 h-4 rounded-sm"
        :style="{ backgroundColor: getColor }"
        :title="gitHubRecord ? `${gitHubRecord.contributions_count} contributions on ${gitHubRecord.date.toLocaleDateString()}` : 'No contributions'"
    ></div>
</template>

<style scoped>
.rounded-sm {
    border-radius: 2px;
}
</style>
