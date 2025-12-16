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
            margin: 160px 40px 80px 40px;
        }

        header {
            position: fixed;
            top: -140px;
            left: 0;
            right: 0;
            height: 120px;
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
                <td width="70%">
                    <img src="{{ asset('images/easy_clean.jpg') }}" alt="Logo" style="width:300px;">
                </td>
                <td width="60%" align="right" class="company-block">
                    </b>{{ $hardcoded['company_name'] }}<b> <br>
                        {!! nl2br(e($hardcoded['company_address'])) !!}<br>
                        Tel.: {{ $hardcoded['company_phone'] }}<br>
                        Website: {{ $hardcoded['company_website'] }}<br>
                        E-mail: {{ $hardcoded['company_email'] }}<br><br>
                        IBAN: {{ $hardcoded['iban'] }}<br>
                        KvK Amsterdam: {{ $hardcoded['kvk'] }}<br>
                        BTW nr.: {{ $hardcoded['btw'] }}<br>
                </td>
            </tr>
        </table>
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
        {{-- <h1 class="document-title">{{ $offer->subject }}</h1> --}}
        <p>Datum:{{ \Carbon\Carbon::parse($offer->date)->format('d F Y') }}</p>
        <p>Betreft: keetonderhoud/portier</p>
        <p>Project: {{ $offer->project->name ?? '-' }}</p>
        <p>Ons kenmerk: {{ $offer->our_reference }}</p>

        <div style="margin-top: 100px;">
            <p>Geachte heer/mevrouw,</p>
            <p>Hierbij hebben wij het genoegen u onze offerte te doen toekomen voor de opleverschoonmaak van het
                bovengenoemd project.</p>
            <p>Wij danken u hartelijk voor uw aanvraag. Mocht u nog aanvullende vragen hebben vernemen wij dit graag van
                u.</p>
            <p>Met vriendelijke groet,<br>Easy Clean Up BV<br>Shakeel Rehman<br><br>___________________<br>29502189
            </p>
        </div>

        <div style="page-break-after: always;"></div>

        <div style="border: 2px solid #000; padding: 15px; font-family: Arial, sans-serif; font-size: 14px;">
            <div style="text-align: center; font-weight: bold; font-size: 18px; margin-bottom: 10px;">
                {{ $offer->title ?? 'Offerte Keetonderhoud' }}
            </div>
            <hr>
            <div style="margin-bottom: 15px;">
                <b>{{ $offer->unit ?? '0' }} units</b>
            </div>

            <table width="100%" style="margin-bottom: 15px;">
                <tr>
                    <td>
                        <b>{{ $offer->desc_hour ?? '' }}</b>
                    </td>
                    <td style="text-align: right;">
                        Prijs: € {{ number_format($offer->price_hour, 2, ',', '.') }} per keer
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>{{ $offer->desc_time ?? '' }}</b>
                    </td>
                    <td style="text-align: right;">
                        Prijs: € {{ number_format($offer->price_time, 2, ',', '.') }} per uur
                    </td>
                </tr>
            </table>
            <hr>
            {{-- Looping Scope Groups --}}
            @foreach ($groupedItems as $item)
                <p><b>{{ $item['name'] }}</b></p>
                <ul>
                    @foreach ($item['descriptions'] as $desc)
                        <li>{{ $desc }}</li>
                    @endforeach
                </ul>
            @endforeach
        </div>

        <div style="page-break-after: always;"></div>

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
