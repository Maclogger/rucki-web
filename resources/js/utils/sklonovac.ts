export const vysklonuj = (num: number, singular: string, dvaTriStyri: string, nulaPatAViac: string, hideNum?: boolean): string => {
    let output: string = "";
    if (hideNum == undefined || !hideNum) {
        output += num + " ";
    }
    if (num == 1) {
        return output + singular;
    }
    if (num == 2 || num == 3 || num == 4) {
        return output + dvaTriStyri;
    }
    return output + nulaPatAViac;
}
