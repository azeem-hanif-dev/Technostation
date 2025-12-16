<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Offerte: Offerte Verkeersregelaars en bouwhulp</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 120px 40px 60px 40px;
        }

        /* Har page header & footer ke liye */
        @page {
            margin: 220px 40px 80px 40px;
        }

        header {
            position: fixed;
            top: -200px;
            left: 0;
            right: 0;
            height: 180px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            font-size: 10px;
            text-align: center;
            border-top: 1px solid #4CAF50;
            padding-top: 5px;
        }

        .company-block {
            font-size: 11px;
            line-height: 1.4;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
        }

        .section-title {
            background: #6d6b6b3a;
            color: #080808;
            padding: 5px;
            font-size: 14px;
            margin: 20px 0 10px;
            justify-content: center;
            text-align: center;
        }

        .conditions-list {
            font-size: 11px;
        }
    </style>
</head>

<body>
    <header>
        <table width="100%">
            <tr>
                <td width="50%">
                    <img src="{{ asset('images/easy_clean.jpg') }}" alt="Logo" style="width:260px;">
                </td>
                <td width="50%" align="left" class="company-block" style="padding-left: 60px;">
                    {{ $hardcoded['company_name'] }}<br>
                    <b>{!! nl2br(e($hardcoded['company_address'])) !!}</b><br>
                    <b>Tel: {{ $hardcoded['company_phone'] }}</b><br>
                    <b> Website: {{ $hardcoded['company_website'] }}</b><br>
                    <b>E-mail: {{ $hardcoded['company_email'] }}</b><br>
                    <b>IBAN: {{ $hardcoded['iban'] }}</b><br>
                    <b>KvK Amsterdam: {{ $hardcoded['kvk'] }}</b><br>
                    <b> BTW nr.: {{ $hardcoded['btw'] }}</b><br>
                </td>
            </tr>
        </table>

        <div style="margin-top:15px; font-size: 11px;">
            <p><b>Datum:</b> {{ \Carbon\Carbon::parse($offer->date)->format('d F Y') }}</p>
            <p><b>Betreft:</b> {{ $offer->service_type }}</p>
            <p><b>Project:</b> {{ $offer->project->name ?? '-' }}</p>
            <p><b>Project nummer:</b> {{ $offer->project->project_number ?? '-' }}</p>
            <p><b>Ons kenmerk:</b> {{ $offer->our_reference }}</p>
        </div>
    </header>

    <footer style="font-size: 10px; border-top: 1px solid #4CAF50; padding-top: 5px;">
        <table width="100%">

            <tr>
                <td align="left">{{ $offer->project->customer->name ?? 'N/A' }}</td>
                <td align="center">{{ $offer->our_reference }}</td>
                <td align="right">{{ \Carbon\Carbon::today()->format('d F Y') }}</td>

            </tr>
        </table>
    </footer>

    <!-- MAIN CONTENT -->
    <main>
        {{-- PAGE 1: Letter --}}
        <div style="margin-top: 100px;">
            <p>Geachte heer/mevrouw,</p>
            <p>Hierbij hebben wij het genoegen u onze offerte te doen toekomen voor de opleverschoonmaak van het
                bovengenoemd project.</p>
            <p>Wij danken u hartelijk voor uw aanvraag. Mocht u nog aanvullende vragen hebben vernemen wij dit graag van
                u.</p>
            <p>Met vriendelijke groet,<br>Easy Clean Up BV<br>___________________<br><br>S.Rehman<br>29502189
            </p>
        </div>

        <div style="page-break-after: always;"></div>

        <div
            style=" margin-top:200px; border: 2px solid #000; padding: 15px; font-family: Arial, sans-serif; font-size: 14px;">
            <p>
            <h2 style="text-decoration: underline;">{{ $offer->project->name }}</h2>
            @if ($offer->service_type === 'Traffic Controller')
                <b>
                    <h4 style="margin-bottom: 4px;">Traffic Controller</h4>
                </b>
                <p style="text-align: right; margin-top: 0; margin-bottom: 6px;">
                    <b>Prijs:</b> &euro;{{ number_format($offer->traffic_controllers_price, 2, '.', ',') }}
                </p>
            @elseif ($offer->service_type === 'Construction Helper')
                <b>
                    <h4 style="margin-bottom: 4px;">Construction Helper</h4>
                </b>
                <p style="text-align: right; margin-top: 0; margin-bottom: 6px;">
                    <b>Prijs:</b> &euro;{{ number_format($offer->construction_price, 2, '.', ',') }}
                </p>
            @else
                <b>
                    <h5 style="margin-bottom: 4px;">Traffic Controller</h5>
                    <p style="text-align: right; margin-bottom: 8px;">
                        <b>Prijs:</b> &euro;{{ number_format($offer->traffic_controllers_price, 2, '.', ',') }}
                    </p>
                    <h5 style="margin-top: 4px; margin-bottom: 4px;">Construction Helper</h5>
                    <p style="text-align: right; margin-bottom: 0;">
                        <b>Prijs:</b> &euro;{{ number_format($offer->construction_price, 2, '.', ',') }}
                    </p>
                </b>
            @endif
            <p>
                <b>Minimale afname 6 uur per dag <br> Binnen 72 uur afmelden dienen de uren afgenomen te worden.</b>
            </p>
            </p>
        </div>
        <b>Prijzen geldig t/m {{ $offer->date }}</b>
        <div style="page-break-after: always;"></div>
        {{-- PAGE 3: Voorwaarden --}}
        <h2 class="section-title">Voorwaarden</h2>
        <ul class="conditions-list">
            <li>Eventuele, extra, werkzaamheden worden uitgevoerd op basis van regie-uren à € 39,50 per uur</li>
            <li>Aangenomen werk wordt uitgevoerd op basis van een volle werkdag.</li>
            <li>Onze prijs is inclusief schoonmaakmiddelen- en materialen.</li>
            <li>Onze prijs is exclusief het inhuren van steigers en/of hoogwerkers</li>
            <li>De riolering dient aangesloten te zijn i.v.m. lozen afval water.</li>
            <li>Warm water en stroom dient in de woning of in het pand aanwezig te zijn.</li>
            <li>Indien er bewassingswerkzaamheden plaatsvinden dient aangegeven te
                worden of het sterk verontreinigt en/of krasgevoelig glas betreft. Mocht dit
                niet voor aanvang van de werkzaamheden gemeld worden zijn wij op generlei
                wijze aansprakelijk voor eventuele schade aan de ramen.</li>
            <li>Betalingstermijn: binnen 30 dagen - rekeningnummer 65.63.01.872 – ING bank</li>
            <li>Onze algemene voorwaarden zijn gratis te downloaden via www.easycleanup.nl of te verkrijgen via kantoor.
            </li>
        </ul>
    </main>
</body>

</html>
