import {defineStore} from 'pinia';


export interface GitHubRecord {
    date: Date
    contributions_count: number,
    updated_at: Date,
    created_at: Date,
    day_of_the_week: number, // row
    week_of_the_year: number, // column
    year_level: number, // highlight thickness ~ <0, 1>
}

export interface GitHubYearChart {
    year: number, // year to select
    week_count: number, // how many columns there will be in chart
    git_hub_records: GitHubRecord[],
}

export interface GitHubStoreState {
    last_update: Date | null,
    currently_displayed_year: number, // which year is currently selected to display the graph
    git_hub_year_chart: Map<number, GitHubYearChart>, // key is the year
}


export const useGitHubStore = defineStore("gitHubStore", {
    state: (): GitHubStoreState => {
        return {
            last_update: null,
            currently_displayed_year: 2025,
            git_hub_year_chart: new Map<number, GitHubYearChart>()
        };
    },

    actions: {
       setState(newState: GitHubStoreState) {
           this.$patch(newState);
       }
    }
});

