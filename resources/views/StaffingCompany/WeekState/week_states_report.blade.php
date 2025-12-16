<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Weekstaten Rapport</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
        }
        .header p {
            margin: 5px 0;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .title h2 {
            margin: 0;
        }
        .title h3 {
            margin: 5px 0;
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 3px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="header">
    <img style="width: 300px;" src="{{ asset('images/easy_clean.jpg') }}" alt="Company Logo">
    <h1>Easy Clean UP B.V.</h1>
    <p>Kollenbergweg 78-1101 AV Amsterdam</p>
    <p>Tel: 020 - 691 61 15, E-mail: info@easycleanup.nl</p>
    <p>Schoonmaakonderhoud, Bouwopruiming, Opleveringsschoonmaak, Glasbewassing, Gevelreiniging, Keetschoonmaak, Bedrijfsdiensten, Bouwafval management & Containers Service</p>
</div>
<div class="title">
    <h2>Weekstaten Rapport</h2>
</div>
@if($report_type == 'all_week_states')
    <div class="title">
        <h3>Status van de weekstaten van week: {{ $week_no }} - {{ $year }}</h3>
    </div>
    <table>
        <tr>
            <th>ID.</th>
            <th>Project</th>
            <th>Verz. datum</th>
            <th>Ontv. datum</th>
            <th>Factuurdatum</th>
            <th>Status</th>
            <th>Afgehandeld</th>
        </tr>
        @foreach($week_states as $week_state)
            @if($week_state->projects->isEmpty())
                <tr>
                    <td>{{$week_state->id}}</td>
                    <td style="background-color: #ff8200; color: white">No projects available</td>
                    <td>{{$week_state->delay_date}}</td>
                    <td>{{$week_state->receive_date}}</td>
                    <td>{{$week_state->invoice_date}}</td>
                    <td>{{$week_state->status}}</td>
                    <td>@if($week_state->approved == 1)
                            Close
                        @else
                            Open
                        @endif</td>
                </tr>
            @else
                @foreach ($week_state->projects as $project)
                    <tr>
                        <td>{{$week_state->id}}</td>
                        <td>{{ $project->address }} {{ $project->name }}</td>
                        <td>{{$week_state->delay_date}}</td>
                        <td>{{$week_state->receive_date}}</td>
                        <td>{{$week_state->invoice_date}}</td>
                        <td>{{$week_state->status}}</td>
                        <td>@if($week_state->approved == 1)
                                Close
                            @else
                                Open
                            @endif</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </table>

@elseif($report_type == 'per_week')
    @foreach($week_nos as $week_no)
        @php
            $year_week_no = $year.$week_no;
        @endphp

        @if($week_states->where('week_no', $year_week_no)->count() > 0)
            <h3>Status van de weekstaten van week: {{ $week_no }} - {{ $year }}</h3>
            <table>
                <tr>
                    <th>ID.</th>
                    <th>Project</th>
                    <th>Verz. datum</th>
                    <th>Ontv. datum</th>
                    <th>Factuurdatum</th>
                    <th>Status</th>
                    <th>Afgehandeld</th>
                </tr>
                @foreach($week_states as $week_state)
                    @if($week_state->week_no == $year_week_no)
                        @if($week_state->projects->isEmpty())
                            <tr>
                                <td>{{$week_state->id}}</td>
                                <td style="background-color: #ff8200; color: white">No projects available</td>
                                <td>{{$week_state->delay_date}}</td>
                                <td>{{$week_state->receive_date}}</td>
                                <td>{{$week_state->invoice_date}}</td>
                                <td>{{$week_state->status}}</td>
                                <td>@if($week_state->approved == 1)
                                        Close
                                    @else
                                        Open
                                    @endif</td>
                            </tr>
                        @else
                            @foreach ($week_state->projects as $project)
                                <tr>
                                    <td>{{$week_state->id}}</td>
                                    <td>{{ $project->address }} {{ $project->name }}</td>
                                    <td>{{$week_state->delay_date}}</td>
                                    <td>{{$week_state->receive_date}}</td>
                                    <td>{{$week_state->invoice_date}}</td>
                                    <td>{{$week_state->status}}</td>
                                    <td>@if($week_state->approved == 1)
                                            Close
                                        @else
                                            Open
                                        @endif</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
            </table>
        @endif
    @endforeach
@elseif($report_type == 'per_project')
    @foreach($projects as $project)
        @if($project->weekStates->count() > 0)
            <h3>Status van de weekstaten van project: {{ $project->address }} {{ $project->name }}</h3>
            <table>
                <tr>
                    <th>ID.</th>
                    <th>Week</th>
                    <th>Verz. datum</th>
                    <th>Ontv. datum</th>
                    <th>Factuurdatum</th>
                    <th>Status</th>
                    <th>Afgehandeld</th>
                </tr>
                @if ($project && $project->weekStates)
                    @foreach(_getProjectOnlyOpenWeekStates($project) as $week_state)
                        <tr>
                            <td>{{$week_state->id}}</td>
                            <td>{{$week_state->week_no}}</td>
                            <td>{{$week_state->delay_date}}</td>
                            <td>{{$week_state->receive_date}}</td>
                            <td>{{$week_state->invoice_date}}</td>
                            <td>{{$week_state->status}}</td>
                            <td>@if($week_state->approved == 1)
                                    Close
                                @else
                                    Open
                                @endif</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No week states found for this project.</td>
                    </tr>
                @endif

            </table>
        @endif
    @endforeach
@endif
</body>
</html>
