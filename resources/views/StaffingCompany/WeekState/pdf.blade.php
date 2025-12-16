{{-- <!DOCTYPE html> --}}
{{-- <html lang="en"> --}}
{{-- <head> --}}
{{--    <meta charset="UTF-8"> --}}
{{--    <title>Document</title> --}}
{{--    <style> --}}
{{--        body { --}}
{{--            font-family: Arial, sans-serif; --}}
{{--            margin: 0; --}}
{{--            padding: 0; --}}
{{--        } --}}
{{--        .center { --}}
{{--            text-align: center; --}}
{{--        } --}}
{{--        .left { --}}
{{--            text-align: left; --}}
{{--        } --}}
{{--        .right { --}}
{{--            text-align: right; --}}
{{--        .padding { --}}
{{--            padding: 2px; --}}
{{--        } --}}
{{--        .margin-top { --}}
{{--            margin-top: 10px; --}}
{{--        } --}}
{{--        footer { --}}
{{--            position: fixed; --}}
{{--            bottom: 0cm; --}}
{{--            left: 0cm; --}}
{{--            right: 0cm; --}}
{{--            height: 10px; --}}
{{--            text-align: center; --}}
{{--            font-size: 10px; --}}
{{--        } --}}
{{--        .weekcard table, .weekcard td, .weekcard th { --}}
{{--            border: 1px solid #000; --}}
{{--            box-shadow: none; --}}
{{--            text-shadow: none; --}}
{{--        } --}}
{{--        table { --}}
{{--            border-collapse: collapse; --}}
{{--            width: 100%; --}}
{{--            font-size: 12px; --}}
{{--        } --}}
{{--        .strong { --}}
{{--            font-weight: bold; --}}
{{--        } --}}
{{--        .weekcard th, .weekcard td { --}}
{{--            font-size: 10px; --}}
{{--        } --}}
{{--        .address td, th { --}}
{{--            line-height: 14px; --}}
{{--        } --}}
{{--        img { --}}
{{--            vertical-align: top; --}}
{{--            height: 100px; --}}
{{--            width: 200px; --}}
{{--            margin-bottom: 15px; --}}
{{--        } --}}
{{--        .note { --}}
{{--            font-size: 12px; --}}
{{--            margin-top: 20px; --}}
{{--        } --}}
{{--        .app-info { --}}
{{--            text-align: center; --}}
{{--            font-size: 12px; --}}
{{--            margin-top: 10px; --}}
{{--        } --}}
{{--        .app-icons img { --}}
{{--            height: 50px; --}}
{{--            margin: 0 5px; --}}
{{--        } --}}
{{--    </style> --}}
{{-- </head> --}}
{{-- <body> --}}
{{-- <table> --}}
{{--    <tr> --}}
{{--        <td width="25%"><img src="{{ asset('images/easy_clean.jpg') }}" /></td> --}}
{{--        <td width="75%" class="center" style="font-size: 12px;"> --}}
{{--            <h1>Easy Clean UP B.V.</h1> --}}
{{--            <p>Kollenbergweg 78 - 1101 AV Amsterdam</p> --}}
{{--            <p>Tel: 020 - 691 61 15, E-mail: planning@easycleanup.nl</p> --}}
{{--            <p>Schoonmaakonderhoud - Bouwopruiming - Opleveringsschoonmaak</p> --}}
{{--            <p>Glasbewassing - Gevelreiniging - Keetschoonmaak - Bedrijfsdiensten</p> --}}
{{--            <p>Bouwafval management & Containers Service</p> --}}
{{--        </td> --}}
{{--    </tr> --}}
{{-- </table> --}}

{{-- <div style="border: 1px solid #000; padding: 5px;"> --}}
{{--    <p class="center">WEEKSTAAT</p> --}}
{{--    <table> --}}
{{--        <tr> --}}
{{--            <td width="30%"><span style="margin-left: 5px;">OPDRACHTGEVER:</span></td> --}}
{{--            <td width="70%">{{ $departmentName }}</td> --}}
{{--        </tr> --}}
{{--        <tr> --}}
{{--            <td width="30%"><span style="margin-left: 5px;">WEEK NUMMER:</span></td> --}}
{{--            <td width="70%">{{ $week_state->week_no }}</td> --}}
{{--        </tr> --}}
{{--        <tr> --}}
{{--            <td width="30%"><span style="margin-left: 5px;">PROJECTNAAM:</span></td> --}}
{{--            <td width="70%">{{ $project->name }}</td> --}}
{{--        </tr> --}}
{{--        <tr> --}}
{{--            <td width="30%"><span style="margin-left: 5px;">KLANT PROJ. NR.:</span></td> --}}
{{--            <td width="70%">{{ $project->customer->name }}</td> --}}
{{--        </tr> --}}
{{--        <tr> --}}
{{--            <td width="30%"><span style="margin-left: 5px;">ECU PROJECT/DEBITEUR #:</span></td> --}}
{{--            <td width="70%"></td> --}}
{{--        </tr> --}}
{{--        <tr> --}}
{{--            <td width="30%"><span style="margin-left: 5px;">WEEKSTAAT ID:</span></td> --}}
{{--            <td width="70%">{{ $week_state->id }}</td> --}}
{{--        </tr> --}}
{{--    </table> --}}
{{-- </div> --}}

{{-- <table class="weekcard"> --}}
{{--    <tr> --}}
{{--        <th width="10%" rowspan="2" class="center">W. nr.</th> --}}
{{--        <th width="25%" rowspan="2" class="center">Naam werknemer</th> --}}
{{--        <th width="40%" colspan="7" class="center">Gewerkte uren</th> --}}
{{--        <th width="10%" rowspan="2" class="center">Totaal</th> --}}
{{--        <th width="35%" rowspan="2" class="center">Opmerkingen</th> --}}
{{--    </tr> --}}
{{--    <tr> --}}
{{--        <th width="4%" class="center">Ma</th> --}}
{{--        <th width="4%" class="center">Di</th> --}}
{{--        <th width="4%" class="center">Wo</th> --}}
{{--        <th width="4%" class="center">Do</th> --}}
{{--        <th width="4%" class="center">Vr</th> --}}
{{--        <th width="4%" class="center">Za</th> --}}
{{--        <th width="4%" class="center">Zo</th> --}}
{{--    </tr> --}}
{{--    @foreach ($week_state->weekCards()->where('directing', '=', $directing)->get() as $key => $weekCard) --}}
{{--        <tr> --}}
{{--            <td class="center">{{ $key+1 }}</td> --}}
{{--            <td class="left">{{ $weekCard->personnel->first_name . ' ' . $weekCard->personnel->last_name }}</td> --}}
{{--            <td class="center padding">{{ $weekCard->hours_1 == '0.0' ? '-' : $weekCard->hours_1 }}</td> --}}
{{--            <td class="center padding">{{ $weekCard->hours_2 == '0.0' ? '-' : $weekCard->hours_2 }}</td> --}}
{{--            <td class="center padding">{{ $weekCard->hours_3 == '0.0' ? '-' : $weekCard->hours_3 }}</td> --}}
{{--            <td class="center padding">{{ $weekCard->hours_4 == '0.0' ? '-' : $weekCard->hours_4 }}</td> --}}
{{--            <td class="center padding">{{ $weekCard->hours_5 == '0.0' ? '-' : $weekCard->hours_5 }}</td> --}}
{{--            <td class="center padding">{{ $weekCard->hours_6 == '0.0' ? '-' : $weekCard->hours_6 }}</td> --}}
{{--            <td class="center padding">{{ $weekCard->hours_7 == '0.0' ? '-' : $weekCard->hours_7 }}</td> --}}
{{--            <td class="center">{{ $weekCard->total_hours }}</td> --}}
{{--            <td class="left padding">{{ $weekCard->comments }}</td> --}}
{{--        </tr> --}}
{{--    @endforeach --}}
{{--    <tr> --}}
{{--        <td colspan="9" class="right strong">Totale uren:</td> --}}
{{--        <td class="right">{{ $week_state->weekCards()->where('directing','=',$directing)->sum('total_hours') }}</td> --}}
{{--        <td></td> --}}
{{--    </tr> --}}
{{-- </table> --}}

{{-- <div class="note">Notitie:</div> --}}
{{-- <table width="90%" style="font-size: 12px;" class="address"> --}}
{{--    <tr> --}}
{{--        <td width="15%">Handtekening:</td> --}}
{{--        <td style="border-bottom: 1px solid black;" width="30%"></td> --}}
{{--        <td width="10%"></td> --}}
{{--        <td width="15%">Uitvoerder:</td> --}}
{{--        <td style="border-bottom: 1px solid black;" width="30%">{{ $performerName }}</td> --}}
{{--    </tr> --}}
{{--    <tr> --}}
{{--        <td colspan="5">&nbsp;</td> --}}
{{--    </tr> --}}
{{--    <tr> --}}
{{--        <td colspan="2"></td> --}}
{{--        <td></td> --}}
{{--        <td>Mobilenummer:</td> --}}
{{--        <td style="border-bottom: 1px solid black;">{{ $performerMobile }}</td> --}}
{{--    </tr> --}}
{{--    <tr> --}}
{{--        <td colspan="5">&nbsp;</td> --}}
{{--    </tr> --}}
{{--    <tr> --}}
{{--        <td colspan="2"></td> --}}
{{--        <td></td> --}}
{{--        <td>E-mail adres:</td> --}}
{{--        <td style="border-bottom: 1px solid black;">{{ $performerEmail }}</td> --}}
{{--    </tr> --}}
{{--    <tr> --}}
{{--        <td colspan="5">&nbsp;</td> --}}
{{--    </tr> --}}
{{--    <tr> --}}
{{--        <td>Naam:</td> --}}
{{--        <td style="border-bottom: 1px solid black;"></td> --}}
{{--        <td></td> --}}
{{--        <td>Project Adress:</td> --}}
{{--        <td style="border-bottom: 1px solid black;">{{ $project->city }}, {{ $project->address }}</td> --}}
{{--    </tr> --}}
{{-- </table> --}}

{{-- <div class="app-info"> --}}
{{--    <p style="color: red;">Maak gebruik van onze handige mobiele App voor container bestellingen en personeelsaanvragen. Zie onze website www.easycleanup.nl voor de download link van de mobiele App</p> --}}
{{--    <div class="app-icons"> --}}
{{--        <img src="{{ asset('images/google_play.jpg') }}"> --}}
{{--        <img src="{{ asset('images/app_store.jpg') }}"> --}}
{{--    </div> --}}
{{-- </div> --}}

{{-- <footer> --}}
{{--    <div class="pagenum-container"><span class="pagenum"></span></div> --}}
{{-- </footer> --}}
{{-- </body> --}}
{{-- </html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .padding {
            padding: 2px;
        }

        .margin-top {
            margin-top: 10px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 10px;
            text-align: center;
            font-size: 10px;
        }

        .weekcard table,
        .weekcard td,
        .weekcard th {
            border: 1px solid #000;
            box-shadow: none;
            text-shadow: none;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }

        .strong {
            font-weight: bold;
        }

        .weekcard th,
        .weekcard td {
            font-size: 10px;
        }

        .address td,
        th {
            line-height: 14px;
        }

        img {
            vertical-align: top;
            height: 100px;
            width: 200px;
            margin-bottom: 15px;
        }

        .note {
            font-size: 12px;
            margin-top: 20px;
        }

        .app-info {
            text-align: center;
            font-size: 12px;
            margin-top: 10px;
        }

        .app-icons img {
            height: 50px;
            margin: 0 5px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td width="25%"><img src="{{ asset('images/easy_clean.jpg') }}" /></td>
            <td width="75%" class="center" style="font-size: 12px;">
                <h1>Easy Clean UP B.V.</h1>
                <p>Kollenbergweg 78 - 1101 AV Amsterdam</p>
                <p>Tel: 020 - 691 61 15, E-mail: planning@easycleanup.nl</p>
                <p>Schoonmaakonderhoud - Bouwopruiming - Opleveringsschoonmaak</p>
                <p>Glasbewassing - Gevelreiniging - Keetschoonmaak - Bedrijfsdiensten</p>
                <p>Bouwafval management & Containers Service</p>
            </td>
        </tr>
    </table>

    <div style="border: 1px solid #000; padding: 5px;">
        <p class="center">WEEKSTAAT</p>
        <table>
            <tr>
                <td width="30%"><span style="margin-left: 5px;">OPDRACHTGEVER:</span></td>
                <td width="70%">{{ $departmentName }}</td>
            </tr>
            <tr>
                <td width="30%"><span style="margin-left: 5px;">WEEK NUMMER:</span></td>
                <td width="70%">{{ $week_state->week_no }}</td>
            </tr>
            <tr>
                <td width="30%"><span style="margin-left: 5px;">PROJECTNAAM:</span></td>
                <td width="70%">{{ $project->name }}</td>
            </tr>
            <tr>
                <td width="30%"><span style="margin-left: 5px;">KLANT PROJ. NR.:</span></td>
                <td width="70%">{{ $project->customer->name }}</td>
            </tr>
            <tr>
                <td width="30%"><span style="margin-left: 5px;">ECU PROJECT/DEBITEUR #:</span></td>
                <td width="70%"></td>
            </tr>
            <tr>
                <td width="30%"><span style="margin-left: 5px;">WEEKSTAAT ID:</span></td>
                <td width="70%">{{ $week_state->id }}</td>
            </tr>
        </table>
    </div>

    <table class="weekcard">
        <tr>
            <th width="10%" rowspan="2" class="center">W. nr.</th>
            <th width="25%" rowspan="2" class="center">Naam werknemer</th>
            <th width="40%" colspan="7" class="center">Gewerkte uren</th>
            <th width="10%" rowspan="2" class="center">Totaal</th>
            <th width="35%" rowspan="2" class="center">Opmerkingen</th>
        </tr>
        <tr>
            <th width="4%" class="center">Ma</th>
            <th width="4%" class="center">Di</th>
            <th width="4%" class="center">Wo</th>
            <th width="4%" class="center">Do</th>
            <th width="4%" class="center">Vr</th>
            <th width="4%" class="center">Za</th>
            <th width="4%" class="center">Zo</th>
        </tr>
        @foreach ($week_state->weekCards()->where('directing', '=', $directing)->get() as $key => $weekCard)
            <tr>
                <td class="center">{{ $key + 1 }}</td>
                <td class="left">{{ $weekCard->personnel->first_name . ' ' . $weekCard->personnel->last_name }}</td>
                <td class="center padding">{{ $weekCard->hours_1 == '0.0' ? '-' : $weekCard->hours_1 }}</td>
                <td class="center padding">{{ $weekCard->hours_2 == '0.0' ? '-' : $weekCard->hours_2 }}</td>
                <td class="center padding">{{ $weekCard->hours_3 == '0.0' ? '-' : $weekCard->hours_3 }}</td>
                <td class="center padding">{{ $weekCard->hours_4 == '0.0' ? '-' : $weekCard->hours_4 }}</td>
                <td class="center padding">{{ $weekCard->hours_5 == '0.0' ? '-' : $weekCard->hours_5 }}</td>
                <td class="center padding">{{ $weekCard->hours_6 == '0.0' ? '-' : $weekCard->hours_6 }}</td>
                <td class="center padding">{{ $weekCard->hours_7 == '0.0' ? '-' : $weekCard->hours_7 }}</td>
                <td class="center">{{ $weekCard->total_hours }}</td>
                <td class="left padding">{{ $weekCard->comments }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="9" class="right strong">Totale uren:</td>
            <td class="right">{{ $week_state->weekCards()->where('directing', '=', $directing)->sum('total_hours') }}
            </td>
            <td></td>
        </tr>
    </table>

    <div class="note">Notitie:</div>
    <table width="90%" style="font-size: 12px;" class="address">

        <tr>
            <td width="15%">Handtekening:</td>
            <td style="border-bottom: 1px solid black;" width="30%"></td>
            <td width="10%"></td>
            <td width="15%">Uitvoerder:</td>
            <td style="border-bottom: 1px solid black;" width="30%">{{ $performerName }}</td>
        </tr>

        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>

        <tr>
            <td>Naam:</td>
            <td style="border-bottom: 1px solid black;"></td>
            <td></td>
            <td>Mobilenummer:</td>
            <td style="border-bottom: 1px solid black;">{{ $performerMobile }}</td>
        </tr>

        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>E-mail adres:</td>
            <td style="border-bottom: 1px solid black;">{{ $performerEmail }}</td>
        </tr>

        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Project Adres:</td>
            <td style="border-bottom: 1px solid black;">{{ $project->city }}, {{ $project->address }}</td>
        </tr>

    </table>
    <div class="app-info">
        <p style="color: red;">Maak gebruik van onze handige mobiele App voor container bestellingen en
            personeelsaanvragen. Zie onze website www.easycleanup.nl voor de download link van de mobiele App</p>
        <div class="app-icons">
            <img src="{{ asset('images/google_play.jpg') }}">
            <img src="{{ asset('images/app_store.jpg') }}">
        </div>
    </div>

    <footer>
        <div class="pagenum-container"><span class="pagenum"></span></div>
    </footer>
</body>

</html>
