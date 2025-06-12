import {defineStore} from 'pinia';

export const useGitHubStore = defineStore("gitHubStore", {
    state: () => ({
        lastUpdate: null as Date | null,
        gitHubRecords: [] as GitHubRecord[],
    }),
});

interface GitHubRecord {
    date: Date
    contributions_count: number,
    updated_at: Date,
    created_at: Date
}
