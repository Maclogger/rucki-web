<script setup lang="ts">

import {useGitHubStore} from "@/stores/githubStore";
import {storeToRefs} from "pinia";
import {usePublicStore} from "@/stores/publicStore";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const gitHubStore = useGitHubStore();
const publicStore = usePublicStore();

const {last_update} = storeToRefs(gitHubStore);

const redirect = (url: string) => {
    console.log("deje sa");
    window.location.replace(url);
}


</script>

<template>
    <div class="bg-my-black h-screen">
        <div class="h-full flex">
            <div class="md:w-2/5 h-full relative overflow-hidden">
                <font-awesome-icon class="text-my-white background-github" :icon="['fab', 'github']"/>
                <div class="flex flex-col mx-36">
                    <p class="md:text-start
                    text-3xl sm:text-4xl lg:text-5xl
                    font-semibold text-my-white mt-24">
                        {{
                            publicStore.getFullName()
                        }}
                    </p>
                    <p class="text-my-white">Pozrite si moju tvorbu priamo na GitHube</p>
                    <button @click="redirect('https://github.com/Maclogger')"
                            class="bg-my-white p-3 flex
                        rounded-lg duration-300
                        hover:bg-my-github-green
                        hover:text-my-white"

                    >
                        https://github.com/Maclogger
                        <font-awesome-icon :icon="['fab', 'github']"/>
                    </button>
                </div>
            </div>
            <div class="md:w-3/5 bg-blue-100 h-full">

            </div>


        </div>


    </div>
</template>


<!--
<p>Last update: {{ toNicelyFormattedDateAndTime(last_update) }}</p>
<GitHubGraph/>
-->
<style scoped>

.background-github {
    position: absolute;
    bottom: -4rem;
    height: 32rem;
    width: 32rem;
    left: 50%;
    transform: translate(-50%, 0)
}

</style>
