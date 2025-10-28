import {defineStore} from 'pinia';
import {ToastSeverity, useToastsStore} from './toastsStore';
import {File, FilesResponse} from '@/Classes/File';
import {Photo} from '@/Classes/Photo';

export enum FetchStatus {
    LOADING,
    LOADED,
    ERROR,
}

export interface FilesStoreState {
    files: File[];
    refreshedAt: Date;
    status: FetchStatus;
    displayPhotos: boolean;
    displayFiles: boolean;
    // Pagination
    currentPage: number;
    lastPage: number;
    perPage: number;
    total: number;
    hasMore: boolean;
    // Polling
    pollingInterval: number | null;
    lastCheckedAt: Date | null;
}

function mapResponseToFile(fileResponse: FilesResponse): File {
    return fileResponse.mime_type.startsWith('image/')
        ? new Photo(fileResponse)
        : new File(fileResponse);
}

function mapResponseToFiles(filesResponses: FilesResponse[]): File[] {
    return filesResponses.map(mapResponseToFile);
}


export const useFilesStore = defineStore("filesStore", {
    state: (): FilesStoreState => {
        return {
            files: [],
            refreshedAt: new Date(),
            status: FetchStatus.LOADING,
            displayPhotos: true,
            displayFiles: true,
            // Pagination
            currentPage: 0,
            lastPage: 0,
            perPage: 10, // will be later set by the value from DB
            total: 0,
            hasMore: false,
            // Polling
            pollingInterval: null,
            lastCheckedAt: null,
        };
    },

    getters: {
        getSelectedCount(state): number {
            return state.files.filter(f => f.selected).length;
        },
        getSelectedFiles(state): File[] {
            return state.files.filter(f => f.selected);
        },
        areAllFilesSelected(state): boolean {
            return state.files.length > 0 && state.files.every(file => file.isSelected());
        },
        getVisibleFiles(state): File[] {
            return state.files.filter(f => {
                if (state.displayPhotos && f instanceof Photo) {
                    return true;
                }
                return state.displayFiles && !(f instanceof Photo);
            });
        }
    },

    actions: {
        async fetchInitialData() {
            this.resetState();
            await this.fetchFilesPage(1);
        },

        async loadMoreFiles() {
            if (!this.canLoadMore()) {
                return;
            }

            await this.fetchFilesPage(this.currentPage + 1, true);
        },

        async fetchFilesPage(page: number, append: boolean = false) {
            if (!append) {
                this.status = FetchStatus.LOADING;
            }

            try {
                const response = await window.axios.get("/get-files", {
                    params: {
                        per_page: this.perPage,
                        page
                    }
                });

                this.handleFilesResponse(response.data, append);
            } catch (error) {
                this.handleFetchError(error);
            }
        },

        handleFilesResponse(data: any, append: boolean) {
            if (!data || !data.files) {
                useToastsStore().displayToast({
                    message: "Refresh súborov bol neúspešný.",
                    severity: ToastSeverity.ERROR,
                });
                this.status = FetchStatus.ERROR;
                return;
            }

            if (data.files.length === 0 && !append) {
                this.handleEmptyResponse();
                return;
            }

            const newFiles = mapResponseToFiles(data.files);

            if (append) {
                this.files.push(...newFiles);
            } else {
                this.files = newFiles;
            }

            this.updatePaginationData(data.pagination);
            this.updateTimestamps();
            this.status = FetchStatus.LOADED;
        },

        handleEmptyResponse() {
            useToastsStore().displayToast({
                message: "Na serveri sa nenašli žiadne súbory.",
                severity: ToastSeverity.WARNING,
            });
            this.files = [];
            this.updateTimestamps();
            this.status = FetchStatus.LOADED;
        },

        handleFetchError(error: any) {
            this.status = FetchStatus.ERROR;
            const message = error ? `Refresh súborov bol neúspešný. ${error}` : "Nepodarilo sa načítať súbory.";
            useToastsStore().displayToast({
                message,
                severity: ToastSeverity.ERROR,
            });
        },

        // ========== Polling Methods ==========

        async checkForNewFiles() {
            if (!this.lastCheckedAt) {
                return;
            }

            try {
                const response = await window.axios.get("/get-latest-files", {
                    params: {
                        since: this.lastCheckedAt.toISOString(),
                        limit: this.perPage
                    }
                });

                this.handleNewFilesResponse(response.data);
            } catch (error) {
                console.error("Failed to check for new files: " + error);
            }
        },

        handleNewFilesResponse(data: any) {
            if (!data || !data.files || data.count === 0) {
                this.lastCheckedAt = new Date();
                return;
            }

            const newFiles = mapResponseToFiles(data.files);
            this.files.unshift(...newFiles);
            this.total += data.count;
            this.lastCheckedAt = new Date();

            this.notifyNewFiles(data.count);
        },

        notifyNewFiles(count: number) {
            useToastsStore().displayToast({
                message: `Pridané ${count} nové súbory`,
                severity: ToastSeverity.SUCCESS,
            });
        },

        setPollingPageSize(newPageSize: number = 10) {
            this.perPage = newPageSize;
        },

        startPolling(intervalSeconds: number = 30) {
            console.log("Store: Starting polling for new files every " + intervalSeconds + " seconds.");
            if (!intervalSeconds || intervalSeconds <= 0) {
                intervalSeconds = 30;
            }

            if (this.pollingInterval !== null) {
                this.stopPolling();
            }

            this.pollingInterval = window.setInterval(() => {
                this.checkForNewFiles();
            }, intervalSeconds * 1000);
        },

        stopPolling() {
            if (this.pollingInterval !== null) {
                clearInterval(this.pollingInterval);
                this.pollingInterval = null;
            }
        },

        // ========== Filter Methods ==========

        setPhotosFilter(newValue: boolean) {
            this.displayPhotos = newValue;
        },

        setFilesFilter(newValue: boolean) {
            this.displayFiles = newValue;
        },

        // ========== Delete Methods ==========

        async deleteSingleFile(file: File) {
            const originalState = this.captureState();

            this.removeFileFromList(file.id);
            this.decrementTotal();

            try {
                await window.axios.post("/delete-single-file", { id: file.id });
                useToastsStore().displayToast({
                    message: "Súbor bol úspešne vymazaný.",
                    severity: ToastSeverity.SUCCESS,
                });

            } catch (error) {
                useToastsStore().displayToast({
                    message: "Súbor sa nepodarilo zmazať!",
                    severity: ToastSeverity.ERROR,
                });
                this.restoreState(originalState);
            }
        },

        async deleteSelectedFiles() {
            const originalState = this.captureState();
            const idsToDelete = this.files.filter(f => f.selected).map(f => f.id);

            this.removeSelectedFiles();
            this.decrementTotal(idsToDelete.length);

            try {
                await window.axios.post("/delete-multiple-files", { ids: idsToDelete });
                console.log("Successfully deleted files.");
            } catch (error) {
                console.error("Files could not be deleted!");
                this.restoreState(originalState);
            }
        },

        resetState() {
            this.files = [];
            this.currentPage = 0;
            this.status = FetchStatus.LOADING;
        },

        updatePaginationData(pagination: any) {
            this.currentPage = pagination.current_page;
            this.lastPage = pagination.last_page;
            this.perPage = pagination.per_page;
            this.total = pagination.total;
            this.hasMore = pagination.has_more;
        },

        updateTimestamps() {
            this.refreshedAt = new Date();
            this.lastCheckedAt = new Date();
        },

        canLoadMore(): boolean {
            return this.hasMore && this.status !== FetchStatus.LOADING;
        },

        removeFileFromList(fileId: number) {
            this.files = this.files.filter(f => f.id !== fileId);
        },

        removeSelectedFiles() {
            this.files = this.files.filter(f => !f.selected);
        },

        decrementTotal(amount: number = 1) {
            this.total = Math.max(0, this.total - amount);
        },

        captureState() {
            return {
                files: [...this.files],
                total: this.total
            };
        },

        restoreState(state: { files: File[], total: number }) {
            this.files = state.files;
            this.total = state.total;
        },
    },
});
