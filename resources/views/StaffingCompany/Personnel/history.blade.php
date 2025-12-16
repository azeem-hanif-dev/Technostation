<!DOCTYPE html>
<html lang="en">
<style>
    body {
        background-color: #0f2c35;
        color: #ffffff;
        font-family: Arial, sans-serif;
    }

    .header,
    .footer {
        padding: 10px 0;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table th,
    table td {
        padding: 10px;
        border: 1px solid #3a3a3a;
        text-align: center;
    }

    .total-section {
        text-align: right;
    }
</style>
@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('StaffingCompany.partials._navBar')

        @include('StaffingCompany.partials._sideBar')
@php
    $selected_from_week = $selected_from_week ?? now()->weekOfYear;
    $selected_from_year = $selected_from_year ?? now()->year;
    $selected_to_week = $selected_to_week ?? now()->weekOfYear;
    $selected_to_year = $selected_to_year ?? now()->year;
@endphp

        <div class="content-wrapper">
            <div class="ml-2 mr-2">
                <div class="header">
                    <h1>{{ __('Staffing_Company/staff/workhistory.name') }}: {{ $personnel->first_name }}
                        {{ $personnel->last_name }}</h1>
                    @php
                        $from_week_no = $from_week_no ?? 0;
                        $to_week_no = $to_week_no ?? 0;
                    @endphp
                    <a href="{{ url('personnel-work-history-pdf/' . $personnel->id . '/' . $from_week_no . '/' . $to_week_no) }}">
                        <button class="btn btn-xs btn-success float-right">PDF</button>
                    </a>
                </div>
                <div class="col-md-9">
                    <form method="POST" action="{{ route('employee-search-work-history', $personnel) }}">
                        @csrf
                        <div class="row">

                            {{-- From Week --}}
                            <label class="ml-5 mr-2 mt-2">
                                {{ __('Staffing_Company/staff/workhistory.from_week_no') }}:
                            </label>
                            <select name="from_week" class="form-control" style="width: 70px; margin-right: 5px;">
                                @foreach (_weekNos() as $week)
                                    <option value="{{ $week }}"
                                        {{ $week == $selected_from_week ? 'selected' : '' }}>
                                        {{ $week }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="from_year" class="form-control" style="width: 100px;">
                                @foreach (_getPastYears(10) as $year)
                                    <option value="{{ $year }}"
                                        {{ $year == $selected_from_year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- To Week --}}
                            <label class="ml-5 mr-2 mt-2">
                                {{ __('Staffing_Company/staff/workhistory.to_week_no') }}:
                            </label>
                            <select name="to_week" class="form-control" style="width: 70px; margin-right: 5px;">
                                @foreach (_weekNos() as $week)
                                    <option value="{{ $week }}"
                                        {{ $week == $selected_to_week ? 'selected' : '' }}>
                                        {{ $week }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="to_year" class="form-control" style="width: 100px;">
                                @foreach (_getPastYears(10) as $year)
                                    <option value="{{ $year }}"
                                        {{ $year == $selected_to_year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Submit Button --}}
                            <button type="submit" class="btn btn-primary btn-sm ml-2">
                                {{ __('Staffing_Company/staff/workhistory.search') }}
                            </button>
                        </div>
                    </form>

                </div>
                <div class="table-section">
                    <h3 style="margin: 10px;">{{ __('Staffing_Company/staff/workhistory.work_history') }}</h3>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Staffing_Company/staff/workhistory.week_number') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.project_name') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.employment_agency') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.employment_function') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.mon') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.tue') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.wed') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.thu') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.fri') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.sat') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.sun') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grand_total_hours = 0;
                            @endphp
                            @if (isset($plannings) && count($plannings) > 0)
                                @foreach ($plannings as $planning)
                                    @php
                                        $totalHours = isset($planning->week_card->total_hours)
                                            ? $planning->week_card->total_hours
                                            : 0;
                                        $grand_total_hours += $totalHours;
                                    @endphp
                                    <tr>
                                        <td>{{ isset($planning->week_no) ? $planning->week_no : 'N/A' }}</td>
                                        <td>{{ isset($planning->project_name) ? $planning->project_name : 'N/A' }}</td>
                                        <td>{{ isset($personnel->agency->name) ? $personnel->agency->name : 'N/A' }}
                                        </td>
                                        <td>{{ isset($planning->employeeFunction->name) ? $planning->employeeFunction->name : 'N/A' }}
                                        </td>
                                        <td>{{ isset($planning->week_card->hours_1) ? $planning->week_card->hours_1 : '0' }}
                                        </td>
                                        <td>{{ isset($planning->week_card->hours_2) ? $planning->week_card->hours_2 : '0' }}
                                        </td>
                                        <td>{{ isset($planning->week_card->hours_3) ? $planning->week_card->hours_3 : '0' }}
                                        </td>
                                        <td>{{ isset($planning->week_card->hours_4) ? $planning->week_card->hours_4 : '0' }}
                                        </td>
                                        <td>{{ isset($planning->week_card->hours_5) ? $planning->week_card->hours_5 : '0' }}
                                        </td>
                                        <td>{{ isset($planning->week_card->hours_6) ? $planning->week_card->hours_6 : '0' }}
                                        </td>
                                        <td>{{ isset($planning->week_card->hours_7) ? $planning->week_card->hours_7 : '0' }}
                                        </td>
                                        <td>{{ isset($planning->week_card->total_hours) ? $planning->week_card->total_hours : '0' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11">
                                        {{ __('Staffing_Company/staff/workhistory.data_not_available') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card" style="margin-top: 15px;">
                    <table style="margin: 10px; width: 98%">
                        <thead>
                            <tr>
                                <th>{{ __('Staffing_Company/staff/workhistory.name') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.from_week') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.to_week') }}</th>
                                <th>{{ __('Staffing_Company/staff/workhistory.total') }}</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>
                                {{ $personnel->first_name }} {{ $personnel->last_name }}
                            </td>
                            <td>
                                {{ $from_week_no }}
                            </td>
                            <td>
                                {{ $to_week_no }}
                            </td>
                            <td>{{ $grand_total_hours }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @include('StaffingCompany.partials._footer')
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "lengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                "order": [
                    [0, "desc"]
                ],
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "ordering": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</html>
