import {defineStore} from 'pinia';

interface PublicStoreState {
    canLogin: boolean;
    canRegister: boolean;
    laravelVersion: string | null;
    phpVersion: string | null;
}

export const usePublicStore = defineStore("publicStore", {
    state: (): PublicStoreState => {
        return {
            canLogin: false,
            canRegister: false,
            laravelVersion: null,
            phpVersion: null,
        };
    },

    actions: {
        setState(newState: PublicStoreState) {
            this.$patch(newState);
            /*
                this.canLogin = newState.canLogin;
                this.canRegister = newState.canRegister;
                this.laravelVersion = newState.laravelVersion;
                this.phpVersion = newState.phpVersion;
            */
        }
    },
});
