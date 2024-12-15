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
    }

    table {
        border: 1px solid #dddddd;
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        text-align: left;
        padding: 8px;
    }

    tr.background {
        background-color: #dddddd;
    }
</style>
<body style="padding: 3rem 1.5rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1>Timerent - Maandrapport</h1>
        <h4>Periode {{$data['month']}} - {{ \Carbon\Carbon::now()->setMonth($data['month'])->startOfMonth()->format('d-m-Y')  }} t/m {{ \Carbon\Carbon::now()->setMonth($data['month'])->endOfMonth()->format('d-m-Y') }}</h4>
    </div>

    <div>
        <table>
            <tr>
                <th>Omschrijving</th>
                <th>Specificatie</th>
                <th>Bedrag</th>
                <th>Aantal</th>
            </tr>
            <tr>
                <td>Operationele omzet</td>
                <td>Totale omzet</td>
                <td>&euro; {{ number_format($data['total_revenue'] / 100, 2, ',', '.') }}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Waarvan BTW hoog</td>
                <td>&euro; {{ number_format($data['tax_amount_high'] / 100, 2, ',', '.') }}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Waarvan BTW laag</td>
                <td>&euro; {{ number_format($data['tax_amount_low'] / 100, 2, ',', '.') }}</td>
                <td></td>
            </tr>

            <tr class="background">
                <td>Klantenaantal</td>
                <td></td>
                <td></td>
                <td>{{ $data['customer_count'] }}</td>
            </tr>
            <tr>
                <td>Reserveringaantal</td>
                <td></td>
                <td></td>
                <td>{{ $data['reservation_count'] }}</td>
            </tr>
        </table>
    </div>

</body>
</html>