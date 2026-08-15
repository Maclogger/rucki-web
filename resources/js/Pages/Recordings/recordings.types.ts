
export interface WrSession {
    id_session: string;
    id_visitor: string;
    created_at: Date;
    events_count: number;
    visitor: WrVisitor;
}

export interface WrVisitor {
    id_visitor: string;
}
