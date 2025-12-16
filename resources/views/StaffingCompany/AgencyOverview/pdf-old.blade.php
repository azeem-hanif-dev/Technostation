<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title></title>

    <style>
        body {

            font-family: "Arial", Gadget, sans-serif;
            /*margin:2px; */
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .padding {
            padding: 3px;
        }

        .right {
            text-align: right;
        }

        .margin-top {
            margin-top: 20px !important
        }

        /*footer { position: relative ; bottom: -20px; left: 0px; right: 0px; text-align:left;  height: 20px; text-decoration:underline; }*/
        footer .pagenum:before {
            content: 'nothing';
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 10px;
            text-decoration: underline;

        }


        .weekcard table,
        td,
        th {

            border: 1px solid #000;
            box-shadow: none;
            text-shadow: none;
        }

        table {

            border-collapse: collapse;
        }

        .strong {
            font-weight: bold;
        }

        .weekcard th {
            font-size: 12px !important;
        }

        .weekcard td {
            font-size: 11px !important;
        }

        .address>td,
        th {

            line-height: 11px;
        }
    </style>

</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"
    style="-webkit-font-smoothing: antialiased; width: 100% !important;  -webkit-text-size-adjust: none;">


    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:-10px;">

        <tr>

            <td width="25%" style="border: none;">

                <img src="{{ asset('images/easy_clean.jpg') }}"
                    style=" vertical-align:top; height:135px; width:270px; margin-bottom:25px;" />
            </td>

            <td width="60%" style="line-height:4px; vertical-align:bottom; font-size:13px; border: none;">

                <h1 class="center">Easy Clean UP B.V.</h1>

                <p class="center">Kollenbergweg 78 - 1101 AV Amsterdam</p>

                <p class="center">Tel: 020 - 691 61 15, E-mail: info@easycleanup.nl</p>

                <p class="center">&nbsp;</p>

                <p class="center">Schoonmaakonderhoud - Bouwopruiming - Opleveringsschoonmaak</p>

                <p class="center">Glasbewassing - Gevelreiniging - Keetschoonmaak - Bedrijfsdiensten</p>

                <p class="center">Bouwafval management & Containers Service</p>


            </td>

        </tr>

    </table>


    <div style="width:101%; border:1px solid #000">

        <p class="center" style="line-height:4px;">WEEKSTAAT</p>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">

            <tr>

                <td width="30%" style="border: none;"><span
                        style="margin-left:10px;text-align:left;font-size:12px;">OPDRACHTGEVER:</span></td>
                <td width="65%" style="border: none;">

                </td>

            </tr>

            <tr>

                <td width="30%" style="border: none;"><span
                        style="margin-left:10px;text-align:left;font-size:12px;">WEEK
                        NUMMER:</span></td>

                <td width="60%" style="border: none;">
                    {{ $week_no }}
                </td>

            </tr>


            <tr>

                <td width="30%" style="border: none;"><span
                        style="margin-left:10px;text-align:left; font-size:12px;">Start date:</span></td>

                <td width="60%" style="border: none;">
                    {{ $start_date }}
                </td>

            </tr>

            <tr>

                <td width="30%" style="border: none;"><span style="margin-left:10px;font-size:12px">End Date:</span>
                </td>

                <td width="60%" style="border: none;">
                    {{ $end_date }}
                </td>

            </tr>

            <tr>

                <td width="30%" style="border: none;"><span style="margin-left:10px;font-size:12px">ECU
                        PROJECT/DEBITEUR #:</span></td>

                <td width="60%" style="border: none;">
                </td>

            </tr>
            <tr>

                <td width="30%" style="border: none;"><span style="margin-left:10px;font-size:12px">WEEKSTAAT
                        ID:</span></td>

                <td width="60%" style="border: none;">
                    {{ $ids }}
                </td>

            </tr>

        </table>
    </div>


    <table width="100%" class="weekcard">

        <tr>

            <th width="10%" rowspan="2" class="center">W. nr.</th>

            <th width="25%" rowspan="2" class="center">Naam werknemer</th>

            <th width="40%" colspan="15" class="center">Gewerkte uren</th>

        </tr>
        <tr>

            <th width="4%" class="center">Ma</th>

            <th width="4%" class="center">Di</th>

            <th width="4%" class="center">Wo</th>

            <th width="4%" class="center">Do</th>

            <th width="4%" class="center">Vr</th>

            <th width="4%" class="center">Za</th>

            <th width="4%" class="center">Zo</th>

            <th width="7%" class="center">Totaal</th>

            <th width="30%" class="center" colspan="7">Tarief per uur</th>
        </tr>
        @php $serial_number = 1; @endphp

        @foreach ($grouped_week_cards as $weekCard)
            <tr>
                <td class="center">{{ $serial_number++ }}</td>
                <td class="left">{{ $weekCard->personnel->first_name . ' ' . $weekCard->personnel->last_name }}</td>
                <td class="center padding">{{ $weekCard->hours_1 }}</td>
                <td class="center padding">{{ $weekCard->hours_2 }}</td>
                <td class="center padding">{{ $weekCard->hours_3 }}</td>
                <td class="center padding">{{ $weekCard->hours_4 }}</td>
                <td class="center padding">{{ $weekCard->hours_5 }}</td>
                <td class="center padding">{{ $weekCard->hours_6 }}</td>
                <td class="center padding">{{ $weekCard->hours_7 }}</td>
                <td class="right padding">{{ $weekCard->total_hours }}</td>
                <td colspan="7" class="right padding">{{ $weekCard->personnel->cost_per_hour }}</td>
            </tr>
        @endforeach

        {{-- <span style="page-break-before: always;">&nbsp;</span> --}}
        <footer style="margin-top:160px !important; ">
            <div class="pagenum-container"><span class="pagenum"></span></div>
        </footer>

        <tr>

            <td class="center padding">&nbsp;</td>

            <td class="left padding">&nbsp;</td>

            <td class="center padding">&nbsp;</td>

            <td class="center padding">&nbsp;</td>

            <td class="center padding">&nbsp;</td>

            <td class="center padding">&nbsp;</td>

            <td class="center padding">&nbsp;</td>

            <td class="center padding">&nbsp;</td>

            <td class="center padding">&nbsp;</td>

            <td class="center padding">&nbsp;</td>

            <td colspan="7" class="center padding">&nbsp;</td>

        </tr>


        <tr>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <th colspan="6" align="right" class="strong" style="font-size:14px;">Totale uren:&nbsp;</th>

            <td class="right">
                {{ $all_week_cards_total_hours }}
            </td>
            <td colspan="7" class="center">&nbsp;</td>

        </tr>

    </table>


    <span class="margin-top" style="margin:15px;">Notitie:

    </span>


    {{-- <table width="90%" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;" class="address">

        <tr>

            <td width="5%" style="border: none;">Handtekening:</td>

            <td style="border: none; border-bottom: 1px solid black;" width="20%">&nbsp;</td>

            <td width="5%" style="border: none; ">&nbsp;</td>

            <td width="5%" style="border: none;">Uitvoerder:</td>

            <td width="20%" style="border: none; border-bottom: 1px solid black; margin-left:10px; ">

            </td>

        </tr>


        <tr>

            <td colspan="5" style="border: none;">&nbsp;</td>


        </tr>


        <tr>


            <td width="5%" style="border: none;">Naam:</td>
            <td style="border: none; border-bottom: 1px solid black;">
                {{ $weekCard->personnel->agency->name ?? 'N/A' }}
            </td>

            <td style="border: none; border-bottom: 1px solid black;" width="20%">&nbsp;</td>


            <td width="5%" style="border: none;">&nbsp;</td>


            <td width="5%" style="border: none;">Mobilenummer:</td>

            <td width="20%" style="border: none; border-bottom: 1px solid black; margin-left:10px; ">

            </td>

        </tr>

        <tr>

            <td style="border: none;" colspan="5">&nbsp;</td>


        </tr>

        <tr>

            <td colspan="3" width="25%" style="border: none;">&nbsp;</td>


            <td width="15%" style="border: none;">E-mail adres:</td>
            <td style="border: none; border-bottom: 1px solid black;">
                {{ $weekCard->personnel->agency->email ?? 'N/A' }}
            </td>
            <td width="50%" style="border: none; border-bottom: 1px solid black; margin-left:10px; ">

            </td>

        </tr>
        <tr>

            <td colspan="5" style="border: none;">&nbsp;</td>
        </tr>

        <tr>

            <td colspan="3" width="25%" style="border: none;">&nbsp;</td>

        </tr>
        <tr>
            <td colspan="4" width="50%" style="border: none;">&nbsp;</td>
        </tr>

    </table> --}}
    <table width="90%" border="0" cellpadding="4" cellspacing="0" style="font-size:12px;" class="address">
    <tr>
        <td style="border: none; white-space: nowrap;">Naam:</td>
        <td style="border: none; border-bottom: 1px solid black; width: 40%;">
            {{ $weekCard->personnel->agency->name ?? 'N/A' }}
        </td>
        <td style="border: none; white-space: nowrap;">Mobilenummer:</td>
        <td style="border: none; border-bottom: 1px solid black; width: 40%;">
            {{ $weekCard->personnel->agency->mobile ?? 'N/A' }}
        </td>
    </tr>

    <tr><td colspan="4" style="border: none; height: 10px;"></td></tr>

    <tr>
        <td style="border: none; white-space: nowrap;">E-mail adres:</td>
        <td colspan="3" style="border: none; border-bottom: 1px solid black;">
            {{ $weekCard->personnel->agency->email ?? 'N/A' }}
        </td>
    </tr>
</table>

    &nbsp; &nbsp;
    <table width="90%" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;" class="address">
        <tr>
            <td colspan="4" width="50%" style="border: none;">
                <font color="red">Maak gebruik van onze handige mobiele App voor container bestellingen en
                    persoeelsaanvragen. Zie onze website www.easycleanup.nl voor de download link van de mobiele App
                </font>
            </td>
            <td><img style="height: 50px; margin-left: 4px;" src="{{ asset('images/google_play.jpg') }}"></td>
            <td><img style="height: 50px; margin-left: 4px;" src="{{ asset('images/app_store.jpg') }}"></td>
        </tr>

    </table>

</body>

</html>
