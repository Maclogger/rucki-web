<script setup lang="ts">

import GitHubGraph from "@/Pages/PublicDomain/GitHub/GitHubGraph.vue";
import {useGitHubStore} from "@/stores/githubStore";
import {toNicelyFormattedDateAndTime} from "@/utils/dateHelper";
import {storeToRefs} from "pinia";
import {usePublicStore} from "@/stores/publicStore";

const gitHubStore = useGitHubStore();
const publicStore = usePublicStore();

const {last_update} = storeToRefs(gitHubStore);

const redirect = (url: string ) => {
    console.log("deje sa");
    window.location.replace(url);
}


</script>

<template>
    <div class="bg-my-black h-screen">
        <div class="h-full md:container mx-auto flex">
            <div class="md:w-1/3 h-full ">
                <p class="md:text-start text-3xl sm:text-4xl lg:text-5xl font-semibold text-my-white">{{ publicStore.getFullName() }}</p>
                <p class="text-my-white">Pozrite si moju tvorbu priamo na GitHube</p>
                <button @click="redirect('https://github.com/Maclogger')"
                        class="bg-my-white p-3 flex rounded-lg duration-300 hover:bg-my-github-green hover:text-my-white"

                >
                    <img src="/images/icons/odkaz.png" class="w-6 me-4 "/>
                    https://github.com/Maclogger
                </button>
            </div>
            <div class="md:w-2/3 bg-blue-100 h-full">

            </div>


        </div>


    </div>
</template>


<!--
<p>Last update: {{ toNicelyFormattedDateAndTime(last_update) }}</p>
<GitHubGraph/>
-->
<style scoped>

</style>
