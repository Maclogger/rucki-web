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

// Dátum (zostaňme pri tvojom riešení)
const date = computed(() =>
    getDateFromWeekAndDayISO(
        gitHubStore.currently_displayed_year,
        props.week,
        props.day
    )
);

// Farba políčka
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

// Text tooltipu
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
        <!-- ďalšie bunky -->
    </div>
</template>

<style scoped>
/* -- GRID NASTAVENIA -- */
.grid-container {
    display: grid;
    /* napr.: grid-template-rows: repeat(7, 1rem);
       grid-template-columns: repeat(53, 1rem); */
    gap: 0;
    line-height: 0; /* zruší rezervu pre inline potomkov */
}

/* -- BUNKA (tooltip wrapper) -- */
.cell {
    /* block element, žiadna spodná rezerva */
    position: relative;
}

/* -- TOOLTIP BOX -- */
.cell[data-tooltip]::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%) translateY(0rem);
    background-color: #EE83C4;
    color: #fff;
    padding: 1rem 1rem;           /* väčší tooltip */
    border-radius: 4px;
    font-size: 0.875rem;        /* 14px */
    white-space: nowrap;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
    z-index: 10;
}

/* -- ŠÍPKA (arrow) -- */
.cell[data-tooltip]::before {
    content: "";
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    border-width: 6px;
    border-style: solid;
    border-color: #EE83C4 transparent transparent transparent;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.1s ease-in-out;
    z-index: 11;
}

/* -- ZOBRAZENIE na hover -- */
.cell[data-tooltip]:hover::after,
.cell[data-tooltip]:hover::before {
    opacity: 1;
    transform: translateX(-50%) translateY(-20%);
}
</style>
