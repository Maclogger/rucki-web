<script setup lang="ts">
import type {GitHubRecord} from "@/stores/githubStore";
import {computed, onMounted} from "vue";
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

const colorClasses = {
    dracula: [
        "bg-[#424557]",
        "bg-[#f1b4de]",
        "bg-[#EE83C4]",
        "bg-[#e95db7]",
        "bg-[#b7257b]",
    ],
    dark: [
        "bg-primary-dark-transparent",
        "bg-primary-dark",
        "bg-primary",
        "bg-primary-light",
        "bg-primary-light-ultra",
    ],
};

const theme = "dark";

const cellBgClass = computed(() => {
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
    if (level === 0) return colorClasses[theme][0];
    if (level <= 0.25) return colorClasses[theme][1];
    if (level <= 0.5) return colorClasses[theme][2];
    if (level <= 0.75) return colorClasses[theme][3];
    return colorClasses[theme][4];
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
        tippy(element, {
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
            :class="cellBgClass"
        ></div>
    </div>
</template>

<style scoped>
/* Scoped štýly sú v poriadku pre špecifické veci, ktoré nechceš riešiť Tailwindiem.
   Pre farby pozadia by si sa mal snažiť použiť Tailwind triedy.
*/
</style>
