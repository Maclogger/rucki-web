<script setup lang="ts">
import type {GitHubRecord} from "@/stores/githubStore";
import {computed, onMounted} from "vue";
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

const colors = {
    "dracula": [
        {
            'value': "#424557",
        },
        {
            'value': "#f1b4de",
        },
        {
            'value': "#EE83C4",
        },
        {
            'value': "#e95db7",
        },
        {
            'value': "#b7257b",
        },
    ],
    "dark": [
        {
            'value': "rgba(86,86,177,0.23)",
        },
        {
            'value': "#4e4ed6",
        },
        {
            'value': "#6e6bea",
        },
        {
            'value': "#8988eb",
        },
        {
            'value': "#c4c3f6",
        },
    ],
}

const theme = "dark";


const getColor = computed(() => {
    const isCurrentYear =
        date.value.getFullYear() === gitHubStore.currently_displayed_year;
    if (!isCurrentYear) return "";
    const level = props.gitHubRecord?.year_level ?? 0;
    if (level === 0) return colors[theme][0].value;
    if (level <= 0.25) return colors[theme][1].value;
    if (level <= 0.5) return colors[theme][2].value;
    if (level <= 0.75) return colors[theme][3].value;
    return colors[theme][4].value;
});

const tooltipText = computed(() =>
    props.gitHubRecord
        ? `${props.gitHubRecord.contributions_count} contributions on ${toNicelyFormattedDateAndTime(
            props.gitHubRecord.date
        )}`
        : "No contributions"
);

import tippy from 'tippy.js';

const getId = () => {
    return "id-" + props.day + "-" + props.week;
}

onMounted(() => {
    tippy('#id-' + props.day + "-" + props.week, {
        content: tooltipText.value,
        arrow: true,
        animation: "fade",
        theme: "tomato",
    });
});

</script>

<template>
    <div class="grid-container">
        <div
            :id="getId()"
            class="cell relative w-4 h-4 rounded-sm "
            :style="{ backgroundColor: getColor }"
        >
        </div>
    </div>
</template>

<style scoped>


</style>
