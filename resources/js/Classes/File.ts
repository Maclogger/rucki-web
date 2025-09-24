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

    getFilePath(): string {
        return `/files-show/${this.fileName}`;
    }
}
