import {usePublicStore} from "@/stores/publicStore";
import {format} from 'date-fns';

export const toNicelyFormattedDateAndTime = (date: Date | null): string => {
    const publicStore = usePublicStore();

    if (!date) {
        return "Invalid Date";
    }

    const formatString = publicStore.getSetting('dateAndTimeFormat');

    return format(date, formatString);
}

