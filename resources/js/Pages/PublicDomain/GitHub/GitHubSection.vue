<script setup lang="ts">

import {useGitHubStore} from "@/stores/githubStore";
import {storeToRefs} from "pinia";
import {usePublicStore} from "@/stores/publicStore";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import GitHubGraph from "@/Pages/PublicDomain/GitHub/GitHubGraph.vue";
import {toNicelyFormattedDateAndTime} from "@/utils/dateHelper";

const gitHubStore = useGitHubStore();
const publicStore = usePublicStore();

const {last_update} = storeToRefs(gitHubStore);

const redirect = (url: string) => {
    console.log("deje sa");
    window.location.replace(url);
}


</script>

<template>
    <div class=" h-screen">
        <div class="h-full flex">
            <div class="md:w-2/5
                        flex flex-col 2xl:mx-48 xl:mx-28 md:mx-16 mt-36 justify-between overflow-hidden
                ">
                <div id="top-part" class="flex flex-col">
                    <p class="md:text-start
                                text-3xl sm:text-4xl lg:text-4xl
                                font-semibold ">
                        {{
                            publicStore.getFullName()
                        }}
                    </p>
                    <p class="">Pozrite si moju tvorbu priamo na GitHube</p>
                    <Button class="mt-12" @click="redirect('https://github.com/Maclogger')">
                        <font-awesome-icon :icon="['fas', 'square-up-right']"/>
                        https://github.com/Maclogger
                    </Button>
                </div>
                <font-awesome-icon class="my-23 w-full h-full" :icon="['fab', 'github']"/>
            </div>
            <div class="md:w-3/5 bg-blue-100 ">
                <p>Last update: {{ toNicelyFormattedDateAndTime(last_update) }}</p>
                <GitHubGraph/>
            </div>
        </div>


    </div>
</template>

<style scoped>


</style>
