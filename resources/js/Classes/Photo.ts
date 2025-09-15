import { ToastProps, ToastSeverity } from "@/stores/toastsStore";

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
    fileName: string;
    idUser: number;
    originalName: string;
    mimeType: string;
    readableSize: string;
    createdAt: Date;
    updatedAt: Date;
    selected: boolean;
    status: PhotoStatus;
    imgElement: HTMLImageElement | null;

    constructor(data: PhotoResponse);
    constructor(data: Photo);

    constructor(data: PhotoResponse | Photo) {
        const isPhotoResponse = 'file_name' in data; // Ak má 'file_name', je to PhotoResponse (snake_case)
        if (isPhotoResponse) {
            console.log("okej");
            this.id = data.id;
            this.fileName = data.file_name;
            this.idUser = data.id_user;
            this.originalName = data.original_name;
            this.mimeType = data.mime_type;
            this.readableSize = data.readable_size;
            this.createdAt = new Date(data.created_at);
            this.updatedAt = new Date(data.updated_at);
            this.selected = false;
            this.status = PhotoStatus.LOADING;
            this.imgElement = null;
        } else {
            console.log("zle");
            this.id = data.id;
            this.fileName = data.fileName;
            this.idUser = data.idUser;
            this.originalName = data.originalName;
            this.mimeType = data.mimeType;
            this.readableSize = data.readableSize;
            this.createdAt = data.createdAt;
            this.updatedAt = data.updatedAt;
            this.selected = data.selected;
            this.status = data.status;
            this.imgElement = data.imgElement;
        }
    }

    getFilePath(): string {
        return `/photos-show/${this.fileName}`;
    }

    toggleSelection() {
        this.selected = !this.selected;
    }

    createCanvasWithImage(): [HTMLCanvasElement, null] | [null, ToastProps] {
        if (!this.imgElement) {
            return [null, {
                message: `Image element could not be found by ID: ${this.fileName}.`,
                severity: ToastSeverity.ERROR,
            }];
        }

        const imageIsLoaded = this.imgElement.complete && this.imgElement.naturalHeight !== 0;
        if (!imageIsLoaded) {
            return [null, {
                message: `Image is not fully loaded yet. Cannot copy.`,
                severity: ToastSeverity.ERROR,
            }];
        }

        return [this.createCanvasWithImageImpl(), null];
    }

    private createCanvasWithImageImpl() {
        const canvas = document.createElement('canvas');
        canvas.width = this.imgElement!.naturalWidth;
        canvas.height = this.imgElement!.naturalHeight;
        const ctx = canvas.getContext('2d')!;
        ctx.drawImage(this.imgElement!, 0, 0);
        return canvas;
    }

}
