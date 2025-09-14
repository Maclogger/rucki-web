export enum PhotoStatus {
    LOADING, // when the <img> tag is loading the photo
    LOADED,
}

export interface PhotoResponse {
    id: number,
    file_name: string,
    id_user: number,
    original_name: string,
    mime_type: string,
    size: number,
    readable_size: string,
    created_at: string,
    updated_at: string,
}

export class Photo {
    id: number;
    file_name: string;
    id_user: number;
    original_name: string;
    mime_type: string;
    readable_size: string;
    created_at: Date;
    updated_at: Date;
    selected: boolean;
    status: PhotoStatus;
    imgElement: HTMLImageElement | null; // Referencia na DOM element

    constructor(data: PhotoResponse | Photo) {
        this.id = data.id;
        this.file_name = data.file_name;
        this.id_user = data.id_user;
        this.original_name = data.original_name;
        this.mime_type = data.mime_type;
        this.readable_size = data.readable_size;
        this.created_at = new Date(data.created_at);
        this.updated_at = new Date(data.updated_at);
        this.selected = (data as Photo).selected !== undefined ? (data as Photo).selected : false;
        this.status = (data as Photo).status !== undefined ? (data as Photo).status : PhotoStatus.LOADING;
        this.imgElement = (data as Photo).imgElement !== undefined ? (data as Photo).imgElement : null;
    }

    getFilePath(): string {
        return `/photos-show/${this.file_name}`;
    }

    toggleSelection() {
        this.selected = !this.selected;
    }
}
