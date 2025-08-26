import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

export interface User {
    id: number,
    username: string;
}

export interface AuthState {
    user: User | null;
}

export const useUserStore = defineStore("userStore", {
    state(): AuthState {
        return {
            user: null
        };
    },

    getters: {
        isLoggedIn: (state) => !!state.user,
        getUser: (state) => state.user,
    },

    actions: {
        setUser(pUser: User | null) {
            this.user = pUser;
        },

        logout(): void {
            if (!this.user) return;

            router.post(
                route("logout"),
                {}, // no need to pass arguments, request will contain $request->user() anyways
                {
                    onFinish: () => {
                        this.setUser(null);
                    },
                    onError: () => {
                        console.error("Logout did not work.");
                    }
                })
        },
    },
});








