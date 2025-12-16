<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Project Planning PDF</title>
    <style>
        body {
            font-family: "Arial Black", Gadget, sans-serif;
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
            margin-top: 15px !important;
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
            font-size: 9px !important;
        }

        .weekcard td {
            font-size: 8px !important;
        }
    </style>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"
    style="-webkit-font-smoothing: antialiased; width: 100% !important; -webkit-text-size-adjust: none;">
    <table width="100%" class="weekcard">
        <tr>
            <th colspan="5" class="center">Dagelijkse Projecten</th>
        </tr>

        <tr>
            <th class="center" style="width:25%">{{ date('l, F d, Y', strtotime($planning->date)) }}</th>
            <td colspan="3" class="center">{{ $formatted_functions }}</td>
            <th class="center">{{ date('W', strtotime($planning->date)) }}</th>
        </tr>

        <tr>
            <th class="center" style="width:25%">Afdeling</th>
            <th class="center" style="width:25%">Project</th>
            <th class="center" style="width:35%">Geplande personeel</th>
            <th class="center" style="width:10%">Regie</th>
            <th class="center" style="width:10%">Aangenomen</th>
        </tr>

        @foreach ($planning->staffingProjects as $project)
            @php
                // Only active employee projects for this project
                $employeeProjects = $planning->employeeProjectsActive->where('project_id', $project->id);

                $personnel_ids = $employeeProjects->pluck('employee_id')->unique()->toArray();

                $users = \App\Models\StaffingCompany\Personnel::with('employeeFunction')
                    ->whereIn('id', $personnel_ids)
                    ->get();

                $formatted_names = $users
                    ->map(function ($user) use ($employeeProjects) {
                        $employeeProject = $employeeProjects->firstWhere('employee_id', $user->id);
                        $employee_function = $employeeProject
                            ? \App\Models\StaffingCompany\EmployeeFunction::find($employeeProject->geschikt)
                            : null;
                        $employee_function_code = $employee_function ? $employee_function->code : '';
                        return $user->first_name . ' ' . $user->last_name . ' (' . $employee_function_code . ')';
                    })
                    ->implode('","', '"');

                $formatted_names = "\"$formatted_names\"";

                $department = $project->department ? $project->department->name : 'Department Not Found!';
            @endphp
            <tr>
                <td class="left">{{ $department }}</td>
                <td class="left">{{ $project->name }}</td>
                <td class="left">{{ $formatted_names }}</td>
                <td class="center">{{ $planning->countActiveEmployeeProjectWise($project->id) }}</td>
                <td class="center">{{ $planning->countInactiveEmployeeProjectsWise($project->id) }}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3"></td>
            <td class="center">{{ $planning->countActiveEmployeeProjects() }}</td>
            <td class="center">{{ $planning->countInactiveEmployeeProjects() }}</td>
        </tr>

        <tr>
            <td colspan="3"></td>
            <td colspan="2" class="center">{{ $planning->countAllEmployeeProjects() }}</td>
        </tr>
    </table>
    <div style="height:20px;">&nbsp;</div>
</body>

</html>
