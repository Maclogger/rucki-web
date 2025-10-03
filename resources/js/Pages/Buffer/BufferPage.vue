<script setup lang="ts">

import CodeDigit from "@/Pages/Buffer/CodeDigit.vue";
import UploadFilesButton from "@/Pages/Photos/ControlPanel/UploadFilesButton.vue";
import {computed, onMounted, onUnmounted, ref} from "vue";

const NUMBER_OF_DIGITS = 4;
const code = ref("");

const props = defineProps({
    code: String
});


const handleKeyDown = (event: KeyboardEvent) => {
    const key = event.key;
    if (/^[a-zA-Z0-9]$/.test(key)) {
        addNewLetter(key.toUpperCase());
    } else if (key === "Backspace") {
        code.value = code.value.slice(0, -1);
    }
}

const addNewLetter = (key: string) => {
    if (wholeCodeIsTyped.value) {
        return;
    }
    code.value += key;
}

const wholeCodeIsTyped = computed(() => {
    return code.value.length >= NUMBER_OF_DIGITS;
})


onMounted(() => {
    window.addEventListener("keydown", handleKeyDown);
    if (props.code) {
        code.value = props.code.toUpperCase();
    }
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeyDown);
});


</script>

<template>

    <div class="h-[100vh] flex flex-col justify-center items-center p-10">
        <div class="flex flex-col bg-primary-dark-transparent rounded-lg mx-auto gap-8 p-14">
            <div>
                <p class="text-3xl">Marekov Buffer</p>
                <p class="text-lg">Zadajte kód</p>
            </div>
            <div class="flex flex-row gap-4">
                <CodeDigit v-for="digitNumber in NUMBER_OF_DIGITS"
                           :digit="digitNumber"
                           :focused="digitNumber == code.length + 1"
                           :value="code.at(digitNumber - 1)"/>
            </div>
            <UploadFilesButton v-if="!wholeCodeIsTyped" :disabled="true"/>
            <UploadFilesButton v-else/>
        </div>
    </div>
</template>
