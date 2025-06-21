<script setup lang="ts">

import {Head, Link, useForm} from '@inertiajs/vue3';
import GitHubSection from "@/Pages/PublicDomain/GitHub/GitHubSection.vue";
import {useGitHubStore, GitHubYearChart} from "@/stores/githubStore";
import {usePublicStore} from "@/stores/publicStore";
import {onMounted} from "vue";
import InitialScreen from "@/Pages/InitialScreen.vue";

const props = defineProps<{
    can_login?: boolean;
    can_register?: boolean;
    laravel_version: string;
    php_version: string;

    setting_pairs: Array<{
        key: string,
        value: any,
        type: {
            type_name: string
        },
    }>;

    git_hub_chart_data: {
        year: number,
        initial_git_hub_records: Array<{
            date: string,
            contributions_count: number,
            updated_at: string,
            created_at: string,
            day_of_the_week: number,
            week_of_the_year: number,
            year_level: number,
        }>;
        week_count: number,
    }

}>();


const form = useForm({});
const gitHubStore = useGitHubStore();
const publicStore = usePublicStore();

const submit = () => {
    form.post("/fetch-github-contributions");
};

function setPublicStoreState() {
    console.log("Prišli zo serveru settingy: ");
    console.log(props.setting_pairs);


    const publicStoreState = {
        can_login: props.can_login,
        can_register: props.can_register,
        laravel_version: props.laravel_version,
        php_version: props.php_version,
        setting_pairs: props.setting_pairs,
    };

    publicStore.setState(publicStoreState);
}

function findLatestUpdateDate(
    gitHubRecords: {
        date: Date; contributions_count: number; updated_at: Date; created_at: Date
    }[]
) {
    let latestUpdateDate: Date | null = null;

    gitHubRecords.forEach((value, index) => {
        if (latestUpdateDate == null || latestUpdateDate > value.updated_at) {
            latestUpdateDate = value.updated_at;
        }
    })

    return latestUpdateDate;
}

function getCurrentGitHubYearChartRecords() {
    return props.git_hub_chart_data.initial_git_hub_records.map(record => ({
        ...record,
        date: new Date(record.date),
        updated_at: new Date(record.updated_at),
        created_at: new Date(record.created_at),
    }));
}

function setGitHubRecordsState() {
    const currentGitHubYearChartRecords = getCurrentGitHubYearChartRecords();

    const currentGitHubYearChart = {
        year: props.git_hub_chart_data.year,
        week_count: props.git_hub_chart_data.week_count,
        git_hub_records: currentGitHubYearChartRecords
    }

    const allYearsGitHubYearCharts = new Map<number, GitHubYearChart>()
    allYearsGitHubYearCharts.set(currentGitHubYearChart.year, currentGitHubYearChart);

    const latestUpdateDate = findLatestUpdateDate(currentGitHubYearChartRecords);

    const gitHubStoreState = {
        last_update: latestUpdateDate,
        currently_displayed_year: props.git_hub_chart_data.year,
        git_hub_year_chart: allYearsGitHubYearCharts
    }


    gitHubStore.setState(gitHubStoreState);
}

onMounted(() => {
    setPublicStoreState()
    setGitHubRecordsState();
});
</script>

<template>

    <InitialScreen/>
    <GitHubSection/>

</template>

<style scoped>

</style>
