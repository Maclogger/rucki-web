<script setup lang="ts">
import AuthLayout from "@/Layouts/AuthLayout.vue";
import {computed, onMounted} from "vue";
import {BufferCode, usesBufferCodesStore} from "@/stores/bufferCodesStore";
import NewBufferCodeForm from "@/Pages/Buffer/BufferCodes/NewBufferCodeForm.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const bufferCodesStore = usesBufferCodesStore();
const bufferCodes = computed(() => {
    return bufferCodesStore.bufferCodes;
});


onMounted(() => {
    bufferCodesStore.fetchBufferCodes();
})

const handleDeleteBufferCode = (code: BufferCode) => {
    bufferCodesStore.deleteBufferCode(code);
}

const refreshBufferCodeStore = () => {
    bufferCodesStore.fetchBufferCodes();
}

const handleEnabledCheck = (code: BufferCode) => {
    code.enabled = !code.enabled;
    bufferCodesStore.updateCode(code);
}


</script>

<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">Buffer kódy</p>
        </template>
        <template #default>
            <div class="flex gap-4 flex-wrap">
                <NewBufferCodeForm/>
                <div class="bg-primary-dark-transparent rounded-xl w-full h-100 p-4 max-w-90">
                    <div class="flex flex-row justify-between mb-3 ml-4 items-center">
                        <p class="text-xl font-semibold ">Existujúce kódy</p>
                        <button class="btn btn-primary" @click="refreshBufferCodeStore">
                            <font-awesome-icon icon="fa-solid fa-rotate-right" />
                        </button>
                    </div>
                    <div class="flex flex-col gap-4">
                        <div v-for="bufferCode in bufferCodes"
                             class="rounded-xl bg-[#1D232A] w-full flex flex-row py-3 px-3 justify-between items-center">
                            <div class="rounded-lg p-2 bg-primary h-full">
                                <p class="p-0 font-bold">#{{ bufferCode.code }}</p>
                            </div>
                            <div class="flex flex-col items-center">
                                <p class="p-0 m-0 text-xl">{{ bufferCode.numberOfUsages }}</p>
                                <p class="p-0 m-0 text-xs text-center">Počet použití</p>
                            </div>
                            <div class="flex flex-col items-center">
                                <input type="checkbox" class="checkbox checkbox-primary" :checked="bufferCode.enabled"
                                       @input="handleEnabledCheck(bufferCode)"/>
                                <p class="p-0 m-0 text-xs text-center">Aktívny</p>
                            </div>
                            <button class="btn btn-error" @click="handleDeleteBufferCode(bufferCode)">
                                <font-awesome-icon icon="fa-solid fa-trash"/>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AuthLayout>


</template>
