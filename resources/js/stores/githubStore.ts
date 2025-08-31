import { defineStore } from 'pinia';

export interface GithubRecord {
    date: Date
    contributions_count: number,
    updated_at: Date,
    created_at: Date,
    year_level: number, // highlight thickness ~ <0, 1>
}

export interface GithubYearChart {
    year: number, // year to select
    total_contributions: number,
    github_records: GithubRecord[],
}

export interface GithubStoreState {
    last_update: Date | null,
    selected_year: number, // which year is currently selected to display the graph
    data_by_year: Map<number, GithubYearChart>, // key is the year
    first_year: number, // start year
}

interface GitHubStoreRefreshData {
    last_update: string, // date
    selected_year: number,
    first_year: number,
    data_by_year: {
        [year: number]: {
            year: number,
            total_contributions: number,
            github_records: Array<{
                date: string, // date
                contributions_count: number,
                updated_at: string, // date
                created_at: string, // date
                year_level: number,
            }>
        }
    }
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
        _setState(newState: GithubStoreState) {
            this.$patch(newState);
        },

        async fetchGithubYearChart(year: number) {
            const githubChartData = (await window.axios("/fetch-github-chart-data/" + year)).data;

            const githubRecords: GithubRecord[] = githubChartData.github_records.map((record: any) => ({
                ...record,
                date: new Date(record.date),
                updated_at: new Date(record.updated_at),
                created_at: new Date(record.created_at),
            }));

            const newGithubYearChart: GithubYearChart = {
                year: year,
                total_contributions: githubChartData.total_contributions,
                github_records: githubRecords,
            };

            this.data_by_year.set(year, newGithubYearChart);
        },

        async setNewSelectedYear(newSelectedYear: number) {
            if (!this.data_by_year.has(newSelectedYear)) {
                await this.fetchGithubYearChart(newSelectedYear);
            }

            this.selected_year = newSelectedYear;
        },

        async refresh() {
            const data: GitHubStoreRefreshData = (await window.axios("/refresh-github-chart-data")).data;
            console.log(data);
            const dataByYear = new Map<number, GithubYearChart>();

            Object.entries(data.data_by_year).forEach(([year, yearData]) => {
                const githubRecords: GithubRecord[] = yearData.github_records.map((record: any) => ({
                    ...record,
                    date: new Date(record.date),
                    updated_at: new Date(record.updated_at),
                    created_at: new Date(record.created_at),
                }));

                const githubYearChart: GithubYearChart = {
                    year: Number(year),
                    total_contributions: Number(yearData.total_contributions),
                    github_records: githubRecords,
                }

                dataByYear.set(Number(year), githubYearChart);
            });

            const githubStoreState: GithubStoreState = {
                last_update: new Date(data.last_update),
                selected_year: data.selected_year,
                first_year: Number(data.first_year),
                data_by_year: dataByYear,
            };

            this._setState(githubStoreState);
        }
    }
});

