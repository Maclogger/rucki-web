<script setup lang="ts">
import type {GitHubRecord} from "@/stores/githubStore";
import {computed} from "vue";
import {useGitHubStore} from "@/stores/githubStore";
import {
    toNicelyFormattedDateAndTime,
    getDateFromWeekAndDayISO,
} from "@/utils/dateHelper";

const gitHubStore = useGitHubStore();

const props = defineProps<{
    day: number;
    week: number;
    gitHubRecord: GitHubRecord | null;
}>();

const date = computed(() =>
    getDateFromWeekAndDayISO(
        gitHubStore.currently_displayed_year,
        props.week,
        props.day
    )
);

const getColor = computed(() => {
    const isCurrentYear =
        date.value.getFullYear() === gitHubStore.currently_displayed_year;
    if (!isCurrentYear) return "";
    const level = props.gitHubRecord?.year_level ?? 0;
    if (level === 0) return "#424557";
    if (level <= 0.25) return "#f1b4de";
    if (level <= 0.5) return "#EE83C4";
    if (level <= 0.75) return "#e95db7";
    return "#b7257b";
});

const tooltipText = computed(() =>
    props.gitHubRecord
        ? `${props.gitHubRecord.contributions_count} contributions on ${toNicelyFormattedDateAndTime(
            props.gitHubRecord.date
        )}`
        : "No contributions"
);
</script>

<template>
    <div class="grid-container">
        <div
            class="cell relative w-4 h-4 rounded-sm"
            :style="{ backgroundColor: getColor }"
            :data-tooltip="tooltipText"
        ></div>
    </div>
</template>

<style scoped>
</style>
