<script setup lang="ts">
import type {GitHubRecord} from "@/stores/githubStore";
import {computed, onMounted, ref} from "vue";
import {useGitHubStore} from "@/stores/githubStore";
import {
    toNicelyFormattedDate,
    getDateFromWeekAndDayISO,
} from "@/utils/dateHelper";
import tippy from "tippy.js";
import {vysklonuj} from "@/utils/sklonovac";

const gitHubStore = useGitHubStore();

const props = defineProps<{
    day: number;
    week: number;
    gitHubRecord: GitHubRecord | null;
}>();

const date = computed(() => {
    try {
        return getDateFromWeekAndDayISO(
            gitHubStore.currently_displayed_year,
            props.week,
            props.day
        );
    } catch (error) {
        console.error("Chyba pri získavaní dátumu: {" + gitHubStore.currently_displayed_year + "}", error);
        return null;
    }
});

const colors = {
    dracula: [
        {value: "#424557"},
        {value: "#f1b4de"},
        {value: "#EE83C4"},
        {value: "#e95db7"},
        {value: "#b7257b"},
    ],
    dark: [
        {value: "rgba(86,86,177,0.23)"},
        {value: "#4e4ed6"},
        {value: "#6e6bea"},
        {value: "#8988eb"},
        {value: "#c4c3f6"},
    ],
};

const theme = "dark";

const cellColor = computed(() => {
    if (!date.value) return "";

    const isCurrentYear = date.value.getFullYear() === gitHubStore.currently_displayed_year;
    if (!isCurrentYear) {
        console.log(
            "Tento rok podla toho nesedi: " +
            date.value.getFullYear() +
            " - " +
            gitHubStore.currently_displayed_year
        );
        return "";
    }

    const level = props.gitHubRecord?.year_level ?? 0;
    if (level === 0) return colors[theme][0].value;
    if (level <= 0.25) return colors[theme][1].value;
    if (level <= 0.5) return colors[theme][2].value;
    if (level <= 0.75) return colors[theme][3].value;
    return colors[theme][4].value;
});

const tooltipText = computed(() => {
    if (!date.value) return "Neplatný dátum";

    let output;
    try {
        output = toNicelyFormattedDate(date.value) + ": ";
    } catch (error) {
        console.error("Chyba pri formátovaní dátumu {" + date.value + "}:", error);
        output = "Dátum: ";
    }

    const noContributions = !props.gitHubRecord || props.gitHubRecord.contributions_count <= 0;
    if (noContributions) {
        return output + "žiadne príspevky";
    }

    return output + vysklonuj(
        props.gitHubRecord.contributions_count,
        "príspevok",
        "príspevky",
        "príspevkov"
    );
});

const cellId = computed(() => {
    return "id-" + props.day + "-" + props.week;
});

onMounted(() => {
    const element = document.getElementById(cellId.value);
    if (element) {
        tippy("#" + cellId.value, {
            content: tooltipText.value,
            arrow: true,
            animation: "fade",
            theme: "tomato",
        });
    } else {
        console.warn(`Element s ID ${cellId.value} nebol nájdený`);
    }
});
</script>

<template>
    <div class="grid-container">
        <div
            :id="cellId"
            class="cell relative w-4 h-4 rounded-sm"
            :style="{ backgroundColor: cellColor }"
        ></div>
    </div>
</template>

<style scoped></style>
