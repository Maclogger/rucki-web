import {defineStore} from 'pinia';

interface PublicStoreState {
    can_login: boolean;
    can_register: boolean;
    laravel_version: string | null;
    php_version: string | null;
    setting_pairs: SettingPair[];
}

interface SettingPair {
    key: string,
    value: any,
    type: Type,
}

interface Type {
    type_name: string
}

export const usePublicStore = defineStore("publicStore", {
    state: (): PublicStoreState => {
        return {
            can_login: false,
            can_register: false,
            laravel_version: null,
            php_version: null,
            setting_pairs: []
        };
    },

    actions: {
        setState(newState: PublicStoreState) {
            this.$patch(newState);
        },

        getSetting(settingKey: string): any | null {
            for (const value of this.setting_pairs) {
                if (value.key == settingKey) {
                    return value.value;
                }
            }
            return null;
        },

        getFullName(): string {
            if (!this.setting_pairs) return "";

            const titul = this.getSetting("titul");
            const meno = this.getSetting("meno");
            const priezvisko = this.getSetting("priezvisko");

            return titul + " " + meno + " " + priezvisko;
        }
    },
});
