import { defineStore } from 'pinia';
import { ref, Ref } from 'vue';

// Raw data from response:
interface PhotosResponse {
    photos: Array<{
        id: number,
        file_name: string,
        id_user: number,
        original_name: string,
        mime_type: string,
        size: number,
        readable_size: string,
        created_at: string,
        updated_at: string,
    }>,
}

// Formatted data:
export interface PhotoType {
    id: number,
    file_name: string,
    id_user: number,
    original_name: string,
    mime_type: string,
    readable_size: string,
    created_at: Date,
    updated_at: Date,
    selected: boolean,
    status: SinglePhotoStatus,
    imgElement?: HTMLImageElement | null,
}


export enum FetchStatus {
    LOADING,
    LOADED,
    ERROR,
}

export enum SinglePhotoStatus {
    LOADING, // when the <img> tag is loading the photo
    LOADED,
}

export interface PhotosStoreState {
    photos: PhotoType[];
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
                    console.error("Refreshing photos was not successful!");
                    this.status = FetchStatus.ERROR; // Nastav status na ERROR
                    return;
                }

                if (data.photos.length <= 0) {
                    console.warn("0 photos were received from the server.");
                    this.photos = [];
                    this.refreshedAt = new Date();
                    this.status = FetchStatus.LOADED;
                    return;
                }

                const transformedPhotos: PhotoType[] = data.photos.map(p => ({
                    id: p.id,
                    file_name: p.file_name,
                    id_user: p.id_user,
                    original_name: p.original_name,
                    mime_type: p.mime_type,
                    readable_size: p.readable_size,
                    created_at: new Date(p.created_at),
                    updated_at: new Date(p.updated_at),
                    selected: false,
                    status: SinglePhotoStatus.LOADING, // the loading starts when <img> is rendered
                    imgElement: ref<HTMLImageElement | null>(null).value,
                }));

                this.photos = transformedPhotos;
                this.refreshedAt = new Date();
                this.status = FetchStatus.LOADED;
            } catch (error) {
                console.error("Error refreshing photos:", error);
                this.status = FetchStatus.ERROR;
            }
        },


        async deleteSinglePhoto(photo: PhotoType) {
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

        newPhotoAdded(photo: PhotoType) {
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

        createCanvasForPhoto(photo: PhotoType) {

        },

    },

});



