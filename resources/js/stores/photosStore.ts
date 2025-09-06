import { defineStore } from 'pinia';

export interface PhotosStoreState {
    photos: PhotoType[];
    refreshedAt: Date;
};

export interface PhotoType {
    id: number,
    file_name: string,
    id_user: number,
    original_name: string,
    mime_type: string,
    readable_size: string,
    created_at: Date,
    updated_at: Date,
}

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
        async refresh() {
            const data: PhotosResponse = (await window.axios("/get-photos")).data;
            console.log(data);
            if (!data || !data.photos) {
                console.error("Refreshing photos was not successful!");
                return;
            }

            if (data.photos.length <= 0) {
                console.warn("0 photos were received from the server.");
                this.photos = [];
                this.refreshedAt = new Date();
                return;
            }

            const transformedPhotos: PhotoType[] = data.photos.map(p => ({
                id: p.id,
                file_name: p.file_name,
                id_user: p.id_user,
                original_name: p.original_name,
                mime_type: p.mime_type,
                readable_size: p.readable_size, // nicely formatted size
                created_at: new Date(p.created_at),
                updated_at: new Date(p.created_at),
            }));

            this.$patch({
                photos: transformedPhotos,
                refreshedAt: new Date(),
            });
        },
    },

});


