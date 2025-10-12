<script setup lang="ts">
import { computed, onMounted, onUnmounted, watch } from "vue";
import { toNicelyFormattedDate } from "@/utils/dateHelper";
import tippy from "tippy.js"; // Importujeme typy
import { vysklonuj } from "@/utils/sklonovac";
import { TileData } from "@/Pages/PublicDomain/Github/GithubGraph.vue";

const props = defineProps<{
    tileData: TileData;
}>();

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
    const isOutsideOfTheYear = props.tileData.date == undefined;
    if (isOutsideOfTheYear) return ""; // invisible

    const level = props.tileData.contributionData?.year_level ?? 0;
    if (level === 0) return colorClasses[theme][0];
    if (level <= 0.25) return colorClasses[theme][1];
    if (level <= 0.5) return colorClasses[theme][2];
    if (level <= 0.75) return colorClasses[theme][3];
    return colorClasses[theme][4];
});

const tooltipText = computed(() => {
    if (!props.tileData.date) return null; // Vráti null ak dátum neexistuje
    const date = props.tileData.date;

    let output;
    try {
        output = toNicelyFormattedDate(date) + ": ";
    } catch (error) {
        console.error("Chyba pri formátovaní dátumu {" + date + "}:", error);
        output = "Dátum: ";
    }

    const noContributions =
        !props.tileData.contributionData ||
        props.tileData.contributionData.contributions_count <= 0;

    if (noContributions) {
        return output + "žiadne príspevky";
    }

    return (
        output +
        vysklonuj(
            props.tileData.contributionData!.contributions_count,
            "príspevok",
            "príspevky",
            "príspevkov"
        )
    );
});

const cellId = computed(() => {
    return "id-" + props.tileData.row + "-" + props.tileData.column;
});

let tippyInstance: any = null;

const updateTooltip = () => {
    // Ak dátum neexistuje, odstráň tooltip ak existuje a skonči
    if (!props.tileData.date) {
        if (tippyInstance) {
            tippyInstance.destroy();
            tippyInstance = null;
        }
        return;
    }

    const element = document.getElementById(cellId.value);
    if (!element) {
        console.warn(`Element s ID ${cellId.value} nebol nájdený`);
        return;
    }

    if (tippyInstance) {
        tippyInstance.setContent(tooltipText.value);
    } else {
        tippyInstance = tippy(element as HTMLElement, {
            content: tooltipText.value || "",
            arrow: true,
            animation: "fade",
            theme: "tomato",
        });
    }
};

onMounted(() => {
    // Vytvor tooltip len ak existuje dátum
    if (props.tileData.date) {
        updateTooltip();
    }
});

// Sleduj zmeny v dátach a aktualizuj tooltip
watch(
    () => props.tileData.contributionData,
    () => {
        updateTooltip();
    },
    { deep: true }
);

// Sleduj zmeny v dátume
watch(
    () => props.tileData.date,
    (newDate) => {
        // Ak sa dátum zmenil na undefined, odstráň tooltip
        if (!newDate && tippyInstance) {
            tippyInstance.destroy();
            tippyInstance = null;
            return;
        }

        // Inak aktualizuj tooltip
        updateTooltip();
    }
);

// Cleanup pri odstránení komponentu
onUnmounted(() => {
    if (tippyInstance) {
        tippyInstance.destroy();
        tippyInstance = null;
    }
});
</script>

<template>
    <div class="grid-container">
        <div :id="cellId" class="cell relative w-4 h-4 rounded-sm" :class="cellBgClass"> </div>
    </div>
</template>

<style scoped></style>
