<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Quotation PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            padding: 15px 0 5px 0;
        }

        .header img {
            height: 80px;
            float: left;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
        }

        .company-info {
            font-size: 11px;
        }

        .green-title {
            background: #91df9b;
            color: #fff;
            font-weight: bold;
            padding: 4px 8px;
            margin-top: 10px;
            font-size: 13px;
        }

        .info-table {
            width: 100%;
            font-size: 12px;
            border: 1px solid #39b54a;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            border: 1px solid #39b54a;
            padding: 5px;
        }

        .info-table td:first-child {
            width: 120px;
            font-weight: bold;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 11px;
            text-align: center;
        }

        .items-table th {
            background: #f2f2f2;
        }

        .sub-header {
            background: #e8f5e9;
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: 15px;
            left: 0;
            right: 0;
            font-size: 10px;
            padding: 5px;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header" style="text-align:center; margin-bottom:20px;">
        <img src="{{ asset('images/easy_clean.jpg') }}" alt="Logo" style="width:150px; display:block; margin:0 auto;">
        <div style="font-size:28px; font-weight:bold; margin-top:10px;">
            Easy Clean Up B.V.
        </div>
        <div style="font-size:14px; margin-top:5px;">
            Kollenbergweg 78 - 1101 AV AMSTERDAM Z. O.<br>
            Tel.: 020 - 691 61 15 &nbsp;&nbsp; Fax.: 020 - 691 77 28
        </div>
    </div>

    {{-- Info Table --}}
    <table style="width:100%; border-collapse:collapse; background:#ccffcc; padding:10px;">
        <tr>
            <td colspan="2" style="font-weight:bold; font-size:20px; color:#0046ad; padding:8px;">
                Containers Transportbon:
            </td>
            <td style="text-align:right; font-weight:bold; padding:8px; white-space:nowrap;">
                {{ \Carbon\Carbon::parse($quotation->date)->format('d F') ?? '01Maart' }}
            </td>
        </tr>

        <tr>
            <td style="width:160px; font-weight:bold; padding:8px;">Opdrachtgever :</td>
            <td style="padding:8px; background:#fff;">{{ $quotation->customer->name ?? '-' }}</td>
            <td style="text-align:right; padding:8px; font-size:12px; white-space:nowrap;">Pagina: 1/1</td>
        </tr>

        <tr>
            <td style="font-weight:bold; padding:8px;">Project :</td>
            <td colspan="2" style="padding:8px; background:#fff;">
                {{ $quotation->project ?? '-' }}
            </td>
        </tr>

        <tr>
            <td style="font-weight:bold; padding:8px;">Contact persoon :</td>
            <td colspan="2" style="padding:8px; background:#fff; font-weight:bold;">
                {{ $quotation->contact_person ?? '-' }}
            </td>
        </tr>
    </table>



    {{-- Items Table --}}
    <table class="items-table">
        <thead>
            <tr>
                <th>Datum</th>
                <th>Inhoud</th>
                <th colspan="7">Specificaties</th>
            </tr>
            <tr class="sub-header">
                <th></th>
                <th></th>
                <th>Soort Afval</th>
                <th>Gewicht (Ton)</th>
                <th>Prijs per Ton</th>
                <th>Toeslag</th>
                <th>Extra prijs</th>
                <th>Bedrag</th>
                <th>Opmerkingen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quotation->items as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                    <td>{{ $item->content }}</td>
                    <td>{{ $item->waste_type }}</td>
                    <td>{{ $item->weight }}</td>
                    <td>€ {{ number_format($item->price_per_ton, 2, '.', ',') }}</td>
                    <td>-</td>
                    <td>€ {{ number_format($item->extra_charge, 2, '.', ',') }}</td>
                    <td>€ {{ number_format($item->amount, 2, '.', ',') }}</td>
                    <td>{{ $item->remarks }}</td>
                </tr>
            @endforeach

            {{-- Empty rows to match design --}}
            @for ($i = count($quotation->items); $i < 10; $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor

            {{-- Total Row --}}
            <tr class="total-row">
                <td colspan="7">Totaal</td>
                <td>€ {{ number_format($quotation->total, 2, '.', ',') }}</td>
                <td>Ex. BTW</td>
            </tr>
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-left">Zie bijlage<br>Transportvoorwaarden gedeponeerd</div>
        <div class="footer-right">
            Easy Clean Up B.V. <br>
            KvK: 12345678 • BTW: NL123456789B01
        </div>
        <div class="clearfix"></div>
    </div>
</body>

</html>
