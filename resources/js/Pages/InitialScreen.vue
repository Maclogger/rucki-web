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
        created_at: string,
        day_of_the_week: number,
    }>;
    settingPairs: Array<{
        key: string,
        value: any,
        type: {
            type_name: string
        },
    }>;
}>();


const form = useForm({});
const gitHubStore = useGitHubStore();
const publicStore = usePublicStore();

const submit = () => {
    form.post("/fetch-github-contributions");
};

function setPublicStoreState() {
    console.log("Prišli zo serveru settingy: ");
    console.log(props.settingPairs);


    const publicStoreState = {
        canLogin: props.canLogin,
        canRegister: props.canRegister,
        laravelVersion: props.laravelVersion,
        phpVersion: props.phpVersion,
        settingPairs: props.settingPairs,
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
        created_at: new Date(record.created_at),
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
