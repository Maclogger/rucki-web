<script setup lang="ts">
import {computed, onMounted} from "vue";
import {GithubRecord, useGithubStore} from "@/stores/githubStore";
import {getDayCountFromStartOfTheYear, isLeapYear} from '@/utils/dateHelper';
import GithubLegendFakeTile from "@/Pages/PublicDomain/Github/GithubLegendFakeTile.vue";
import GithubTile from "@/Pages/PublicDomain/Github/GithubTile.vue";

const githubStore = useGithubStore();

export interface TileData {
    date?: Date | undefined;
    row: number;
    column: number;
    contributionData: GithubRecord | null;
}

function getRowColForGivenDate(date: Date): { row: number; col: number } {
    console.log(date);
    if (!date) return {row: -1, col: -1};
    if (date.getFullYear() != githubStore.selected_year) {
        throw new Error("selectedYear from githubStore != given date");
    }

    const dayCountFromStartOfTheYear = getDayCountFromStartOfTheYear(date);
    const col = Math.floor((dayCountFromStartOfTheYear - 1) / 7);

    let row = date.getDay() - 1;
    if (row == -1) row = 6;

    return {row: row, col: col};
}

const weekCount = computed(() => {
    return Math.ceil((isLeapYear(githubStore.selected_year) ? 366 : 365) / 7);
});

const matrix = computed(() => {
    const selected_year = githubStore.selected_year;


    // Inicializácia matice
    const m: TileData[][] = [];
    for (let row = 0; row < 7; row++) {
        m[row] = [];
        for (let col = 0; col < weekCount.value; col++) {
            m[row][col] = {
                contributionData: null,
                row: row,
                column: col,
            };
        }
    }

    // Správne vytvorenie dátumu pre 1. január vybraného roka
    const firstJanuary = new Date(selected_year, 0, 1);

    // Zistenie prvého dňa v týždni
    let firstDay = firstJanuary.getDay(); // 0 => SUNDAY, 1 => MONDAY, ..., 6 => SATURDAY
    firstDay -= 1; // to counter 0 => MONDAY, 1 => TUESDAY, ..., 6 => SUNDAY
    if (firstDay == -1) firstDay = 6;

    // Naplnenie matice dátumami
    let currentDate = new Date(firstJanuary);
    for (let col = 0; col < weekCount.value; col++) {
        for (let row = 0; row < 7; row++) {
            if (col === 0 && row < firstDay) {
                // Prázdne bunky pred začiatkom roka
                continue;
            }

            if (currentDate.getFullYear() === selected_year) {
                m[row][col].date = new Date(currentDate);
                // Správne inkrementovanie dátumu
                currentDate.setDate(currentDate.getDate() + 1);
            }
        }
    }

    const data_by_year = githubStore.data_by_year;
    if (data_by_year && data_by_year.get(selected_year)?.github_records) {
        data_by_year.get(selected_year)!.github_records.forEach((githubRecord) => {
            try {
                if (!githubRecord.date) return;
                const {row, col} = getRowColForGivenDate(githubRecord.date);
                if (m[row] && m[row][col]) {
                    m[row][col].contributionData = githubRecord;
                }
            } catch (error) {
                console.error("Error processing GitHub record:", error);
            }
        });
    }

    return m;
});
</script>

<template>
    <div class="flex flex-col gap-1 overflow-y-auto py-2 ">
        <div class="flex gap-1">
            <div
                v-for="week in weekCount"
            >
                <GithubLegendFakeTile/>
            </div>
        </div>

        <div
            v-if="matrix != undefined"
            v-for="day in 7"
            :key="`row-${day}`"
            class="flex gap-1"
        >
            <div
                v-for="week in weekCount"
                :key="`col-${day}-${week}`"
            >
                <GithubTile
                    :tileData="matrix[day - 1][week - 1]"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
