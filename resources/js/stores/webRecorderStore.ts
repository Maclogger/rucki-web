import {defineStore} from "pinia";
import axios from "axios";
import dayjs, {Dayjs} from "dayjs";
import {getSessionId, getVisitorId} from "@/utils/userIdManager";

const INTERVAL_SECONDS = 5;


export interface WebRecorderStoreState {
    batchOfEvents: any[];
    visitorId: string;
    sessionId: string;
    nextBatchScheduledTime: Dayjs;
}

export const useWebRecorderStore = defineStore('webRecorderStore', {
    state: (): WebRecorderStoreState => {
        return {
            batchOfEvents: [],
            visitorId: getVisitorId(),
            sessionId: getSessionId(),
            nextBatchScheduledTime: dayjs().add(INTERVAL_SECONDS, 'seconds'),
        }
    },

    actions: {
        init() {
            this.scheduleNextBatch();
        },

        async saveBatchToDatabase() {
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
            } catch (error) {
                this.batchOfEvents.unshift(...events);
                throw error;
            }
        },

        pushEvent(event: any) {
            this.batchOfEvents.push(event);
        },

        async finishRecording() {
            await this.saveBatchToDatabase();
        },

        scheduleNextBatch() {
            this.nextBatchScheduledTime = dayjs().add(INTERVAL_SECONDS, 'seconds');
            setTimeout(async () => {
                const store = useWebRecorderStore();
                try {
                    await store.saveBatchToDatabase();
                } finally {
                    store.scheduleNextBatch();
                }
            }, INTERVAL_SECONDS * 1_000)
        },
    }


});
