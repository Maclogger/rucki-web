<?php

namespace Database\Seeders;

use App\Models\QrCode;
use Illuminate\Database\Seeder;

class QrCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QrCode::truncate();

        QrCode::create([
            'question_index' => 1,
            'content' => "📟🕵️‍♂️ One barcode lies — find the traitor by its final digit. \n\n We have 3 barcodes. One of them is incorrect. The answer is the letter of the INCORRECT barcode (use the check digit based on the last digit)."
        ]);

        QrCode::create([
            'question_index' => 2,
            'content' => "🔍✨ Find the hidden word. \n\nUnscramble the letters to find a common digital tool for linking to websites: QOEDCR  \nThe letter you are looking for is the second letter of the unscrambled word."
        ]);

        QrCode::create([
            'question_index' => 3,
            'content' => "📦🔑 Peek inside the abbreviation — the treasure is the third letter! \n\nUnscrambled word: UPC The letter you are looking for is the 3rd letter of the first word of the abbreviation."
        ]);

        QrCode::create([
            'question_index' => 4,
            'content' => "🔢🧩Solve the numbers to reveal the letter. \n\nI am a number. Multiply me by 2, add 10, subtract 12, then add 2. The final result is 146. What number am I? The resulting number is the ASCII value of the letter you are looking for."
        ]);

        QrCode::create([
            'question_index' => 5,
            'content' => " 🛠️✨ The originator’s mark — the first letter holds the key \n\nThe answer is the first letter of the name of the QR code inventor. "
        ]);

        QrCode::create([
            'question_index' => 6,
            'content' => "🔄🔤  Loop through the letters — which one stays till the end if every 11th number is gone? Watchout, the letters are in a circle. \n\nABCDEFGHIJKLMNOPQRSTUVWXYZ"
        ]);

        QrCode::create([
            'question_index' => 7,
            'content' => "💻🔍Crack the binary — a letter waits in the code. \n\nI am a capital letter. My ASCII value in binary is 01000110. Which letter am I? "
        ]);

        QrCode::create([
            'question_index' => 8,
            'content' => "🧩🚪 A clever riddle — the first letter opens the door. \n\nI have keys but no doors, I have space but no room, you can enter but can’t go outside. What am I? The letter you are looking for is the first letter of the answer. "
        ]);

        QrCode::create([
            'question_index' => 9,
            'content' => "⌨️✨ Do you remember previous question? \n\nWhat layout can I have? (1st letter)"
        ]);

        QrCode::create([
            'question_index' => 10,
            'content' => "📝❌ Erase the words — only one letter remains.\n\nRAM, CACHE, BYTE, IEEE, ENCRYPT, CPU, EMAIL, BARCODE, DATA, QRCODE, SCANNER "
        ]);

        QrCode::create([
            'question_index' => 11,
            'content' => "Navigate the keys — where will your path lead? Imagine you are a robot moving across a keyboard. Start at the starting point and follow the instructions (right, left, up, down) until you reach the target.\n\nPath: 3× right, 2× down 4× left,  2× up 3× right, 3× right 1× down, 4× left, 1× right, 1× down 3× right\n\nStart: W\nGoal: The robot ends up on __?"
        ]);

        QrCode::create([
            'question_index' => 12,
            'content' => "🌍🕵️‍♂️ Where it all began — uncover the third letter.\n\nIn which country was the barcode used for the first time? (3rd letter) "
        ]);

        QrCode::create([
            'question_index' => 13,
            'content' => "From Slovak to English — the first letter holds the clue. \n\nTranslate ‘Čiarový kód’ to English. The letter you are looking for is the first letter of the answer."
        ]);

        QrCode::create([
            'question_index' => 14,
            'content' => "💻 Twist the code — XOR reveals the secret letter.\n\nTake ASCII of A (65) XOR 25. What letter do you get?"
        ]);

        QrCode::create([
            'question_index' => 15,
            'content' => "🔍 Riddle: The Tiny Square\n\nI am small but mighty, a square so fine, In QR codes and screens, I help you shine. Black or white, I stand in a row, Together with friends, we make data flow.\nI'm the smallest piece of a digital art, A fundamental unit, the building part. In pictures and codes, I play my role, What am I? A tiny digital soul!\n(First letter of the answer)"
        ]);

        QrCode::create([
            'question_index' => 16,
            'content' => "🗝️ Only one speaks the truth — which box hides the prize? \n\nThere are 3 boxes. One contains a key, the others are empty.\nBox A says 'The key is not in Box B.'\nBox B says 'The key is not in Box C.'\nBox C says 'The key is in Box B.'\nOnly one statement is true. Where is the key?"
        ]);

    }
}

