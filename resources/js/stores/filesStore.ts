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
    websocketConnection: boolean;
}


export const useFilesStore = defineStore("filesStore", {
    state: (): FilesStoreState => {
        return {
            files: [],
            refreshedAt: new Date(),
            status: FetchStatus.LOADING,
            websocketConnection: false,
        };
    },

    getters: {
        getSelectedCount(state): number {
            return state.files.filter(f => f.isSelected()).length;
        },
        getSelectedFiles(state): File[] {
            return state.files.filter(f => f.isSelected());
        },
        areAllFilesSelected(state): boolean {
            return state.files.length > 0 && state.files.every(file => file.isSelected());
        },
        getVisibleFiles(state): File[] {
            return state.files.filter(f => f.isVisible());
        }
    },

    actions: {
        async refresh() {
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

        toggleSelectAll() {
            // TODO: Zmazať, nebude sa používať:
            // let newSelection: boolean = true;
            // if (this.areAllFilesSelected) {
            //     newSelection = false;
            // }
            // this.files.forEach(f => {
            //     f.selected = newSelection;
            // });
        },

        enablePicturesFilter(){
            this.files.forEach(file => {
                if (file instanceof Photo) {
                    file.setVisible();
                }
            });
        },

        disablePicturesFilter() {
            this.files.forEach(file => {
                if (file instanceof Photo) {
                    file.setHidden();
                }
            });
        },

        enableFilesFilter(){
            this.files.forEach(file => {
                if (!(file instanceof Photo)) {
                    file.setVisible();
                }
            });
        },

        disableFilesFilter() {
            this.files.forEach(file => {
                if (!(file instanceof Photo)) {
                    file.setHidden();
                }
            });
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
            const idsOfFilesToDelete = this.files.filter(f => f.isSelected()).map(f => f.id);
            this.files = this.files.filter(f => !f.isSelected());

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
