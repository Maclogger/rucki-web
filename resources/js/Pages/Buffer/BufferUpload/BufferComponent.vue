<script setup lang="ts">

import CodeDigit from "@/Pages/Buffer/BufferUpload/CodeDigit.vue";
import UploadFilesButton from "@/Pages/Photos/ControlPanel/UploadFilesButton.vue";
import {computed, onMounted, onUnmounted, ref} from "vue";
import {EmojiHelper} from "@/Classes/EmojiHelper";
import {usePublicStore} from "@/stores/publicStore";

const code = ref("");
const publicStore = usePublicStore();

const bufferCodeLength = computed(() => {
    return parseInt(publicStore.getConstant("bufferCodeLength") ?? "4");
})

const props = defineProps({
    code: String,
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
    return code.value.length >= bufferCodeLength.value;
})

const setCodeFromUrl = (codeFromUrl: string) => {
    code.value = codeFromUrl
        .replace(/[^a-zA-Z0-9]/g, '')
        .slice(0, bufferCodeLength.value)
        .toUpperCase();
}

const emoji = ref(EmojiHelper.getRandomEmoji());

onMounted(() => {
    if (props.code) {
        setCodeFromUrl(props.code);
    }
    window.addEventListener("keydown", handleKeyDown);
    displayKeyboard();
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeyDown);
});

const handleUploadFinished = () => {
    code.value = "";
}

const inputRef = ref<HTMLInputElement | null>(null);

const displayKeyboard = () => {
    inputRef.value?.focus();
}

</script>

<template>
    <div class="flex flex-col md:justify-center items-center h-full md:p-6">
        <!-- Skrytý input pre mobilné klávesnice -->
        <input
            ref="inputRef"
            type="text"
            inputmode="text"
            autocomplete="one-time-code"
            maxlength="4"
            class="absolute opacity-0 pointer-events-none"
            @input="e => setCodeFromUrl((e.target as HTMLInputElement).value)"
            @keydown="handleKeyDown"
            tabindex="0"
        />

        <!-- Hlavná časť -->
        <div class="flex flex-col bg-primary-dark-transparent rounded-lg gap-8 p-6">
            <div>
                <p class="text-3xl">Marekov Buffer {{ emoji }}</p>
                <p class="text-lg">Zadaj kód</p>
            </div>
            <div class="flex flex-row lg:gap-4 gap-2 ">
                <CodeDigit v-for="digitNumber in bufferCodeLength"
                           :digit="digitNumber"
                           :focused="digitNumber == code.length + 1"
                           :value="code.at(digitNumber - 1)"
                           @click="displayKeyboard"
                />
            </div>
            <UploadFilesButton v-if="!wholeCodeIsTyped" :disabled="true"/>
            <UploadFilesButton v-else :bufferCode="code" @uploadFinished="handleUploadFinished"/>
        </div>
    </div>
</template>
