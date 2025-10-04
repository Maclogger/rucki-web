export class EmojiHelper {

    public static readonly EMOJIS: string[] = [
        "😂", "🤣", "😜", "😎", "🤪", "😇", "🤩", "😅", "😊", "🧸",
        "😏", "😋", "😝", "😛", "😆", "😁", "😃", "😄", "😉", "😇",
        "🐶", "🐱", "🐭", "🐹", "🐰", "🦊", "🐻", "🐼", "🐨", "🐯",
        "🦁", "🐮", "🐷", "🐸", "🐵", "🙈", "🙉", "🐔", "🐧", "😺",
        "🐦", "🐤", "🦆", "🦉", "🐝", "🐞", "🦋", "🐢", "🐬", "😸",
        "🥰", "😍", "😻", "😽", "😸", "😺", "😹", "😻", "😼", "😽",
    ];

    public static getRandomEmoji(): string {
        const emojis = EmojiHelper.EMOJIS;
        const randomIndex = Math.floor(Math.random() * emojis.length);
        return emojis[randomIndex];
    }
}
