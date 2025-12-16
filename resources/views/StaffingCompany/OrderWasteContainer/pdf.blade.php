    @php
        function part_of_day($per)
        {
            switch ($per) {
                case 2:
                    return 'Morning';
                case 3:
                    return 'Afternoon';
                case 4:
                    return 'Evening';
                default:
                    return 'As soon as possible';
            }
        }
    @endphp
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PDF - Container Order</title>
        {{--    <style> --}}
        {{--        .table-col table { --}}
        {{--            width: 100%; --}}
        {{--            table-layout: fixed; --}}
        {{--        } --}}
        {{--        @page { --}}
        {{--            size: A4 landscape; --}}
        {{--            margin: 10mm; --}}
        {{--        } --}}
        {{--        body { --}}
        {{--            font-family: Arial, sans-serif; --}}
        {{--            line-height: 1.3; --}}
        {{--            font-size: 10px; --}}
        {{--            margin: 0; --}}
        {{--            padding: 0; --}}
        {{--        } --}}
        {{--        .text-center{ --}}
        {{--            text-align: center; --}}
        {{--        } --}}
        {{--        .container { --}}
        {{--            width: 100%; --}}
        {{--            margin: 0 auto; --}}
        {{--            padding: 5px; --}}
        {{--        } --}}
        {{--        .logo-container { --}}
        {{--            width: 300px; --}}
        {{--            margin-bottom: 5px; --}}
        {{--            margin: auto; --}}
        {{--        } --}}
        {{--        .logo { --}}
        {{--            max-width: 194px; --}}

        {{--            height: auto; --}}
        {{--        } --}}
        {{--        h4, h5 { --}}
        {{--            margin: 8px 0; --}}
        {{--            font-weight: bold; --}}
        {{--            font-size: 11px; --}}
        {{--        } --}}
        {{--        table { --}}
        {{--            width: 100%; --}}
        {{--            border-collapse: collapse; --}}
        {{--            margin-bottom: 10px; --}}
        {{--            border: 1px solid #000; --}}
        {{--            page-break-inside: avoid; --}}
        {{--        } --}}
        {{--        th, td { --}}
        {{--            border: 1px solid #000; --}}
        {{--            padding: 3px 4px; --}}
        {{--            font-size: 9px; --}}
        {{--        } --}}
        {{--        th { --}}
        {{--            background-color: #f8f8f8; --}}
        {{--            font-weight: bold; --}}
        {{--        } --}}
        {{--        .text-center { --}}
        {{--            text-align: center; --}}
        {{--        } --}}
        {{--        .align-middle { --}}
        {{--            vertical-align: middle; --}}
        {{--        } --}}
        {{--        .tables-row { --}}
        {{--            display: flex; --}}
        {{--            gap: 10px; --}}
        {{--            width: 100%; --}}
        {{--            margin: 10px 0; --}}
        {{--        } --}}
        {{--        .table-col { --}}
        {{--            flex: 1; --}}
        {{--            min-width: 0; --}}
        {{--        } --}}
        {{--        .footer { --}}
        {{--            text-align: center; --}}
        {{--            margin-top: 15px; --}}
        {{--            font-size: 9px; --}}
        {{--            page-break-inside: avoid; --}}
        {{--        } --}}
        {{--        .bold { --}}
        {{--            font-weight: bold; --}}
        {{--        } --}}
        {{--        .avoid-break { --}}
        {{--            page-break-inside: avoid; --}}
        {{--        } --}}

        {{--    </style> --}}
        <style>
            .table-col table {
                width: 100%;
                table-layout: fixed;
            }

            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                font-family: Arial, sans-serif;
                line-height: 1.3;
                font-size: 22px;
                margin: 0;
                padding: 0;
            }

            .text-center {
                text-align: center;
            }

            .container {
                width: 100%;
                margin: 0 auto;
                padding: 5px;
            }

            .logo-container {
                width: 300px;
                margin-bottom: 5px;
                margin: auto;
            }

            .logo {
                max-width: 194px;
                height: auto;
            }

            h5 {
                margin: 8px 0;
                font-weight: bold;
                font-size: 13px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
                border: 1px solid #000;
                page-break-inside: avoid;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 3px 4px;
                font-size: 15px;
            }

            th {
                background-color: #f8f8f8;
                font-weight: bold;
            }

            .text-center {
                text-align: center;
            }

            .align-middle {
                vertical-align: middle;
            }

            .tables-row {
                display: flex;
                gap: 10px;
                width: 100%;
                margin: 10px 0;
            }

            .table-col {
                flex: 1;
                min-width: 0;
            }

            .footer {
                text-align: center;
                margin-top: 15px;
                font-size: 11px;
                page-break-inside: avoid;
            }

            .bold {
                font-weight: bold;
            }

            .avoid-break {
                page-break-inside: avoid;
            }
        </style>
    </head>

    <body>

        <div class="container">
            <div class="logo-container avoid-break text-center">
                <img src="{{ asset('images/easy_clean.jpg') }}" class="logo"
                    style="vertical-align:top; margin-top:40px " />
            </div>

            <div class="avoid-break">
                <h4>BESTELFORMULIER AFVALCONTAINERS</h4>
                <table>
                    <tr>
                        <th>Afvalverwerker</th>
                        {{--                <td colspan="4">{{ $data->supplier->company_name }}</td> --}}
                        <td colspan="4">
                            {{ is_object($data->supplier) && $data->supplier->company_name ? $data->supplier->company_name : 'ali' }}
                        </td>
                    </tr>
                    <tr>
                        <th>E-mail adres</th>
                        <td colspan="4">
                            {{ is_object($data->supplier) && $data->supplier->email ? $data->supplier->email : 'supplier@gmail.com' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Besteldatum</th>
                        <td>{{ $today }} / {{ $dayName }}</td>
                        <td><span class="bold">Tijd:</span> {{ $currentTime }}</td>
                        <td class="bold">Nummer</td>
                        <td>BN-{{ $data->project_id }}</td>
                    </tr>
                </table>
            </div>

            <div class="avoid-break">
                <h5>Opdracht: </h5>
                <table>
                    <tr>
                        <th>Klantnaam</th>
                        <td>{{ @$data->project->customer->name }}</td>
                        <td class="bold">Project nummer</td>
                        <td>PB-{{ $data->project_id }}</td>
                    </tr>
                    <tr>
                        <th>Project naam</th>
                        <td colspan="3">{{ $data->project->name }} - {{ $data->project_id }}</td>
                    </tr>
                    <tr>
                        <th>Werkadres</th>
                        <td colspan="3">{{ $data->project->address }}</td>
                    </tr>
                    <tr>
                        <th>Contactpersoon</th>
                        <td>{{ $data->project->projectPerformer->first_name }}
                            {{ $data->project->projectPerformer->last_name }}</td>
                        <td class="bold">Telefoonnummer</td>
                        <td>{{ $data->project->projectPerformer->mobile }}</td>
                    </tr>
                    <tr>
                        <th>Uitvoerdatum</th>
                        <td colspan="3">
                            {{ $data->execution_date ?? '' }}
                            @if (!empty($data->execution_date))
                                {{ \Carbon\Carbon::parse($data->execution_date)->format('l') }}
                            @endif
                        </td>
                    </tr>

                    </tr>

                    <tr>
                        <th>Dagdeel / gewenste tijd</th>
                        <td colspan="3">{{ part_of_day($data->part_of_day) }}</td>
                    </tr>
                    <tr>
                        <th>Opmerkingen</th>
                        <td colspan="3">{{ $data->notes }}</td>
                    </tr>
                </table>
            </div>
            <!-- START Side-by-side tables using a layout table -->
            <table width="100%" class="weekcard" style="margin-top:15px; border:unset !important ;">
                <tr style="border:unset !important ;">
                    <!-- Left Table Column -->
                    <td style="width:30%; vertical-align:top; border:unset !important ;">
                        <table width="100%" class="weekcard">
                            <tr>
                                <th rowspan="2" class="center">Container type</th>
                                <th colspan="3" class="center">Aantal containers</th>
                            </tr>
                            <tr>
                                <th class="center">Plaats</th>
                                <th class="center">Wissel</th>
                                <th class="center">Afvoer</th>
                            </tr>
                            @foreach ($data->containerOperation as $operation)
                                <tr>
                                    <td class="left">{{ $operation->containerType->name }}</td>
                                    <td class="center">{{ $operation->placement }}</td>
                                    <td class="center">{{ $operation->exchange }}</td>
                                    <td class="center">{{ $operation->discharge }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>

                    <!-- Right Table Column -->
                    <td style="width:70%; vertical-align:top; border:unset !important ;">
                        <table width="100%" class="weekcard" style="border:unset !important ;">
                            <tr>
                                <th colspan="7" class="center">Aantal per afvalstroom</th>
                            </tr>
                            <tr>
                                <th class="center">BSA</th>
                                <th class="center">Puin</th>
                                <th class="center">Hout</th>
                                <th class="center">Plastic Folie</th>
                                <th class="center">Papier</th>
                                <th class="center">Diverse</th>
                                <th class="center">Opmerkingen</th>
                            </tr>
                            @foreach ($data->containerOperation as $operation)
                                @foreach ($operation->wasteBreakdown as $wasteBreak)
                                    <tr>
                                        <td class="center">{{ $wasteBreak->bsa }}</td>
                                        <td class="center">{{ $wasteBreak->debris }}</td>
                                        <td class="center">{{ $wasteBreak->wood }}</td>
                                        <td class="center">{{ $wasteBreak->plastic_foil }}</td>
                                        <td class="center">{{ $wasteBreak->paper }}</td>
                                        <td class="center">{{ $wasteBreak->diverse }}</td>
                                        <td class="center">{{ $wasteBreak->comment }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
            <!-- END Side-by-side tables -->


            <div class="footer">
                <h4>
                    Indien de samenstelling van de afvalstroom afwijkt van hetgeen is opgegeven, afkeuring vermelden op
                    transportbon en foto's van desbetreffende vracht met factuur meesturen
                </h4>
                <p>
                    Easy Clean Up BV | Kollenbergweg 78, 1101 AV Amsterdam | Tel.: 020-6916115 |
                    containers@easycleanup.nl
                </p>
            </div>
        </div>
    </body>

    </html>
