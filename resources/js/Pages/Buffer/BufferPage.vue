<script setup lang="ts">

import CodeDigit from "@/Pages/Buffer/CodeDigit.vue";
import UploadFilesButton from "@/Pages/Photos/ControlPanel/UploadFilesButton.vue";
import {ref} from "vue";

defineProps<{
    code?: string
}>();

let focusedDigit = ref(1);
let uploadEnabled = ref(false);

</script>

<template>

    <div class="h-[100vh] flex flex-col justify-center items-center p-10">
        <div class="flex flex-col  bg-primary-dark-transparent rounded-lg mx-auto gap-8 p-14">
            <div>
                <p class="text-3xl">Marekov Buffer</p>
                <p class="text-lg">Zadajte kód</p>
            </div>
            <div class="flex flex-row gap-4">
                <CodeDigit :digit="1" :focused="focusedDigit == 1"/>
                <CodeDigit :digit="2" :focused="focusedDigit == 2"/>
                <CodeDigit :digit="3" :focused="focusedDigit == 3"/>
                <CodeDigit :digit="4" :focused="focusedDigit == 4"/>
            </div>
            <UploadFilesButton v-if="!uploadEnabled" :disabled="true"/>
            <UploadFilesButton v-else />
        </div>
    </div>
</template>
