<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - {{ $reservation->ticket_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #FF6000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            color: #FF6000;
            font-size: 36px;
            font-weight: bold;
        }
        .ticket-code {
            background: #f5f5f5;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            border: 2px dashed #FF6000;
        }
        .details {
            margin: 20px 0;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .details td:first-child {
            font-weight: bold;
            width: 200px;
        }
        .qr-code {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">FastMovie Renesse</div>
        <p>De beste filmervaring in Renesse</p>
    </div>

    <h1 style="text-align: center; color: #454545;">{{ $reservation->show->movie->title }}</h1>

    <div class="ticket-code">
        <h2 style="margin: 0; font-size: 28px; font-family: monospace;">{{ $reservation->ticket_code }}</h2>
        <p style="margin: 10px 0 0 0; color: #666;">Ticket Code</p>
    </div>

    <div class="details">
        <table>
            <tr>
                <td>Datum</td>
                <td>{{ $reservation->show->show_date->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>Tijd</td>
                <td>{{ substr($reservation->show->show_time, 0, 5) }}</td>
            </tr>
            <tr>
                <td>Aantal plaatsen</td>
                <td>{{ $reservation->seats }}</td>
            </tr>
            @if($reservation->seat_numbers)
            <tr>
                <td>Stoelnummers</td>
                <td>{{ implode(', ', $reservation->seat_numbers) }}</td>
            </tr>
            @endif
            <tr>
                <td>Genre</td>
                <td>{{ $reservation->show->movie->genre }}</td>
            </tr>
            <tr>
                <td>Leeftijd</td>
                <td>{{ $reservation->show->movie->age_rating }}+</td>
            </tr>
            <tr>
                <td>Taal</td>
                <td>{{ $reservation->show->movie->language }}</td>
            </tr>
            @if($reservation->snacks)
            <tr>
                <td>Snacks</td>
                <td>
                    @foreach($reservation->snacks as $snack)
                        {{ $snack['quantity'] }}x {{ $snack['name'] }}<br>
                    @endforeach
                </td>
            </tr>
            @endif
            <tr style="background: #f5f5f5;">
                <td><strong>Totaalprijs</strong></td>
                <td><strong style="color: #FF6000;">€{{ number_format($reservation->total_price, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="qr-code">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="width: 200px; height: 200px;">
        <p style="color: #666; margin-top: 10px;">Scan deze code bij de ingang</p>
    </div>

    <div style="background: #fffbf0; border: 1px solid #ffd700; padding: 15px; margin: 20px 0; border-radius: 5px;">
        <strong>Belangrijke informatie:</strong>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li>Wees 15 minuten voor aanvang aanwezig</li>
            <li>Toon dit ticket bij de ingang</li>
            <li>Neem een geldig legitimatiebewijs mee</li>
        </ul>
    </div>

    <div class="footer">
        <p>FastMovie Renesse | Renesse | info@fastmovie.nl</p>
        <p>© 2026 FastMovie. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
