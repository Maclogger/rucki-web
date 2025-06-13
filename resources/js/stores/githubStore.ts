import {defineStore} from 'pinia';


interface GitHubRecord {
    date: Date
    contributions_count: number,
    updated_at: Date,
    created_at: Date
}

interface GitHubStoreState {
    lastUpdate: Date | null,
    gitHubRecords: GitHubRecord[],
}


export const useGitHubStore = defineStore("gitHubStore", {

    state: (): GitHubStoreState => {
        return {
            lastUpdate: null,
            gitHubRecords: [],
        };
    },

    actions: {
       setState(newState: GitHubStoreState) {
           this.$patch(newState);
       }
    }
});

