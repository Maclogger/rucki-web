import { User } from "@/stores/userStore";

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};


export type Paginated<T> = {
    data: T[];
    current_page: number;
    last_page: number;
    total: number;
}
