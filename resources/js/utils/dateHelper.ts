import {usePublicStore} from "@/stores/publicStore";
import {format} from 'date-fns';

export const toNicelyFormattedDateAndTime = (date: Date | null): string => {
    console.log(typeof(date));
    const publicStore = usePublicStore();

    if (!date) {
        console.log(date);
        return "Invalid Date";
    }

    const formatString = publicStore.getSetting('dateAndTimeFormat');
    console.log("Format string: " + formatString);

    return format(date, formatString);
}




