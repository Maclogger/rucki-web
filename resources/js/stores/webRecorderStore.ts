import {defineStore} from "pinia";
import axios from "axios";
import {getSessionId, getVisitorId} from "@/utils/userIdManager";

export interface WebRecorderStoreState {
    batchOfEvents: any[];
    visitorId: string;
    sessionId: string;
}

export const useWebRecorderStore = defineStore('webRecorderStore', {
    state: (): WebRecorderStoreState => {
        return {
            batchOfEvents: [],
            visitorId: getVisitorId(),
            sessionId: getSessionId(),
        }
    },

    actions: {
        pushEvent(event: any) {
            this.batchOfEvents.push(event);
        },

        async flush() {
            if (this.batchOfEvents.length === 0) {
                return;
            }

            const events = this.batchOfEvents;
            this.batchOfEvents = [];

            try {
                await axios.post("/store-web-recorder-batch", {
                    visitorId: this.visitorId,
                    sessionId: this.sessionId,
                    events,
                });
            } catch {
                // Unsent events belong at the front of the queue, the order has to be preserved
                this.batchOfEvents.unshift(...events);
            }
        },
    }

});
