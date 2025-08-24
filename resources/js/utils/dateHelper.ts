import { usePublicStore } from "@/stores/publicStore";
import { format } from 'date-fns';

export const toNicelyFormattedDateAndTime = (date: Date | null): string => {
    const publicStore = usePublicStore();

    if (!date) {
        return "Invalid Date";
    }

    const formatString = publicStore.getConstant('dateAndTimeFormat') || 'dd.MM.yyyy HH:MM';

    return format(date, formatString);
}

export const toNicelyFormattedDate = (date: Date | null): string => {
    const publicStore = usePublicStore();

    if (!date) {
        return "Invalid Date";
    }

    const formatString = publicStore.getConstant('dateFormat') || 'dd.MM.yyyy';

    return format(date, formatString);
}

export const isLeapYear = (year: number) => {
    return (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
};


export const getDayCountFromStartOfTheYear = (date: Date) => {
    const year = date.getFullYear();
    const month = date.getMonth(); // 0 (Jan) - 11 (Dec)
    const day = date.getDate(); // 1 - 31

    let dayOfYear = 0;

    for (let i = 0; i < month; i++) {
        switch (i) {
            case 0: // Január
            case 2: // Marec
            case 4: // Máj
            case 6: // Júl
            case 7: // August
            case 9: // Október
            case 11: // December
                dayOfYear += 31;
                break;
            case 3: // Apríl
            case 5: // Jún
            case 8: // September
            case 10: // November
                dayOfYear += 30;
                break;
            case 1: // Február
                // Zistíme, či je rok priestupný pre Február
                const isLeap = (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
                dayOfYear += isLeap ? 29 : 28;
                break;
        }
    }

    return dayOfYear + day;
}

