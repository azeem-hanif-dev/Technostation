<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Weekstaat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .header {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            line-height: 1.5;
        }

        .no-border td {
            border: none;
            padding: 2px;
        }

        .no-border {
            margin-bottom: 5px;
        }

        .left-align {
            text-align: left;
            padding-left: 10px;
        }

        .bold {
            font-weight: bold;
        }

        .small-font {
            font-size: 10px;
            font-style: italic;
        }

        .remarks {
            margin-top: 20px;
        }

        .signature {
            margin-top: 40px;
        }

        .underline {
            border-bottom: 1px solid black;
            display: inline-block;
            width: 200px;
        }

        .footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; text-align: left; border: none;">
                <img src="{{ asset('images/easy_clean.jpg') }}"
                    style="height: 135px; width: 100%; object-fit: contain;" />
            </td>
            <td style="width: 50%; text-align: left; border: none; margin-right: 80px;">
                <h1 style="margin: 0; font-size: 20px;">Easy Cleanup B.V.</h1>
                <p style="margin: 0;">Kollenbergweg 78 – 1101 AV Amsterdam ZO</p>
                <p style="margin: 0;">Tel: 020 - 691 61 15, Fax: 020 – 691 77 28</p>
            </td>
        </tr>
    </table>
    @php
        $firstCard = $grouped_week_cards->first();
    @endphp

    <div style="clear: both;"></div>

    <div style="text-align: center;">
        <h3 style="margin-bottom: 5px;">WEEKSTAAT</h3>
        <p style="margin: 0;"><b>Naam:</b> {{ $firstCard->personnel->agency->name ?? 'N/A' }}</p>
        <p style="margin: 0;"><b>E-mail:</b> {{ $firstCard->personnel->agency->email ?? 'N/A' }}</p>
    </div>
    <br>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; margin-top: 10px;">
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"><b>Weeknummer:</b></td>
            <td style="border: 1px solid #000; padding: 5px;">{{ $week_no }}</td>
            <td style="border: 1px solid #000; padding: 5px;"><b>Datum:</b></td>
            <td style="border: 1px solid #000; padding: 5px;">{{ $start_date }} t/m {{ $end_date }}</td>
        </tr>
    </table>
    <p class="small-font" style="text-align: center;">
        Eventuele wijzigingen in de gewerkte uren kunnen aangepast worden indien de getekende werkbon afwijkt
    </p>
    <table>
        <thead>
            <tr>
                <th>W. nr.</th>
                <th>Naam werknemer</th>
                <th>Ma</th>
                <th>Di</th>
                <th>Wo</th>
                <th>Do</th>
                <th>Vr</th>
                <th>Za</th>
                <th>Zo</th>
                <th>Totaal</th>
                <th>Tarief per uur</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($grouped_week_cards as $index => $weekCard)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="left-align">{{ $weekCard->personnel->first_name }} {{ $weekCard->personnel->last_name }}
                    </td>
                    <td>{{ $weekCard->hours_1 }}</td>
                    <td>{{ $weekCard->hours_2 }}</td>
                    <td>{{ $weekCard->hours_3 }}</td>
                    <td>{{ $weekCard->hours_4 }}</td>
                    <td>{{ $weekCard->hours_5 }}</td>
                    <td>{{ $weekCard->hours_6 }}</td>
                    <td>{{ $weekCard->hours_7 }}</td>
                    <td>{{ $weekCard->total_hours }}</td>
                    <td>€ {{ $weekCard->personnel->cost_per_hour }} plu</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" style="text-align: right;" class="bold">Totale uren:</td>
                <td>{{ $all_week_cards_total_hours }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="remarks">
        <p><b>Opmerkingen:</b></p>
    </div>

    <div class="signature">
        <table class="no-border" width="100%">
            <tr>
                <td class="no-border left-align"><b>Handtekening:</b> <span class="underline"></span></td>
                <td class="no-border right-align"><b>Verz. datum:</b> <span class="underline"></span></td>
            </tr>
            <tr>
                <td class="no-border left-align"><b>Naam:</b>
                    <u>{{ $weekCard->personnel->agency->name ?? 'N/A' }}</u><br>Easy Clean Up<br>BV
                </td>

            </tr>
        </table>
    </div>


</body>

</html>
