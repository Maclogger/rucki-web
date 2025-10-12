import { ToastProps, ToastSeverity } from "@/stores/toastsStore";
import { File, FilesResponse } from "./File";

export enum PhotoStatus {
    LOADING, // when the <img> tag is loading the photo
    LOADED,
}


export class Photo extends File {
    status: PhotoStatus;
    imgElement: HTMLImageElement | null;

    constructor(data: FilesResponse);
    constructor(data: Photo);

    constructor(data: FilesResponse | Photo) {
        if ('file_name' in data) { // Dáta z API (FilesResponse - snake_case)
            // Predpokladáme, že FilesResponse obsahuje všetky potrebné dáta pre Photo,
            // takže stačí predať data do konštruktora predka.
            super(data);
            this.status = PhotoStatus.LOADING;
            this.imgElement = null;
        } else { // Existujúci objekt Photo (camelCase)
            super(data);
            this.status = data.status;
            this.imgElement = data.imgElement;
        }

        // Ak sa vytvorí Photo z FilesResponse a mime_type nie je image,
        // mohol by si tu pridať logiku na vyhodenie chyby alebo konverziu na File.
        // Pre jednoduchosť predpokladáme, že FilesResponse pre Photo bude vždy image.
    }

    getFilePath(): string {
        return `/photos-show/${this.fileName}`;
    }

    createCanvasWithImage(): [HTMLCanvasElement, null] | [null, ToastProps] {
        if (!this.imgElement) {
            return [null, {
                message: `Image element could not be found for photo: ${this.fileName}.`,
                severity: ToastSeverity.ERROR,
            }];
        }

        const imageIsLoaded = this.imgElement.complete && this.imgElement.naturalHeight !== 0;
        if (!imageIsLoaded) {
            return [null, {
                message: `Image is not fully loaded yet. Cannot copy photo.`,
                severity: ToastSeverity.ERROR,
            }];
        }

        return [this.createCanvasWithImageImpl(), null];
    }

    private createCanvasWithImageImpl(): HTMLCanvasElement {
        const canvas = document.createElement('canvas');
        canvas.width = this.imgElement!.naturalWidth;
        canvas.height = this.imgElement!.naturalHeight;
        // Používame '2d' kontext pre prácu s obrázkami. '3d' je pre WebGL.
        const ctx = canvas.getContext('2d')!;
        ctx.drawImage(this.imgElement!, 0, 0); // Oprava - drawImage potrebuje x a y pozíciu, nie len 1
        return canvas;
    }
}
