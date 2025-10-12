<script setup lang="ts">
import {usePublicStore} from "@/stores/publicStore";
const publicStore = usePublicStore();
</script>

<template>
    <div class="min-h-[85vh] flex flex-col items-center justify-center py-9 md:flex-row md:justify-center">

        <div class="w-full md:w-6/12 h-auto md:h-full flex items-center justify-center md:justify-end text-center md:text-right">
            <div class="flex flex-col mb-12 md:me-24 md:mb-0">
                <p class="md:text-start text-4xl lg:text-5xl font-semibold ">{{ publicStore.getFullName() }}</p>
                <p class="md:text-start sm:text-lg lg:text-xl">{{ publicStore.getConstant("rola") }}</p>
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
