export const vysklonuj = (num: number, singular: string, dvaTriStyri: string, nulaPatAViac: string): string => {
    let output: string = "" + num + " ";
    if (num == 1) {
        return output + singular;
    }
    if (num == 2 || num == 3 || num == 4) {
        return output + dvaTriStyri;
    }
    return output + nulaPatAViac;
}
