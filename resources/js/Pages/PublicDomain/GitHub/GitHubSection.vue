<script setup lang="ts">

import {usePublicStore} from "@/stores/publicStore";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import GitHubGraphComp from "@/Pages/PublicDomain/GitHub/GitHubGraphComp.vue";

const publicStore = usePublicStore();


const redirect = (url: string) => {
    window.location.replace(url);
}


</script>

<template>
    <div class=" h-screen">
        <div class="h-full flex bg-blue-100 pt-36">
            <div class="md:w-2/5
                        flex flex-col 2xl:mx-48 xl:mx-28 md:mx-16  justify-between overflow-hidden
                ">
                <div class="flex flex-col">
                    <p class="md:text-start
                                text-3xl sm:text-4xl lg:text-4xl
                                font-semibold ">
                        {{
                            publicStore.getFullName()
                        }}
                    </p>
                    <p class="">Pozrite si moju tvorbu priamo na GitHube</p>
                    <Button class="mt-4 w-fit" @click="redirect('https://github.com/Maclogger')">
                        <font-awesome-icon class="text-2xl" :icon="['fas', 'square-up-right']"/>
                        https://github.com/Maclogger
                    </Button>
                </div>
                <font-awesome-icon class="my-23 text-my-black"    style="width: max-content; height:
                max-content"
                                   :icon="['fab', 'github']"/>
            </div>
            <div class="md:w-3/5 bg-my-white">
                <GitHubGraphComp/>
            </div>
        </div>


    </div>
</template>

<style scoped>


</style>
