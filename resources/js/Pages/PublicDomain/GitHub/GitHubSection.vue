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
        <div class="h-full flex flex-row py-42 xl:gap-20 lg:gap-10 md:gap-8 my-container mx-auto">
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
                    <p class="">Pozrite si moju tvorbu priamo na GitHube</p>
                    <button class="btn btn-primary mt-4 w-fit" @click="redirect('https://github.com/Maclogger')">
                        <font-awesome-icon class="text-2xl" :icon="['fas', 'square-up-right']"/>
                        https://github.com/Maclogger
                    </button>
                </div>
                <font-awesome-icon class="my-23 text-my-black"    style="width: max-content; height:
                max-content"
                                   :icon="['fab', 'github']"/>
            </div>
            <div class="md:w-2/3">
                <GitHubGraphComp/>
            </div>
        </div>
    </div>
</template>

<style scoped>

.my-container {
    width: 100%;
    @media (width >= 40rem /* 640px */) {
        max-width: 40rem /* 640px */;
    }
    @media (width >= 48rem /* 768px */) {
        max-width: 48rem /* 768px */;
    }
    @media (width >= 64rem /* 1024px */) {
        max-width: 64rem /* 1024px */;
    }
    @media (width >= 80rem /* 1280px */) {
        max-width: 80rem /* 1280px */;
    }
    @media (width >= 96rem /* 1536px */) {
        max-width: 96rem /* 1536px */;
    }
    @media (width >= 112rem /* 1792px */) {
        max-width: 104rem /* 1792px */;
    }
}

</style>
