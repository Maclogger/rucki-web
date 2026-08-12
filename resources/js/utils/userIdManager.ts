export function getVisitorId() {
    const key = "rucki-web-visitor-id";
    try {
        let id = localStorage.getItem(key);
        if (!id) {
            id = crypto.randomUUID();
            localStorage.setItem(key, id);
        }
        return id;
    } catch (e) {
        console.error(e);
        return crypto.randomUUID();
    }
}

export function getSessionId() {
    const key = "rucki-web-session-id";
    try {
        let id = sessionStorage.getItem("rucki-web-session-id");
        if (!id) {
            id = crypto.randomUUID();
            sessionStorage.setItem(key, id);
        }
        return id;
    } catch (e) {
        console.error(e);
        return crypto.randomUUID();
    }
}


















