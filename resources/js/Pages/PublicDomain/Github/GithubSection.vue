<script setup lang="ts">

import {usePublicStore} from "@/stores/publicStore";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import GithubGraphComp from "@/Pages/PublicDomain/Github/GithubGraphComp.vue";
import GithubTile from "@/Pages/PublicDomain/Github/GithubTile.vue";

const publicStore = usePublicStore();


const redirect = (url: string) => {
    window.location.replace(url);
}


</script>

<template>
    <div class=" h-screen">
        <div class="h-full flex flex-row py-32 xl:gap-20 lg:gap-10 max-w-[1792px] md:gap-8 xl:px-32 lg:px-24 md:px-12 mx-auto">
            <div class="md:w-1/3
                        flex flex-col justify-between overflow-hidden
                ">
                <div class="flex flex-col">
                    <p class="md:text-start
                                text-3xl sm:text-4xl lg:text-4xl
                                font-semibold ">
                        {{
                            publicStore.getFullName()
                        }}
                    </p>
                    <p class="">Pozrite si moju tvorbu priamo na Githube</p>
                    <button class="btn btn-primary mt-4 w-fit py-6" @click="redirect('https://github.com/Maclogger')">
                        <font-awesome-icon class="text-2xl" :icon="['fas', 'square-up-right']"/>
                        <p class="ms-2 me-1">https://github.com/Maclogger</p>
                    </button>
                </div>
                <font-awesome-icon class="mt-24 text-my-white" style="width: max-content; height: max-content"
                                   :icon="['fab', 'github']"/>
            </div>
            <div class="md:w-2/3 flex flex-col">
                <GithubGraphComp/>
            </div>
        </div>
    </div>
</template>

<style scoped>


</style>
