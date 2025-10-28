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
            perPage: 6,
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
            this.files = [];
            this.status = FetchStatus.LOADING;
            this.currentPage = 0;

            try {
                const response = await window.axios.get("/get-files", {
                    params: {
                        per_page: this.perPage,
                        page: 1
                    }
                });
                const data = response.data;

                if (!data || !data.files) {
                    useToastsStore().displayToast({
                        message: "Refresh súborov bol neúspešný.",
                        severity: ToastSeverity.ERROR,
                    });
                    this.status = FetchStatus.ERROR;
                    return;
                }

                if (data.files.length <= 0) {
                    useToastsStore().displayToast({
                        message: "Na serveri sa nenašli žiadne súbory.",
                        severity: ToastSeverity.WARNING,
                    });
                    this.files = [];
                    this.refreshedAt = new Date();
                    this.status = FetchStatus.LOADED;
                    return;
                }

                this.files = data.files.map((fileResponse: FilesResponse) => {
                    if (fileResponse.mime_type.startsWith('image/')) {
                        return new Photo(fileResponse);
                    }
                    return new File(fileResponse);
                });

                // Update pagination info
                this.currentPage = data.pagination.current_page;
                this.lastPage = data.pagination.last_page;
                this.perPage = data.pagination.per_page;
                this.total = data.pagination.total;
                this.hasMore = data.pagination.has_more;

                this.refreshedAt = new Date();
                this.lastCheckedAt = new Date();
                this.status = FetchStatus.LOADED;
            } catch (error) {
                this.status = FetchStatus.ERROR;
                useToastsStore().displayToast({
                    message: "Refresh súborov bol neúspešný. " + error,
                    severity: ToastSeverity.ERROR,
                });
            }
        },

        async loadMoreFiles() {
            if (!this.hasMore || this.status === FetchStatus.LOADING) {
                return;
            }

            const nextPage = this.currentPage + 1;

            try {
                const response = await window.axios.get("/get-files", {
                    params: {
                        per_page: this.perPage,
                        page: nextPage
                    }
                });
                const data = response.data;

                if (!data || !data.files) {
                    console.error("Failed to load more files");
                    return;
                }

                const newFiles = data.files.map((fileResponse: FilesResponse) => {
                    if (fileResponse.mime_type.startsWith('image/')) {
                        return new Photo(fileResponse);
                    }
                    return new File(fileResponse);
                });

                this.files.push(...newFiles);

                // Update pagination info
                this.currentPage = data.pagination.current_page;
                this.lastPage = data.pagination.last_page;
                this.hasMore = data.pagination.has_more;

            } catch (error) {
                console.error("Failed to load more files: " + error);
                useToastsStore().displayToast({
                    message: "Nepodarilo sa načítať ďalšie súbory.",
                    severity: ToastSeverity.ERROR,
                });
            }
        },

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
                const data = response.data;

                if (!data || !data.files || data.count === 0) {
                    this.lastCheckedAt = new Date();
                    return;
                }

                const newFiles = data.files.map((fileResponse: FilesResponse) => {
                    if (fileResponse.mime_type.startsWith('image/')) {
                        return new Photo(fileResponse);
                    }
                    return new File(fileResponse);
                });

                // Add new files to the beginning
                this.files.unshift(...newFiles);
                this.total += data.count;
                this.lastCheckedAt = new Date();

                if (data.count > 0) {
                    useToastsStore().displayToast({
                        message: `Pridané ${data.count} nové súbory`,
                        severity: ToastSeverity.SUCCESS,
                    });
                }

            } catch (error) {
                console.error("Failed to check for new files: " + error);
            }
        },

        startPolling(intervalSeconds: number = 30) {
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

        setPhotosFilter(newValue: boolean) {
            this.displayPhotos = newValue;
        },
        setFilesFilter(newValue: boolean) {
            this.displayFiles = newValue;
        },

        async deleteSingleFile(file: File) {
            const originalFiles = this.files;
            this.files = this.files.filter(f => f.id != file.id);
            this.total = Math.max(0, this.total - 1);

            window.axios.post(
                "/delete-single-file",
                { id: file.id })
                .then(() => {
                    console.log("Successfully deleted file.");
                })
                .catch(() => {
                    console.error("File could not be deleted!");
                    this.files = originalFiles;
                    this.total = originalFiles.length;
                });
        },

        deleteSelectedFiles() {
            const originalFiles = this.files;
            const idsOfFilesToDelete = this.files.filter(f => f.selected).map(f => f.id);
            const deleteCount = idsOfFilesToDelete.length;
            this.files = this.files.filter(f => !f.selected);
            this.total = Math.max(0, this.total - deleteCount);

            window.axios.post(
                "/delete-multiple-files",
                { ids: idsOfFilesToDelete })
                .then(() => {
                    console.log("Successfully deleted files.");
                })
                .catch(() => {
                    console.error("Files could not be deleted!");
                    this.files = originalFiles;
                    this.total = originalFiles.length;
                });
        },
    },
});
