import {defineStore} from 'pinia';

export interface GithubRecord {
    date: Date
    contributions_count: number,
    updated_at: Date,
    created_at: Date,
    day_of_the_week: number, // row
    week_of_the_year: number, // column
    year_level: number, // highlight thickness ~ <0, 1>
}

export interface GithubYearChart {
    year: number, // year to select
    github_records: GithubRecord[],
}

export interface GithubStoreState {
    last_update: Date | null,
    selected_year: number, // which year is currently selected to display the graph
    data_by_year: Map<number, GithubYearChart>, // key is the year
    first_year: number, // start year
}


export const useGithubStore = defineStore("githubStore", {
    state: (): GithubStoreState => {
        return {
            last_update: null,
            selected_year: 2025,
            first_year: 2017, // default year is 2017
            data_by_year: new Map<number, GithubYearChart>(),
        };
    },

    actions: {
        setState(newState: GithubStoreState) {
            this.$patch(newState);
        },

        async fetchGithubYearChart(year: number) {
            const githubChartData = (await window.axios("/fetch-github-chart-data/" + year)).data;
            console.log(githubChartData);

            const newGithubYearChart = {
                year: year,
                github_records: githubChartData.github_records,
            };

            this.data_by_year.set(year, newGithubYearChart);
        },

        async setNewSelectedYear(newSelectedYear: number) {
            if (!this.data_by_year.has(newSelectedYear)) {
                await this.fetchGithubYearChart(newSelectedYear);
            }

            this.selected_year = newSelectedYear;
        }

    }
});

