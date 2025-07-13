import {usePublicStore} from "@/stores/publicStore";
import {format} from 'date-fns';

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


/**
 * Získa dátum začiatku prvého týždňa daného roka podľa ISO 8601.
 * Prvý týždeň je ten, ktorý obsahuje prvý štvrtok roka.
 * @param year Rok.
 * @returns Objekt Date reprezentujúci začiatok prvého týždňa.
 */
function getFirstWeekStartDateISO(year: number): Date {
    const januaryFourth = new Date(year, 0, 4); // Mesiac 0 je január

    const dayOfWeek = (januaryFourth.getDay() + 6) % 7; // Pondelok = 0, Utorok = 1, ... Nedeľa = 6

    januaryFourth.setDate(januaryFourth.getDate() - dayOfWeek);

    return januaryFourth;
}

/**
 * Konvertuje rok, číslo týždňa a poradové číslo dňa v týždni na inštanciu Date
 * striktne podľa ISO 8601 štandardu.
 * Dni v týždni sú číslované od 1 (pondelok) do 7 (nedeľa).
 *
 * @param year Rok.
 * @param weekNumber Číslo týždňa (1-53).
 * @param dayOfWeek Poradové číslo dňa v týždni (1 = pondelok, 7 = nedeľa).
 * @returns Inštancia Date reprezentujúca daný dátum.
 * @throws Error ak je dayOfWeek mimo rozsahu 1-7.
 */
export function getDateFromWeekAndDayISO(
    year: number,
    weekNumber: number,
    dayOfWeek: number
): Date {
    if (dayOfWeek < 1 || dayOfWeek > 7) {
        throw new Error("Poradové číslo dňa v týždni musí byť v rozsahu 1-7.");
    }

    const firstWeekStart = getFirstWeekStartDateISO(year);

    const daysToAdd = (weekNumber - 1) * 7 + (dayOfWeek - 1);

    const resultDate = new Date(firstWeekStart);
    resultDate.setDate(firstWeekStart.getDate() + daysToAdd);

    return resultDate;
}
