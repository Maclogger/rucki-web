<script setup lang="ts">
import {Head, Link, useForm} from '@inertiajs/vue3';
import GitHubSection from "@/Pages/PublicDomain/GitHub/GitHubSection.vue";
import {useGitHubStore, GitHubYearChart} from "@/stores/githubStore";
import {usePublicStore} from "@/stores/publicStore";
import {onMounted} from "vue";

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
    <form class="bg-gray" @submit.prevent="submit">
        <button class="bg-github-green w-auto text-white m-2 p-4 rounded">
            Fetch GitHub Data
        </button>
    </form>
    <div class="min-h-screen bg-white flex flex-col items-center justify-center py-8 md:flex-row md:justify-center">

        <div class="w-full md:w-6/12 h-auto md:h-full flex items-center justify-center md:justify-end text-center md:text-right">
            <div class="flex flex-col mb-12 md:me-24 md:mb-0">
                <p class="text-3xl sm:text-4xl lg:text-5xl font-sans font-bold">{{ publicStore.getFullName() }}</p>
                <p class="text-start sm:text-lg lg:text-xl">{{ publicStore.getSetting("rola") }}</p>
            </div>
        </div>

        <div class="w-full md:w-6/12 h-auto md:h-full flex items-center justify-center md:justify-start">
            <img
                src="/images/profile_pic.png"
                alt="profile picture"
                class="rounded-full object-cover w-64 h-64 md:w-96 md:h-96"/>
        </div>
    </div>

    <GitHubSection/>
</template>>
