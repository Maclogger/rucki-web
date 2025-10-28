import {ToastSeverity, useToastsStore} from "@/stores/toastsStore";

const DOWNLOAD_DELAY_MS = 500;


export interface FilesResponse {
    id: number,
    file_name: string,
    id_user: number,
    original_name: string,
    mime_type: string,
    size: number,
    readable_size: string,
    created_at: string,
    updated_at: string,
    is_photo: boolean,
}

export class File {
    id: number;
    fileName: string;
    idUser: number;
    originalName: string;
    mimeType: string;
    size: number;
    readableSize: string;
    createdAt: Date;
    updatedAt: Date;
    selected: boolean;

    constructor(data: FilesResponse);
    constructor(data: File);

    constructor(data: FilesResponse | File) {
        if ('file_name' in data) {
            this.id = data.id;
            this.fileName = data.file_name;
            this.idUser = data.id_user;
            this.originalName = data.original_name;
            this.mimeType = data.mime_type;
            this.size = data.size;
            this.readableSize = data.readable_size;
            this.createdAt = new Date(data.created_at);
            this.updatedAt = new Date(data.updated_at);
            this.selected = false;
        } else {
            this.id = data.id;
            this.fileName = data.fileName;
            this.idUser = data.idUser;
            this.originalName = data.originalName;
            this.mimeType = data.mimeType;
            this.size = data.size;
            this.readableSize = data.readableSize;
            this.createdAt = data.createdAt;
            this.updatedAt = data.updatedAt;
            this.selected = data.selected;
        }
    }

    toggleSelection(): void {
        this.selected = !this.selected;
    }

    isSelected(): boolean {
        return this.selected;
    }

    getFilePath(): string {
        return `/files-show/${this.fileName}`;
    }

    static clickOnUrl(url: string, filename?: string, newTab: boolean = false): void {
        const link = document.createElement('a');
        link.href = url;

        if (filename) {
            link.setAttribute('download', filename);
        }
        if (newTab) {
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
        }
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    download(): void {
        const fileId = this.id;
        const downloadUrl = `/download/${fileId}`;
        File.clickOnUrl(downloadUrl);

        useToastsStore().displayToast({
            message: "Sťahovanie sa začalo.",
            severity: ToastSeverity.SUCCESS,
        });
    }

    static async downloadMultiple(selectedFiles: File[]) {
        for (const file of selectedFiles) {
            const fileId = file.id;
            const downloadUrl = `/download/${fileId}`;
            File.clickOnUrl(downloadUrl, undefined, false);

            await new Promise(resolve => setTimeout(resolve, DOWNLOAD_DELAY_MS));

            useToastsStore().displayToast({
                message: `Súbor '${file.originalName || file.fileName}' sa začal sťahovať.`,
                severity: ToastSeverity.INFO,
            });
        }
    }

    static downloadZip(selectedFiles: File[]) {
        const ids = selectedFiles.map((file: File) => file.id);
        window.axios.post("/download-multiple",
            {fileIds: ids},
            {responseType: 'blob'})
            .then((response) => {
                const filename = "rucki.zip";
                const blob = new Blob([response.data], {type: 'application/zip'});

                const url = window.URL.createObjectURL(blob);
                File.clickOnUrl(url, filename);
                window.URL.revokeObjectURL(url);

                useToastsStore().displayToast({
                    message: `ZIP archív '${filename}' sa začal sťahovať.`,
                    severity: ToastSeverity.SUCCESS,
                });
            })
            .catch((error) => {
                useToastsStore().displayToast({
                    message: "ZIP sa nepodarilo stiahnuť. " + error.message,
                    severity: ToastSeverity.ERROR,
                });
            });
    }
}
