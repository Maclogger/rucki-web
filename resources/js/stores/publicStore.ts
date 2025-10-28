import {defineStore} from 'pinia';
import {ToastSeverity, useToastsStore} from "@/stores/toastsStore";

export interface PublicStoreState {
    can_login: boolean;
    can_register: boolean;
    laravel_version: string | null;
    php_version: string | null;
    constant_pairs: ConstantPair[];
}

export interface ConstantPair {
    key: string,
    value: any,
    type_name: string,
}

export const usePublicStore = defineStore("publicStore", {
    state: (): PublicStoreState => {
        return {
            can_login: false,
            can_register: false,
            laravel_version: null,
            php_version: null,
            constant_pairs: []
        };
    },

    getters: {
        isLoaded(state): boolean {
            return state.laravel_version !== null && state.php_version !== null;
        },
    },

    actions: {
        async refresh() {
            window.axios("/fetch-public-store")
                .then((response) => {
                    const data: PublicStoreState = response.data;
                    this.$patch(data);
                })
                .catch((error) => {
                    useToastsStore().displayToast({
                        message: `Nepodarilo sa získať údaje z PublicStore. ${error.message}`,
                        severity: ToastSeverity.ERROR,
                    });
                });
        },

        getConstant(constantKey: string): any | null {
            return this.constant_pairs.find((value => value.key == constantKey)).value;
        },

        getFullName(): string {
            if (!this.constant_pairs) return "";

            const titul = this.getConstant("titul");
            const meno = this.getConstant("meno");
            const priezvisko = this.getConstant("priezvisko");

            return titul + " " + meno + " " + priezvisko;
        }
    },
});
