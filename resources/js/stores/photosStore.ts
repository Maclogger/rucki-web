import { defineStore } from 'pinia';
import { Photo, PhotoResponse } from '../Classes/Photo';
import { ToastSeverity, useToastsStore } from './toastsStore';

interface PhotosResponse {
    photos: Array<PhotoResponse>,
}

export enum FetchStatus {
    LOADING,
    LOADED,
    ERROR,
}

export interface PhotosStoreState {
    photos: Photo[];
    refreshedAt: Date;
    status: FetchStatus;
    websocketConnection: boolean;
}


export const usePhotosStore = defineStore("photosStore", {
    state: (): PhotosStoreState => {
        return {
            photos: [],
            refreshedAt: new Date(),
            status: FetchStatus.LOADING,
            websocketConnection: false,
        };
    },

    getters: {
        getSelectedCount(state): number {
            return state.photos.filter(p => p.selected).length;
        }
    },

    actions: {
        // setState(newState: PhotosStoreState) {
        //     this.$patch(newState);
        // },
        async refresh() {
            this.photos = [];
            this.status = FetchStatus.LOADING;

            try {
                const response = await window.axios("/get-photos");
                const data: PhotosResponse = response.data;

                if (!data || !data.photos) {
                    useToastsStore().displayToast({
                        message: "Refreshing photos was not successful!",
                        severity: ToastSeverity.ERROR,
                    });
                    this.status = FetchStatus.ERROR; // Nastav status na ERROR
                    return;
                }

                if (data.photos.length <= 0) {
                    useToastsStore().displayToast({
                        message: "0 photos were received from the server.",
                        severity: ToastSeverity.WARNING,
                    });
                    this.photos = [];
                    this.refreshedAt = new Date();
                    this.status = FetchStatus.LOADED;
                    return;
                }

                const transformedPhotos: Photo[] = data.photos.map(p => new Photo(p));

                this.photos = transformedPhotos;
                this.refreshedAt = new Date();
                this.status = FetchStatus.LOADED;
            } catch (error) {
                console.error("Error refreshing photos:", error);
                this.status = FetchStatus.ERROR;
            }
        },


        async deleteSinglePhoto(photo: Photo) {
            const originalPhotos = this.photos;
            this.photos = this.photos.filter(p => p.id != photo.id); // removing the photo from FrontEnd
            window.axios.post(
                "/delete-single-photo",
                { id: photo.id })
                .then(() => {
                    console.log("Successfuly deleted");
                })
                .catch(() => {
                    console.error("Photo could not be deleted!");
                    this.photos = originalPhotos;
                });
        },

        newPhotoAdded(photo: Photo) {
            const existingPhotoIndex = this.photos.findIndex(p => p.id === photo.id);
            const photoExistsAlready = existingPhotoIndex >= 0;
            if (photoExistsAlready) {
                photo.selected = this.photos[existingPhotoIndex].selected;
                this.photos[existingPhotoIndex] = photo;
            } else {
                this.photos.unshift(photo); // adding at the beginning
            }
            this.refreshedAt = new Date();
        },

        photoDeleted(photoId: number) {
            this.photos = this.photos.filter(p => p.id != photoId);
        },

        deleteSelectedPhotos() {
            const originalPhotos = this.photos;
            const idsOfPhotosToDelete = this.photos.filter(p => p.selected).map(p => p.id);
            this.photos = this.photos.filter(p => !p.selected);

            window.axios.post(
                "/delete-multiple-photos",
                { ids: idsOfPhotosToDelete })
                .then(() => {
                    console.log("Successfuly deleted");
                })
                .catch(() => {
                    console.log("Photos could not be deleted!");
                    this.photos = originalPhotos;
                });
        },

    },

});



