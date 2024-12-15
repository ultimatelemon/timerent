<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Timerent - Maandrapport</title>
</head>
<style>
    html {
        font-family: Arial, Helvetica, sans-serif;
        -webkit-print-color-adjust:exact !important;
        print-color-adjust:exact !important;
        color: #000000; !important;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        text-align: left;
        padding: 8px;
        /*background-color: #ffffff !important;*/
    }
</style>
<body style="padding: 3rem 1.5rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="color: #000">Timerent - Maandrapport</h1>
        <h4 style="color: #000">Periode {{$data['month']}} - {{ \Carbon\Carbon::now()->setMonth($data['month'])->startOfMonth()->format('d-m-Y')  }} t/m {{ \Carbon\Carbon::now()->setMonth($data['month'])->endOfMonth()->format('d-m-Y') }}</h4>
    </div>

    <div>
        <table style="border: 2px solid #000000;">
            <tr style="border: 1px solid #dddddd;">
                <th style="color: #000000; background-color: #ffffff">Omschrijving</th>
                <th style="color: #000000; background-color: #ffffff">Specificatie</th>
                <th style="color: #000000; background-color: #ffffff">Bedrag</th>
                <th style="color: #000000; background-color: #ffffff">Aantal</th>
            </tr>
            <tr style="border: 1px solid #dddddd;">
                <td style="color: #000000; background-color: #ffffff">Operationele omzet</td>
                <td style="color: #000000; background-color: #ffffff">Omzet BTW hoog (incl btw)</td>
                <td style="color: #000000; background-color: #ffffff">&euro; {{ number_format($data['revenue_high'] / 100, 2, ',', '.') }}</td>
                <td style="color: #000000; background-color: #ffffff"></td>
            </tr>
            <tr>
                <td style="color: #000000; background-color: #ffffff"></td>
                <td style="color: #000000; background-color: #ffffff">Waarvan BTW hoog</td>
                <td style="color: #000000; background-color: #ffffff">&euro; {{ number_format($data['tax_amount_high'] / 100, 2, ',', '.') }}</td>
                <td style="color: #000000; background-color: #ffffff"></td>
            </tr>
            <tr>
                <td style="color: #000000; background-color: #ffffff"></td>
                <td style="color: #000000; background-color: #ffffff">Omzet BTW laag (incl btw)</td>
                <td style="color: #000000; background-color: #ffffff">&euro; {{ number_format($data['revenue_low'] / 100, 2, ',', '.') }}</td>
                <td style="color: #000000; background-color: #ffffff"></td>
            </tr>
            <tr>
                <td style="color: #000000; background-color: #ffffff"></td>
                <td style="color: #000000; background-color: #ffffff">Waarvan BTW laag</td>
                <td style="color: #000000; background-color: #ffffff">&euro; {{ number_format($data['tax_amount_low'] / 100, 2, ',', '.') }}</td>
                <td style="color: #000000; background-color: #ffffff"></td>
            </tr>

            <tr style="background-color: #dddddd;">
                <td style="color: #000000; background-color: #ffffff">Klantenaantal</td>
                <td style="color: #000000; background-color: #ffffff"></td>
                <td style="color: #000000; background-color: #ffffff"></td>
                <td style="color: #000000; background-color: #ffffff"></td>{{ $data['customer_count'] }}</td>
            </tr>
            <tr>
                <td style="color: #000000; background-color: #ffffff">Reserveringaantal</td>
                <td style="color: #000000; background-color: #ffffff"></td>
                <td style="color: #000000; background-color: #ffffff"></td>
                <td style="color: #000000; background-color: #ffffff">{{ $data['reservation_count'] }}</td>
            </tr>
        </table>
    </div>

</body>
</html>