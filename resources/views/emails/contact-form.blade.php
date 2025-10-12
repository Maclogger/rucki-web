<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }

        .info-row {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
            color: #4F46E5;
        }

        .message-box {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #4F46E5;
            margin-top: 15px;
            white-space: pre-wrap;
        }
    </style>
    <title>Mailová správa</title>
</head>
<body>
<div class="header">
    <h2 style="margin: 0;">Nová správa z kontaktného formulára</h2>
</div>
<div class="content">
    <div class="info-row">
        <span class="label">Od:</span> {{ $senderName }}
    </div>
    <div class="info-row">
        <span class="label">Email:</span> {{ $senderEmail }}
    </div>
    <div class="info-row">
        <span class="label">Predmet:</span> {{ $emailSubject }}
    </div>
    <div class="message-box">
        <div class="label">Správa:</div>
        <p>{{ $messageContent }}</p>
    </div>
</div>
</body>
</html>

