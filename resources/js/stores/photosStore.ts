import { defineStore } from 'pinia';

export interface PhotosStoreState {
    photos: Photo[];
    refreshedAt: Date;
};

export interface Photo {
    src: string;
    createdAt: Date;
    width: number;
    height: number;
    size: number;
}


export const usePhotosStore = defineStore("photosStore", {
    state: (): PhotosStoreState => {
        return {
            photos: [],
            refreshedAt: new Date(),
        };
    },

    actions: {
        // setState(newState: PhotosStoreState) {
        //     this.$patch(newState);
        // },
        refresh() {
            console.log("Refreshing");
        },
    },

});


