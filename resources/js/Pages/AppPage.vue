<script setup lang="ts">

import {useForm} from '@inertiajs/vue3';
import {useGithubStore, GithubYearChart, GithubRecord} from "@/stores/githubStore";
import {usePublicStore} from "@/stores/publicStore";
import {onMounted} from "vue";
import InitialScreen from "@/Pages/InitialScreen.vue";
import GithubSection from "@/Pages/PublicDomain/Github/GithubSection.vue";

const props = defineProps<{
    can_login?: boolean;
    can_register?: boolean;
    laravel_version: string;
    php_version: string;

    constant_pairs: Array<{
        key: string,
        value: any,
        type_name: string,
    }>;

    github_store: {
        last_update: string, // date
        selected_year: number,
        first_year: number,
        data_by_year: {
            [year: number]: {
                year: number,
                github_records: Array<{
                    date: string, // date
                    contributions_count: number,
                    updated_at: string, // date
                    created_at: string, // date
                    day_of_the_week: number,
                    week_of_the_year: number,
                    year_level: number,
                }>
            }
        }
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


function setGithubRecordsState() {
    //const currentGithubYearChartRecords = transformGithubDates();

    const dataByYear = new Map<number, GithubYearChart>()


    Object.entries(props.github_store.data_by_year).forEach(([year, yearData]) => {
        const githubRecords: GithubRecord[] = yearData.github_records.map(record => ({
            ...record,
            date: new Date(record.date),
            updated_at: new Date(record.updated_at),
            created_at: new Date(record.created_at),
        }));

        const githubYearChart: GithubYearChart = {
            year: Number(year),
            github_records: githubRecords,
        }

        dataByYear.set(Number(year), githubYearChart);
    });

    const githubStoreState = {
        last_update: new Date(props.github_store.last_update),
        selected_year: props.github_store.selected_year,
        first_year: Number(props.github_store.first_year),
        data_by_year: dataByYear,
    };

    githubStore.setState(githubStoreState);
}

onMounted(() => {
    setPublicStoreState()
    setGithubRecordsState();
});

</script>

<template>


    <!--    <form class="bg-gray" @submit.prevent="submit">
            <button type="submit" class="btn btn-primary">
                Fetch Github Data
            </button>
        </form>-->

    <InitialScreen/>
    <GithubSection/>


</template>

<style scoped>

</style>
