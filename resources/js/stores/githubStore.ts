import {defineStore} from 'pinia';
import axios from "axios";


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
    week_count: number, // how many columns there will be in chart
    github_records: GithubRecord[],
}

export interface GithubStoreState {
    last_update: Date | null,
    selected_year: number, // which year is currently selected to display the graph
    github_year_charts: Map<number, GithubYearChart>, // key is the year
    github_year_from: number // start year
}


export const useGithubStore = defineStore("githubStore", {
    state: (): GithubStoreState => {
        return {
            last_update: null,
            selected_year: 2025,
            github_year_charts: new Map<number, GithubYearChart>(),
            github_year_from: 2017 // default year is 2017
        };
    },

    actions: {
        setState(newState: GithubStoreState) {
            this.$patch(newState);
        },

        async fetchGithubYearChart(year: number) {
            const githubChartData = (await window.axios("/fetch-githubchartdata/" + year)).data;
            console.log(githubChartData);

            const newGithubYearChart = {
                year: year,
                week_count: githubChartData.week_count,
                github_records: githubChartData.github_records,
            };

            this.github_year_charts.set(year, newGithubYearChart);
        },

        async setNewSelectedYear(newSelectedYear: number) {
            if (!this.github_year_charts.has(newSelectedYear)) {
                await this.fetchGithubYearChart(newSelectedYear);
            }

            this.selected_year = newSelectedYear;
        }

    }
});

