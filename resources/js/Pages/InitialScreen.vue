<script setup lang="ts">
import {Head, Link, useForm} from '@inertiajs/vue3';
import GitHubSection from "@/Pages/PublicDomain/GitHub/GitHubSection.vue";
import {useGitHubStore} from "@/stores/githubStore";
import {usePublicStore} from "@/stores/publicStore";
import {onMounted} from "vue";

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
    initialGitHubRecords: Array<{
        date: string,
        contributions_count: number,
        updated_at: string,
        created_at: string
    }>;
}>();


const form = useForm({});
const gitHubStore = useGitHubStore();
const publicStore = usePublicStore();

const submit = () => {
    form.post("/fetch-github-contributions");
};

function setPublicStoreState() {
    const publicStoreState = {
        canLogin: props.canLogin,
        canRegister: props.canRegister,
        laravelVersion: props.laravelVersion,
        phpVersion: props.phpVersion,
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

function getGitHubRecordsWithDateTypes() {
    return props.initialGitHubRecords.map(record => ({
        ...record,
        date: new Date(record.date),
        updated_at: new Date(record.updated_at),
        created_at: new Date(record.created_at)
    }));
}

function setGitHubRecordsState() {
    const gitHubRecords = getGitHubRecordsWithDateTypes();

    const latestUpdateDate = findLatestUpdateDate(gitHubRecords);

    const gitHubStoreState = {
        lastUpdate: latestUpdateDate,
        gitHubRecords: gitHubRecords,
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

    <GitHubSection/>

</template>
