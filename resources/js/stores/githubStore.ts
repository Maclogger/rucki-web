import {defineStore} from 'pinia';
import axios from "axios";


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
    git_hub_year_charts: Map<number, GitHubYearChart>, // key is the year
    git_hub_year_from: number // start year
}


export const useGitHubStore = defineStore("gitHubStore", {
    state: (): GitHubStoreState => {
        return {
            last_update: null,
            currently_displayed_year: 2025,
            git_hub_year_charts: new Map<number, GitHubYearChart>(),
            git_hub_year_from: 2017 // default year is 2017
        };
    },

    actions: {
        setState(newState: GitHubStoreState) {
            this.$patch(newState);
        },

        async fetchGitHubYearChart(year: number) {
            const gitHubChartData = (await window.axios("/fetch-githubchartdata/" + year)).data;
            console.log(gitHubChartData);

            const newGitHubYearChart = {
                year: year,
                week_count: gitHubChartData.week_count,
                git_hub_records: gitHubChartData.git_hub_records,
            };

            this.git_hub_year_charts.set(year, newGitHubYearChart);
        },

        async setNewSelectedYear(newSelectedYear: number) {
            if (!this.git_hub_year_charts.has(newSelectedYear)) {
                await this.fetchGitHubYearChart(newSelectedYear);
            }

            this.currently_displayed_year = newSelectedYear;
        }

    }
});

