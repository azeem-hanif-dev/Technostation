<?php
function convert_day($days)
{
    if ($days == 'Monday') {
        return 'Maandag';
    }
    if ($days == 'Tuesday') {
        return 'Dinsdag';
    }
    if ($days == 'Wednesday') {
        return 'Woensdag';
    }
    if ($days == 'Thursday') {
        return 'Donderdag';
    }
    if ($days == 'Friday') {
        return 'Vrijdag';
    }
    if ($days == 'Saturday') {
        return 'Zaterdag';
    }
    if ($days == 'Sunday') {
        return 'Zondag';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <style>
        body {
            font-family: "Arial Black", Gadget, sans-serif;
        }

        .center {
            text-align: center;
        }

        h4 {
            margin-top: 50px;
        }

        li {
            font-size: 16px;
            font-weight: normal;
            padding: 6px;
        }

        table,
        td {
            border: 1px solid #000;
            text-shadow: none;
            text-align: left;
            font-size: 13px;
            font-weight: normal;
            padding: 4px;
            line-height: 24px !important;
        }

        table,
        th {
            border: 1px solid #000;
            box-shadow: none;
            text-align: left;
            font-size: 13px;
            font-weight: bold;
            padding: 3px;
        }

        /* #firstTable, td {
            border: none;
         }
         #firstTable, th {
            border: none;
         } */
        table {
            /* border-collapse: collapse;*/
        }

        .strong {
            font-weight: bold;
        }

        .table td {
            text-align: center !important;
            line-height: 20px !important;
        }

        .footer {
            position: fixed;
            bottom: 0px;
        }

        .week-number {
            /* border: 1px solid black; */
            float: right !important;
            /* padding: 5px; */
            /* display: inline-block; */
        }

        img.logo {
            width: 200px;
            height: auto;
            xmax-height: 80px;
            object-fit: contain;
        }
    </style>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"
    style="-webkit-font-smoothing: antialiased; width: 100% !important;  -webkit-text-size-adjust: none;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" id="firstTable" style="border: none;">
        <tr>
            <td align="center" style="border: none;">
                <img src="{{ asset('images/easy_clean.jpg') }}" class="logo" style="vertical-align:top;" />

            </td>
        </tr>
        <tr>
            <td style="line-height:4px; vertical-align:bottom; border: none;" align="center">
                <h2 style="display: inline-block; text-align: center; margin: 0 40%; white-space: nowrap;">Aanvraag
                    Personeel</h2>
                <h3 class="week-number"><strong>WEEK
                        {{ date('W - Y', strtotime($requested_personnel->starting_date_time)) }}</strong></h3>
            </td>
        </tr>
    </table>


    <table width="100%" cellpadding="3" cellspacing="0" style="font-size:12px; border: none;">

        <tr>
            <th align="Left">Project Naam</th>
            <td colspan="3" style="font-size:14px; font-weight:bold; border: 1px solid #000";>
                <?= @$project->name ?>
            </td>
        </tr>
        <tr>
            <th align="Left" style="width:20% !important">Afdeling</th>
            <td style="width:30% !important; border: 1px solid #000";>
                <?= @$project->department->name ?>
            </td>
            <th align="Left" style="width:20% !important">Besteld door</th>
            <td style="width:30% !important; border: 1px solid #000";><?= @$requested_personnel->adopted_by ?></td>

        </tr>

        <tr>
            <th align="Left">Project Adres</th>
            <td><?= @$project->address ?></td>
            <th align="Left"></th>
            <td></td>

        </tr>
        <!--tr>
            <th  align="Left" ></th>
            <td ></td>
              <th align="Left"></th>
              <td ></td>
        </tr-->

        <tr>
            <th align="Left">Uitvoerder</th>
            <td align="Left">
                <?= @$project->projectPerformer ? $project->projectPerformer->first_name . ' ' . $project->projectPerformer->last_name : 'Not Provided' ?>
            </td>

            <th align="Left">Aangenomen door</th>
            <td align="Left"><?= @$requested_personnel->adopted_by ?></td>


        </tr>
        <tr>
            {{-- <th  align="Left" >Telefoonnummer</th>
            <td  align="Left">@$project->id</td> --}}
            <th align="Left">Aanvraagdatum</th>
            <?php $day = date('l', strtotime(@$requested_personnel->application_date_time)); ?>
            <td align="Left" colspan="3">
                <?= date('d-m-Y', strtotime(@$requested_personnel->application_date_time)) ?> / <?= convert_day($day) ?>
            </td>


        </tr>
        <tr>
            <th align="Left">Aanvraagnummer</th>
            <td><?= @$requested_personnel->application_no ?></td>
            <th align="Left">Tijd</th>
            <td><?= date('H:i', strtotime(@$requested_personnel->application_date_time)) ?></td>
            /tr>
    </table>

    <table width="100%" cellpadding="3" cellspacing="0" border="0">

        <tr style="line-height:4px; vertical-align:bottom;" align="left">
            <td>
                <h3>Opdracht:</h3>
            </td>
        </tr>

    </table>


    <table width="100%" cellpadding="3" cellspacing="0">


        <tr>
            <th align="Left" style="width:20% !important">Begindatum</th>

            <?php $day2 = date('l', strtotime(@$requested_personnel->starting_date_time)); ?>
            <td style="width:30% !important"><?= date('d-m-Y', strtotime(@$requested_personnel->starting_date_time)) ?>
                / <?= convert_day($day2) ?></td>
            <th style="width:20% !important" align="Left">Begintijd</th>
            <td style="width:30% !important"><?= date('H:i', strtotime(@$requested_personnel->starting_date_time)) ?>
            </td>
        </tr>
        <?php
        $Melden = $requested_personnel->report_to; ?>
        <tr>
            <th align="Left">Aantal Mensen</th>
            <td><?= @$requested_personnel->no_of_people ?></td>
            <th align="Left">Hoeveel Dagen</th>
            <td><?= @$requested_personnel->days ?></td>
        </tr>

        <tr>
            <th align="Left">Melden Bij</th>
            <td>{{ $supervisor ? $supervisor->first_name . ' ' . $supervisor->last_name : 'Not Provided' }}</td>

            <th align="Left">Telefoonnummer</th>
            <td>{{ $supervisor && $supervisor->mobile ? _formatPhoneNumber($supervisor->mobile) : 'Not Provided' }}</td>
        </tr>
        <tr>
            <th align="Left">Werkzaamheden</th>
            {{-- <td  colspan="3" >{{ optional($requested_personnel->employeeFunction)->name ?? 'N/A' }} --}}
            <td  colspan="3">
                @foreach ($requested_personnel->employeeFunction as $function)
                    {{ $function->name }}@if (!$loop->last)
                        ,
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th align="Left">Benodigheden</th>
            <td colspan="3"><?= @$requested_personnel->requirements ?></td>
        </tr>

        <tr>
            <th align="Left">Opmerkingen</th>
            <td colspan="3"><?= @$requested_personnel->comments ?></td>
        </tr>

        {{-- <tr>
            <th  align="Left" >Project notities</th>
            <td colspan="3" > //@$project->notes </td>
         </tr> --}}

    </table>

    <div style="width:100%">

        <div style="clear:both"></div>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="footer">
            <tr>
                <td align="center" style=" font-size:11px;">
                    Easy Clean Up BV | Kollenbergweg 78, 1101 AV Amsterdam | Tel.: 020-6916115
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
