<html>
<head>
    <title>ChargeBuddies Revenue Statement</title>
    <meta
            name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <style>
        blockquote,
        dl,
        dd,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        hr,
        figure,
        p,
        pre {
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        /
        /
        @font-face {
        //    font-family: 'Poppins';
        //    src: url({{ storage_path('fonts\Poppins-Regular.ttf') }}) format("truetype");
        //  font-weight: 400;
        //  font-style: normal;
        //
        }

    </style>
</head>
<body style="overflow: hidden; height: 100%;">
<div style=" padding: 3rem; height: 100%;">
    <div style=" width: 100%; margin-bottom: 3.5rem;">
        <div style="font-size: 1.5rem; line-height: 2rem; margin-right: auto;">
            <img src="https://charge-buddies.com/wp-content/uploads/2023/03/cropped-logotest.png" alt="">
        </div>

        <div style="margin-top: 2.5rem">
            <div>
                <span style="font-weight: 600">Factuur: </span> {{ strtoupper(explode('-', $invoice->id)[0]) }}
            </div>
            <div>
                <span style="font-weight: 600">Datum: </span> {{ $invoice->created_at }}
            </div>
        </div>
    </div>
    <div>
        <div style="">
            <div style="width:100%">
                <div style="display: inline-block; min-width: 100%;">
                    <div style="overflow: hidden">
                        <table style="width: 95%; max-width: 95%">
                            <thead>
                            <tr>
                                <th scope="col"
                                    style="font-size: 0.875rem; line-height: 1.25rem; font-weight: 600; color: rgb(17 24 39); padding-top: 1rem; padding-bottom: 1rem; text-align: left;">
                                    <div style="margin-bottom: 1rem">Aan</div>
                                    {{ $invoice->name }}<br/>
                                    {{ $invoice->email }}<br/>
                                    {{ $invoice->phone_number }}
                                </th>
                                <th scope="col"
                                    style="font-size: 0.875rem; line-height: 1.25rem; font-weight: 600; color: rgb(17 24 39); padding-top: 1rem; padding-bottom: 1rem; text-align: right;">
                                    <div style="margin-bottom: 1rem">Van</div>
                                    {{ $business['name'] }}<br/>
                                    {{ $business['address'] }}<br/>
                                </th>
                            </tr>
                            </thead>
                            <!--                            <tbody>-->
                            <!--                            <tr style="background: #fff;">-->
                            <!--                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 600; color: rgb(17 24 39); text-align: left;">-->
                            <!--                                    Profit Sharing-->
                            <!--                                </td>-->
                            <!--                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 500; color: rgb(17 24 39); text-align: right;">-->
                            <!--                                    &euro;-1,50-->
                            <!--                                </td>-->
                            <!--                            </tr>-->
                            <!--                            <tr style="background: #fff;">-->
                            <!--                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 600; color: rgb(17 24 39); text-align: left;">-->
                            <!--                                    Rental-->
                            <!--                                </td>-->
                            <!--                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 500; color: rgb(17 24 39); text-align: right;">-->
                            <!--                                    &euro;25,00-->
                            <!--                                </td>-->
                            <!--                            </tr>-->
                            <!--                            <tr style="background: #fff;">-->
                            <!--                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 600; color: rgb(17 24 39); text-align: left;">-->
                            <!--                                    Tax (21%)-->
                            <!--                                </td>-->
                            <!--                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 500; color: rgb(17 24 39); text-align: right;">-->
                            <!--                                    &euro;5,25-->
                            <!--                                </td>-->
                            <!--                            </tr>-->
                            <!--                            <tr style="background: #fff;">-->
                            <!--                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 600; color: rgb(17 24 39); text-align: left;">-->
                            <!--                                    Total to be paid-->
                            <!--                                </td>-->
                            <!--                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 500; color: rgb(17 24 39); text-align: right;">-->
                            <!--                                    &euro;28,75-->
                            <!--                                </td>-->
                            <!--                            </tr>-->
                            <!--                            </tbody>-->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div>
        <div style="">
            <div style="width:100%">
                <div style="display: inline-block; min-width: 100%;">
                    <div style="overflow: hidden">
                        <table style="width: 95%; max-width: 95%">
                            <thead>
                            <tr>
                                <th scope="col"
                                    style="font-size: 0.875rem; line-height: 1.25rem; font-weight: 600; color: rgb(17 24 39); padding-top: 1rem; padding-bottom: 1rem; text-align: left;">
                                    Type
                                </th>
                                <th scope="col"
                                    style="font-size: 0.875rem; line-height: 1.25rem; font-weight: 600; color: rgb(17 24 39); padding-top: 1rem; padding-bottom: 1rem; text-align: right;">
                                    Prijs
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr style="background: #fff;">
                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 600; color: rgb(17 24 39); text-align: left;">
                                    Reservering
                                </td>
                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 500; color: rgb(17 24 39); text-align: right;">
                                    &euro;{{ number_format($invoice->payment_amount / 100, 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr style="background: #fff;">
                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 600; color: rgb(17 24 39); text-align: left;">
                                    Netto
                                </td>
                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 500; color: rgb(17 24 39); text-align: right;">
                                    &euro;{{ number_format(($invoice->payment_amount - ($invoice->tax_low + $invoice->tax_high)) / 100, 2, ',', '.') }}
                                </td>
                            </tr>
                            @if($invoice->tax_low > 0)
                                <tr style="background: #fff;">
                                    <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 600; color: rgb(17 24 39); text-align: left;">
                                        BTW (9%)
                                    </td>
                                    <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 500; color: rgb(17 24 39); text-align: right;">
                                        &euro;{{ number_format($invoice->tax_low / 100, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            @if($invoice->tax_high > 0)
                                <tr style="background: #fff;">
                                    <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 600; color: rgb(17 24 39); text-align: left;">
                                        BTW (21%)
                                    </td>
                                    <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 500; color: rgb(17 24 39); text-align: right;">
                                        &euro;{{ number_format($invoice->tax_high / 100, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            <tr style="background: #fff;">
                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 600; color: rgb(17 24 39); text-align: left;">
                                    Totaal factuur
                                </td>
                                <td style="padding-top: 1rem; padding-bottom: 1rem; font-size: 0.875rem; font-weight: 500; color: rgb(17 24 39); text-align: right;">
                                    &euro;{{ number_format($invoice->payment_amount / 100, 2 , ',', '.') }}
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div style="color: rgb(17 24 39); margin-top: 4rem; width: 100%;">
                              <span style="width: 100%; font-size: 0.875rem; text-align: center;">
{{--                                The amount will be on your bank account within 3-5 business days. The amount is including tax. If you do not receive the profit sharing amount within 5 business days, please contact business support.--}}
                              </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>