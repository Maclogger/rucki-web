import {defineStore} from 'pinia';
import {ToastSeverity, useToastsStore} from './toastsStore';
import {File, FilesResponse} from '@/Classes/File';
import {Photo} from '@/Classes/Photo';

interface FilesDataResponse {
    files: Array<FilesResponse>,
}

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
}


export const useFilesStore = defineStore("filesStore", {
    state: (): FilesStoreState => {
        return {
            files: [],
            refreshedAt: new Date(),
            status: FetchStatus.LOADING,
            displayPhotos: true,
            displayFiles: true,
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
                if (state.displayFiles && !(f instanceof Photo)) {
                    return true;
                }
                return false;
            });
        }
    },

    actions: {
        async fetchInitialData() {
            this.files = [];
            this.status = FetchStatus.LOADING;

            try {
                const response = await window.axios("/get-files");
                const data: FilesDataResponse = response.data;

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

                this.files = data.files.map(fileResponse => {
                    if (fileResponse.mime_type.startsWith('image/')) {
                        return new Photo(fileResponse);
                    }
                    return new File(fileResponse);
                });
                this.refreshedAt = new Date();
                this.status = FetchStatus.LOADED;
            } catch (error) {
                this.status = FetchStatus.ERROR;
                useToastsStore().displayToast({
                    message: "Refresh súborov bol neúspešný. " + error,
                    severity: ToastSeverity.ERROR,
                });
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
            window.axios.post(
                "/delete-single-file",
                { id: file.id })
                .then(() => {
                    console.log("Successfully deleted file.");
                })
                .catch(() => {
                    console.error("File could not be deleted!");
                    this.files = originalFiles;
                });
        },

        deleteSelectedFiles() {
            const originalFiles = this.files;
            const idsOfFilesToDelete = this.files.filter(f => f.selected).map(f => f.id);
            this.files = this.files.filter(f => !f.selected);

            window.axios.post(
                "/delete-multiple-files",
                { ids: idsOfFilesToDelete })
                .then(() => {
                    console.log("Successfully deleted files.");
                })
                .catch(() => {
                    console.error("Files could not be deleted!");
                    this.files = originalFiles;
                });
        },
    },
});
