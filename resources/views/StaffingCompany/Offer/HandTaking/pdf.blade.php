<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Offerte - {{ $offer->subject }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 160px 40px 60px 40px;
        }

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
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .company-block {
            font-size: 10px;
            line-height: 1.4;
        }

        .section-title {
            background: #f3f3f3;
            color: #000;
            padding: 6px;
            font-size: 14px;
            margin: 25px 100px 10px;
            text-align: center;
            border: 1px solid #000;
        }

        .conditions-list {
            font-size: 11px;
            line-height: 1.6;
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

        <div style="margin-top: 15px; font-size: 10px;">
            <p><b>Datum: {{ \Carbon\Carbon::parse($offer->date)->format('d F Y') }}</b></p>
            <p><b>Betreft: {{ $offer->subject }}</b></p>
            <p><b>Project: {{ $offer->project->name ?? '-' }}</b></p>
            <p><b>Project nummer: {{ $offer->project->id ?? '-' }}</b></p>
            <p><b>Ons kenmerk: JK/{{ $offer->our_reference }}</b></p>
        </div>
    </header>

    <footer>
        <table width="100%">
            <tr>
                <td align="left">{{ $offer->project->customer->name ?? 'N/A' }}</td>
                <td align="center">JK/{{ $offer->our_reference }}</td>
                <td align="right">{{ \Carbon\Carbon::parse($offer->date)->format('d F Y') }}</td>
            </tr>
        </table>
    </footer>

    <main>
        {{-- Page 1: Letter --}}
        <div style="margin-top: 120px;">
            <p>Geachte heer/mevrouw,</p>
            <p>Hierbij hebben wij het genoegen u onze offerte te doen toekomen voor de opleverschoonmaak van het
                bovengenoemd project.</p>
            <p>Wij danken u hartelijk voor uw aanvraag. Mocht u nog aanvullende vragen hebben vernemen wij dit graag van
                u.</p>
            <p>Met vriendelijke groet,<br>Easy Clean Up BV<br>Shakeel Rehman<br><br>___________________<br>29502189</p>
        </div>

        <div style="page-break-after: always;"></div>

        {{-- Page 2: Details --}}
        <div style="border: 1px solid #000; padding: 15px;">
            <h2 class="section-title">{{ $offer->title }}</h2>
            @foreach ($groupedItems as $item)
                <p><b>{{ $item['name'] ?? 'N/A' }}</b></p>
            @endforeach
            {{-- <p style="text-align: right;">
                <b>Totaal prijs:</b> &euro;{{ number_format($offer->total_price, 2, ',', '.') }}
            </p> --}}
            <hr>
            <p style="text-decoration: underline; text-align:center;">Werkzaamheden als volgt omschreven:</p>
            @foreach ($groupedItems as $item)
                <p><b>{{ $item['name'] }}</b></p>
                <ul>
                    @foreach ($item['descriptions'] as $desc)
                        <li>{{ $desc }}</li>
                    @endforeach
                </ul>
            @endforeach
        </div>

        <div>
            <h2 class="section-title">Opmerkingen</h2>
            <p>{{ $offer->notes ?? 'N/A' }}</p>
        </div>

        <div style="page-break-after: always;"></div>

        {{-- Page 3: Voorwaarden --}}
        <h2 class="section-title">Voorwaarden</h2>
        <ul class="conditions-list">
            <li>Eventuele, extra, werkzaamheden worden uitgevoerd op basis van regie-uren à € 39,50 per uur</li>
            <li>Aangenomen werk wordt uitgevoerd op basis van een volle werkdag.</li>
            <li>Onze prijs is inclusief schoonmaakmiddelen- en materialen.</li>
            <li>Onze prijs is exclusief het inhuren van steigers en/of hoogwerkers</li>
            <li>De riolering dient aangesloten te zijn i.v.m. lozen afval water.</li>
            <li>Warm water en stroom dient in de woning of in het pand aanwezig te zijn.</li>
            <li>Indien er bewassingswerkzaamheden plaatsvinden dient aangegeven te worden of het sterk verontreinigt
                en/of krasgevoelig glas betreft. Mocht dit niet voor aanvang van de werkzaamheden gemeld worden zijn wij
                op generlei wijze aansprakelijk voor eventuele schade aan de ramen.</li>
            <li>Betalingstermijn: binnen 30 dagen - rekeningnummer 65.63.01.872 – ING bank</li>
            <li>Onze algemene voorwaarden zijn gratis te downloaden via www.easycleanup.nl of te verkrijgen via kantoor.
            </li>
        </ul>
    </main>
</body>

</html>
