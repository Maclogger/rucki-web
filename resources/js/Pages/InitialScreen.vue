<script setup lang="ts">
import {usePublicStore} from "@/stores/publicStore";
const publicStore = usePublicStore();
</script>

<template>
<!--
    <form class="bg-gray" @submit.prevent="submit">
        <button class="bg-github-green w-auto text-white m-2 p-4 rounded">
            Fetch GitHub Data
        </button>
    </form>
-->
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

</template>>
