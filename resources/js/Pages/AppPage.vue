<script setup lang="ts">

import {Head, Link, useForm} from '@inertiajs/vue3';
import GithubSection from "@/Pages/PublicDomain/Github/GithubSection.vue";
import {useGithubStore, GithubYearChart} from "@/stores/githubStore";
import {usePublicStore} from "@/stores/publicStore";
import {onMounted} from "vue";
import InitialScreen from "@/Pages/InitialScreen.vue";

const props = defineProps<{
    can_login?: boolean;
    can_register?: boolean;
    laravel_version: string;
    php_version: string;

    constant_pairs: Array<{
        key: string,
        value: any,
        type: {
            type_name: string
        },
    }>;

    github_chart_data: {
        year: number,
        initial_github_records: Array<{
            date: string,
            contributions_count: number,
            updated_at: string,
            created_at: string,
            day_of_the_week: number,
            week_of_the_year: number,
            year_level: number,
        }>;
        week_count: number,
        github_year_from: number,
    }
}>();


const form = useForm({});
const githubStore = useGithubStore();
const publicStore = usePublicStore();

const submit = () => {
    form.post("/fetch-github-contributions");
};

function setPublicStoreState() {
    const publicStoreState = {
        can_login: props.can_login,
        can_register: props.can_register,
        laravel_version: props.laravel_version,
        php_version: props.php_version,
        constant_pairs: props.constant_pairs,
    };

    publicStore.setState(publicStoreState);
}

function findLatestUpdateDate(
    githubRecords: {
        date: Date; contributions_count: number; updated_at: Date; created_at: Date
    }[]
) {
    let latestUpdateDate: Date | null = null;

    githubRecords.forEach((value, index) => {
        if (latestUpdateDate == null || latestUpdateDate > value.updated_at) {
            latestUpdateDate = value.updated_at;
        }
    })

    return latestUpdateDate;
}

function getCurrentGithubYearChartRecords() {
    return props.github_chart_data.initial_github_records.map(record => ({
        ...record,
        date: new Date(record.date),
        updated_at: new Date(record.updated_at),
        created_at: new Date(record.created_at),
    }));
}

function setGithubRecordsState() {
    const currentGithubYearChartRecords = getCurrentGithubYearChartRecords();

    const currentGithubYearChart = {
        year: props.github_chart_data.year,
        week_count: props.github_chart_data.week_count,
        github_records: currentGithubYearChartRecords
    }

    const allYearsGithubYearCharts = new Map<number, GithubYearChart>()
    allYearsGithubYearCharts.set(currentGithubYearChart.year, currentGithubYearChart);

    const latestUpdateDate = findLatestUpdateDate(currentGithubYearChartRecords);

    const githubStoreState = {
        last_update: latestUpdateDate,
        currently_displayed_year: props.github_chart_data.year,
        github_year_charts: allYearsGithubYearCharts,
        github_year_from: Number(props.github_chart_data.github_year_from),
    };

    githubStore.setState(githubStoreState);
}

onMounted(() => {
/*
    setPublicStoreState()
    setGitHubRecordsState();
*/
});
</script>

<template>


    <form class="bg-gray" @submit.prevent="submit">
        <button type="submit" class="btn btn-primary">
            Fetch Github Data
        </button>
    </form>

<!--
    <InitialScreen/>
    <GithubSection/>
-->



</template>

<style scoped>

</style>
