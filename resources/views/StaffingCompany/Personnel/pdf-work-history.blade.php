<!DOCTYPE html>
<html>
<head>
    <title>Werk Geschiedenis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
        }
        .section {
            margin-top: 20px;
        }
        .section h2 {
            border-bottom: 1px solid #000;
            padding-bottom: 1px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header mb-5">
        <img style="width: 400px; margin-bottom: 30px" src="{{  asset('images/easy_clean.jpg') }}" alt="Image not found">
    </div>
    <div>
        <table>
            <tr>
                <td>Naam:</td>
                <td>{{ $personnel->first_name }} {{ $personnel->last_name }}</td>
            </tr>
            <tr>
                <td>Periode:</td>
                <td>van Week {{ $from_week_no ?? '0' }} t/m Week {{ $to_week_no ?? '0' }}</td>
            </tr>
        </table>
    </div>
    <div class="section">
        <h2>WERK GESCHIEDENIS</h2>
        <table>
            <thead>
            <tr>
                <th>Week number</th>
                <th>Project name</th>
                <th>Uitzendbureau</th>
                <th>Ma</th>
                <th>Di</th>
                <th>Wo</th>
                <th>Do</th>
                <th>Vr</th>
                <th>Za</th>
                <th>Zo</th>
                <th>Totaal</th>
            </tr>
            </thead>
            <tbody>
            @php
                $grand_total_hours = 0;
            @endphp
            @if(isset($plannings) && count($plannings) > 0)
                @foreach($plannings as $planning)
                    @php
                        $totalHours = isset($planning->week_card->total_hours) ? $planning->week_card->total_hours : 0;
                        $grand_total_hours += $totalHours;
                    @endphp
                    <tr>
                        <td>{{ isset($planning->week_no) ? $planning->week_no : 'N/A' }}</td>
                        <td>{{ isset($planning->project_name) ? $planning->project_name : 'N/A' }}</td>
                        <td>{{ isset($personnel->agency->name) ? $personnel->agency->name : 'N/A' }}</td>
                        <td>{{ isset($planning->week_card->hours_1) ? $planning->week_card->hours_1 : '0' }}</td>
                        <td>{{ isset($planning->week_card->hours_2) ? $planning->week_card->hours_2 : '0' }}</td>
                        <td>{{ isset($planning->week_card->hours_3) ? $planning->week_card->hours_3 : '0' }}</td>
                        <td>{{ isset($planning->week_card->hours_4) ? $planning->week_card->hours_4 : '0' }}</td>
                        <td>{{ isset($planning->week_card->hours_5) ? $planning->week_card->hours_5 : '0' }}</td>
                        <td>{{ isset($planning->week_card->hours_6) ? $planning->week_card->hours_6 : '0' }}</td>
                        <td>{{ isset($planning->week_card->hours_7) ? $planning->week_card->hours_7 : '0' }}</td>
                        <td>{{ isset($planning->week_card->total_hours) ? $planning->week_card->total_hours : '0' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11">Data not available.</td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="card" style="margin-top: 15px;">
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>From Week</th>
                    <th>To Week</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tr>
                    <td>
                        {{ $personnel->first_name }} {{ $personnel->last_name}}
                    </td>
                    <td>
                        {{ $from_week_no ?? '0' }}
                    </td>
                    <td>
                        {{ $to_week_no ?? '0' }}
                    </td>
                    <td>{{ $grand_total_hours }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="footer">
        <p>Rapport afgedrukt op: {{ \Illuminate\Support\Carbon::now() }}</p>
    </div>
</div>
</body>
</html>
