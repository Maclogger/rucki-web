import {defineStore} from 'pinia';

interface PublicStoreState {
    canLogin: boolean;
    canRegister: boolean;
    laravelVersion: string | null;
    phpVersion: string | null;
    settingPairs: SettingPair[];
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
            canLogin: false,
            canRegister: false,
            laravelVersion: null,
            phpVersion: null,
            settingPairs: []
        };
    },

    actions: {
        setState(newState: PublicStoreState) {
            this.$patch(newState);
        },

        getSetting(settingKey: string): any | null {
            for (const value of this.settingPairs) {
                if (value.key == settingKey) {
                    return value.value;
                }
            }
            return null;
        },
    },
});
