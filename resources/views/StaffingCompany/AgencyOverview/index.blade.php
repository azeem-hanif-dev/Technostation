<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')

        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div class="card">
                <h3 class="ml-3 mt-2"> {{ __('Staffing_Company/Agency/a_index.employment_agency_overview') }}
                </h3>
                <div style="margin-top: 10px;" class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <form method="POST" action="{{ route('search-employee-agency-overview') }}">
                                @csrf
                                <div class="row">
                                    <label
                                        class="ml-5 mr-2 mt-2">{{ __('Staffing_Company/Week_State/w_index.year') }}</label>
                                    <select style="width: 100px;" class="form-control" name="year">
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}"
                                                {{ isset($selected_year) && $selected_year == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="ml-2 mr-2 mt-2">{{ __('Staffing_Company/common.week_no') }}.</label>
                                    <select style="width: 70px;" class="form-control" name="week_no">
                                        @for ($i = 1; $i <= 52; $i++)
                                            <option value="{{ $i }}"
                                                {{ isset($selected_week_no) && $selected_week_no == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <button type="submit"
                                        class="btn btn-primary btn-sm ml-2">{{ __('Staffing_Company/Week_State/w_index.search') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Staffing_Company/common.name') }}</th>
                                <th>Worked Hours</th>
                                <th>Cost</th>
                                <th>Dispatch Date</th>
                                <th>Receive Date</th>
                                <th>Invoice Date</th>
                                <th>Status</th>
                                <th>Done</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--                    @foreach ($agency_overviews as $agency) --}}
                            {{--                        <tr> --}}
                            {{--                            <td>{{ $agency->name ?? '' }}</td> --}}
                            {{--                            @php --}}
                            {{--                                $detail = $agency_overviews_details->firstWhere('employ_agency_id', $agency->id); --}}
                            {{--                            @endphp --}}

                            {{--                            @if ($detail) --}}
                            {{--                                <td>{{ $detail->worked_hours }}</td> --}}
                            {{--                                <td>{{ $detail->cost }}</td> --}}
                            {{--                                <td>{{ $agency->total_hours }}</td> --}}
                            {{--                                <td>{{ $agency->total_cost }}</td> --}}
                            {{--                                <td>{{ $detail->dispatch_date }}</td> --}}
                            {{--                                <td>{{ $detail->receive_date }}</td> --}}
                            {{--                                <td>{{ $detail->invoice_date }}</td> --}}
                            {{--                                <td>{{ $detail->status ? 'Open' : 'Closed' }}</td> --}}
                            {{--                                <td>{{ $detail->completed ? 'Open' : 'Closed' }}</td> --}}
                            {{--                            @else --}}
                            {{--                                <td></td> --}}
                            {{--                                <td></td> --}}
                            {{--                                <td></td> --}}
                            {{--                                <td></td> --}}
                            {{--                                <td></td> --}}
                            {{--                                <td></td> --}}
                            {{--                                <td></td> --}}
                            {{--                            @endif --}}
                            {{--                            @php --}}
                            {{--                                $agency = App\Models\EmployAgency::find($agency->id); --}}
                            {{--                                $staff_ids = $agency->personnels()->pluck('id'); --}}
                            {{--                                $week_state_ids = App\Models\StaffingCompany\SfWeekCard::whereIn('personnel_id', $staff_ids)->where('week_no', $week_no)->pluck('week_state_id')->unique()->toArray(); --}}
                            {{--                                $week_state_ids = implode(', ', $week_state_ids); --}}
                            {{--                            @endphp --}}
                            {{--                            <td> --}}
                            {{--                                <div class="row"> --}}
                            {{--                                    <a href="{{ url('/employment-agency/'.$agency->id.'/week-state/'.$week_no) }}"> --}}
                            {{--                                        <i style="color: green;" class="col fa fa-search"></i> --}}
                            {{--                                    </a> --}}
                            {{--                                    <a href="{{url('employment-agency-overview/'.$week_state_ids.'/create/'.$agency->id.'/week-no/'.$week_no)}}"> --}}
                            {{--                                        <i style="color: green;" class="col fa fa-edit"></i> --}}
                            {{--                                    </a> --}}
                            {{--                                    <a href="{{ route('week-state-pdf', ['week_state_ids' => $week_state_ids, 'week_no' => $week_no, 'agency_id' => $agency->id]) }}"> --}}
                            {{--                                        <i style="color: red;" class="col fa fa-file-pdf"></i> --}}
                            {{--                                    </a> --}}
                            {{--                                    <a href="{{route('week-state-email', ['week_state_ids' => $week_state_ids, 'week_no' => $week_no, 'agency_overview_id' => $agency->id]) }}"> --}}
                            {{--                                        <i style="color: green;" class="col fa fa-envelope"></i> --}}
                            {{--                                    </a> --}}
                            {{--                                </div></td> --}}
                            {{--                        </tr> --}}
                            {{--                    @endforeach --}}
                            @foreach ($agencies as $agency)
                                <tr>
                                    <td>{{ $agency->name ?? '' }}</td>
                                    @php
                                        $detail = $agency_overviews_details
                                            ->where('employ_agency_id', $agency->id)
                                            ->where('week_no', $week_no)
                                            ->first();
                                    @endphp
                                    @if ($agency)
                                        <td>{{ $agency->total_hours }}</td>
                                        <td>{{ $agency->total_cost }}</td>
                                        <td>{{ $detail->dispatch_date ?? '' }}</td>
                                        <td>{{ $detail->receive_date ?? '' }}</td>
                                        <td>{{ $detail->invoice_date ?? '' }}</td>
                                        <td>{{ $detail->status ?? '' ? 'Open' : 'Closed' }}</td>
                                        <td>{{ $detail->completed ?? '' ? 'Open' : 'Closed' }}</td>
                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                                    @php
                                        $employ_agency = App\Models\EmployAgency::find($agency->id);

                                        $staff_ids = $employ_agency->personnels()->pluck('id');

                                        $week_state_ids = App\Models\StaffingCompany\SfWeekCard::whereIn(
                                            'personnel_id',
                                            $staff_ids,
                                        )
                                            ->where('week_no', $week_no)
                                            ->pluck('week_state_id')
                                            ->unique()
                                            ->toArray();
                                        $week_state_ids = implode(', ', $week_state_ids);

                                    @endphp
                                    <td>
                                        <div class="row">
                                            <a
                                                href="{{ url('/employment-agency/' . $employ_agency->id . '/week-state/' . $week_no) }}">
                                                <i style="color: green;" class="col fa fa-search"></i>
                                            </a>
                                            <a
                                                href="{{ url('employment-agency-overview/' . $week_state_ids . '/create/' . $employ_agency->id . '/week-no/' . $week_no) }}">
                                                <i style="color: green;" class="col fa fa-edit"></i>
                                            </a>
                                            <a
                                                href="{{ route('week-state-pdf', ['week_state_ids' => $week_state_ids, 'week_no' => $week_no, 'agency_id' => $employ_agency->id]) }}">
                                                <i style="color: red;" class="col fa fa-file-pdf"></i>
                                            </a>
                                            <a
                                                href="{{ route('week-state-email', ['week_state_ids' => $week_state_ids, 'week_no' => $week_no, 'agency_overview_id' => $employ_agency->id]) }}">
                                                <i style="color: green;" class="col fa fa-envelope"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
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
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        function confirmDelete(itemId) {
            if (confirm('Are you sure you want to delete this?')) {
                document.getElementById('delete-form-' + itemId).submit();
            }
        }
    </script>
</body>

</html>
