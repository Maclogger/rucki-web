import {defineStore} from "pinia";
import {ToastSeverity, useToastsStore} from "@/stores/toastsStore";
import {InertiaForm} from "@inertiajs/vue3";

export interface BufferCode {
    id: number;
    code: string;
    numberOfUsages: number;
    enabled: boolean;
    createdAt: Date;
    updatedAt: Date;
}


interface BufferCodesStoreState {
    bufferCodes: Array<BufferCode>
}

export const usesBufferCodesStore = defineStore("bufferCodesStore", {
    state(): BufferCodesStoreState {
        return {
            bufferCodes: [],
        }
    },

    actions: {
        fetchBufferCodes() {
            window.axios("/fetch-buffer-codes")
                .then((response) => {
                    this.bufferCodes = response.data.map((bufferCodeResponse: any) => {
                        return {
                            id: bufferCodeResponse.id,
                            code: bufferCodeResponse.code,
                            numberOfUsages: bufferCodeResponse.number_of_usages,
                            enabled: bufferCodeResponse.enabled,
                            createdAt: new Date(bufferCodeResponse.created_at),
                            updatedAt: new Date(bufferCodeResponse.updated_at),
                        };
                    });
                })
                .catch((error) => {
                    useToastsStore().displayToast({
                        message: `Nepodarilo sa získať údaje kódov pre buffer. ${error.message}`,
                        severity: ToastSeverity.ERROR,
                    });
                });
        },

        createNewBufferCode(form: InertiaForm<{ code: string, enabled: false }>) {
            this.bufferCodes.push();
            form.post("/new-buffer-code", {
                onSuccess: () => {
                    useToastsStore().displayToast({
                        message: "Kód bol úspešne vytvorený.",
                        severity: ToastSeverity.SUCCESS,
                    });
                    this.fetchBufferCodes();
                },
                onError: (error) => {
                    useToastsStore().displayToast({
                        message: `Kód sa nepodarilo vytvoriť. ${error.message}`,
                        severity: ToastSeverity.ERROR,
                    });
                },
                onFinish: () => {
                    form.reset('code');
                    form.code = "";
                    form.enabled = false;
                },
            });
        },

        deleteBufferCode(code: BufferCode) {
            this.bufferCodes.splice(this.bufferCodes.indexOf(code), 1);
            window.axios.post("/delete-buffer-code", {id: code.id})
                .then(() => {
                    useToastsStore().displayToast({
                        message: "Kód bol úspešne zmazaný.",
                        severity: ToastSeverity.SUCCESS,
                    });
                })
                .catch((error) => {
                    this.bufferCodes.push(code);
                    useToastsStore().displayToast({
                        message: `Kód sa nepodarilo zmazať: ${error.message}`,
                        severity: ToastSeverity.ERROR,
                    });
                });
        },

        updateCode(code: BufferCode) {
            const index = this.bufferCodes.findIndex((bufferCode: BufferCode) => bufferCode.id === code.id);
            if (index !== -1) {
                this.bufferCodes[index] = code;
            }
            window.axios.post("/update-buffer-code", {
                id: code.id,
                code: code.code,
                enabled: code.enabled
            })
                .then(() => {
                    useToastsStore().displayToast({
                        message: "Kód úspešne aktualizovaný.",
                        severity: ToastSeverity.SUCCESS,
                    });
                })
                .catch((error) => {
                    useToastsStore().displayToast({
                        message: `Aktualizácia kódu sa nepodarila. ${error.message}`,
                        severity: ToastSeverity.ERROR,
                    });
                });
        },
    },
})
