<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')
        <div class="content-wrapper">
            <div class="card">
                <div style="margin-top: 10px;" class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <form method="POST" action="{{ route('week-state.search') }}">
                                @csrf
                                <div class="row">
                                    <label
                                        class="ml-5 mr-2 mt-2">{{ __('Staffing_Company/Week_State/w_index.year') }}</label>
                                    <select style="width: 100px;" class="form-control" name="year">
                                        @foreach ($years as $y)
                                            <option value="{{ $y }}"
                                                {{ isset($year) && $year == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label class="ml-2 mr-2 mt-2">{{ __('Staffing_Company/common.week_no') }}.</label>
                                    <select style="width: 70px;" class="form-control" name="week_no">
                                        @for ($i = 1; $i <= 52; $i++)
                                            <option value="{{ $i }}"
                                                {{ isset($week_no) && $week_no == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>

                                    <input style="width: 20px;" type="checkbox" class="form-control ml-2" name="status"
                                        {{ isset($status) && $status == 1 ? 'checked' : '' }}>
                                    <label
                                        class="mt-2 ml-1">{{ __('Staffing_Company/Week_State/w_index.only_open') }}</label>

                                    <button type="submit" class="btn btn-primary btn-sm ml-2">
                                        {{ __('Staffing_Company/Week_State/w_index.search') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning" data-toggle="modal"
                                data-target="#exampleModal">{{ __('Staffing_Company/Week_State/w_index.report') }}</button>
                            <a href="{{ route('week-state.create') }}">
                                <button
                                    class="btn btn-success float-right">{{ __('Staffing_Company/Week_State/w_index.new_week') }}</button>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>{{ __('Staffing_Company/common.week_no') }}.</th>
                                <th>{{ __('Staffing_Company/common.project') }}</th>
                                <th>{{ __('Staffing_Company/Week_State/w_index.delay_date') }}</th>
                                <th>{{ __('Staffing_Company/Week_State/w_index.received_date') }}</th>
                                <th>{{ __('Staffing_Company/Week_State/w_index.invoice_date') }}</th>
                                <th>{{ __('Staffing_Company/common.status') }}</th>
                                <th>{{ __('Staffing_Company/common.done') }}</th>
                                <th>{{ __('Staffing_Company/common.option') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($week_states as $week_state)
                                @if ($week_state->projects->isEmpty())
                                    <tr>
                                        <td>{{ $week_state->id }}</td>
                                        <td>{{ $week_state->week_no }}</td>
                                        <td style="background-color: #ff8200; color: white">No projects available</td>
                                        <td>{{ $week_state->delay_date }}</td>
                                        <td>{{ $week_state->receive_date }}</td>
                                        <td>{{ $week_state->invoice_date }}</td>
                                        <td>{{ $week_state->status }}</td>
                                        <td>
                                            @if ($week_state->approved == 1)
                                                <button
                                                    class="btn btn-sm btn-success">{{ __('Staffing_Company/common.close') }}</button>
                                            @else
                                                <button
                                                    class="btn btn-sm btn-danger">{{ __('Staffing_Company/common.open') }}</button>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="row">
                                                <a href="{{ route('week-state.view', $week_state->id) }}">
                                                    <i style="color: green;" class="col fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('week-state.edit', $week_state->id) }}">
                                                    <i style="color: green;" class="col far fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('week-state.destroy', $week_state->id) }}"
                                                    id="delete-form-{{ $week_state->id }}">
                                                    @csrf
                                                    @method('Delete')
                                                    <i style="color: red" type="button"
                                                        onclick="confirmDelete({{ $week_state->id }})"
                                                        class="col fas fa-trash"></i>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($week_state->projects as $project)
                                        <tr>
                                            <td>{{ $week_state->id }}</td>
                                            <td>{{ $week_state->week_no }}</td>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $week_state->delay_date }}</td>
                                            <td>{{ $week_state->receive_date }}</td>
                                            <td>{{ $week_state->invoice_date }}</td>
                                            <td>{{ $week_state->status }}</td>
                                            <td>
                                                @if ($week_state->approved == 1)
                                                    <button
                                                        class="btn btn-sm btn-success">{{ __('Staffing_Company/common.close') }}</button>
                                                @else
                                                    <button
                                                        class="btn btn-sm btn-danger">{{ __('Staffing_Company/common.open') }}</button>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <a
                                                        href="{{ route('week-state.view', $week_state->id) }}?project_id={{ $project->id }}">
                                                        <i style="color: green;" class="col fa fa-eye"></i>
                                                    </a>
                                                    {{-- <a href="{{route('week-state.edit', $week_state->id)}}?project_id={{ $project->id }}">
                                                        <i style="color: green;" onclick="@php Session::flash('project_id',$project->id) @endphp" class="col far fa-edit"></i>
                                                    </a> --}}

                                                    <form action="{{ route('week-state.edit', $week_state->id) }}"
                                                        method="Post" id="editForm-{{ $week_state->id }}">
                                                        @csrf
                                                        <input type="hidden" name="project_id"
                                                            value="{{ $project->id }}">
                                                    </form>

                                                    <a href="{{ route('week-state.edit', $week_state->id) }}#{{ $project->id }}"
                                                        onclick="handleEditClick(event, {{ $week_state->id }}, {{ $project->id }});">

                                                        <i style="color: green;" class="col far fa-edit"></i>
                                                    </a>

                                                    <form method="POST"
                                                        action="{{ route('week-state.destroy', $week_state->id) }}?project_id={{ $project->id }}"
                                                        id="delete-form-{{ $week_state->id }}">
                                                        @csrf
                                                        @method('Delete')
                                                        <i style="color: red" type="button"
                                                            onclick="confirmDelete({{ $week_state->id }})"
                                                            class="col fas fa-trash"></i>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2>RAPPORT</h2>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{ route('week-states-report') }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="all_week_states"
                                                name="status" value="all" onclick="toggleEndWeek(false)" required>
                                            <label class="form-check-label" for="all_week_states">All week
                                                states</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="only_open_week_states"
                                                name="status" value="open" onclick="toggleEndWeek(true)" required>
                                            <label class="form-check-label" for="only_open_week_states">Only open week
                                                states</label>
                                        </div>
                                    </div>

                                    <div id="all_week_states_option" style="display: flex"
                                        class="form-row align-items-center">
                                        <div class="col-auto">
                                            <label
                                                for="year">{{ __('Staffing_Company/Week_State/w_index.year') }}</label>
                                            <select class="form-control" id="year" name="year" required>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label for="week_no">{{ __('Staffing_Company/common.week_no') }}</label>
                                            <select class="form-control" id="week_no" name="week_no" required>
                                                @for ($i = 1; $i <= 52; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row align-items-center mt-3" id="end_week_row"
                                        style="display: none;">
                                        <div class="col-auto">
                                            <label
                                                for="year">{{ __('Staffing_Company/Week_State/w_index.year') }}</label>
                                            <select class="form-control" id="year" name="open_year" required>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label for="week_no">{{ __('Staffing_Company/common.week_no') }}</label>
                                            <select class="form-control" id="week_no" name="open_start_week_no"
                                                required>
                                                @for ($i = 1; $i <= 52; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label for="end_week_no">End Week No.</label>
                                            <select class="form-control" id="end_week_no" name="open_end_week_no">
                                                @for ($i = 1; $i <= 52; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group mt-3" id="report_type_options" style="display: none;">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="week_wise"
                                                name="report_type" value="week">
                                            <label class="form-check-label" for="week_wise">Per Week</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="project_wise"
                                                name="report_type" value="project">
                                            <label class="form-check-label" for="project_wise">Per Project</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Report</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        function confirmDelete(itemId) {
            if (confirm('Are you sure you want to delete this?')) {
                document.getElementById('delete-form-' + itemId).submit();
            }
        }

        function toggleEndWeek(show) {
            const allWeekStates = document.getElementById('all_week_states_option');
            const endWeekRow = document.getElementById('end_week_row');
            const reportTypeOptions = document.getElementById('report_type_options');
            if (show) {
                allWeekStates.style.display = 'none';
                endWeekRow.style.display = 'flex';
                reportTypeOptions.style.display = 'block';
            } else {
                allWeekStates.style.display = 'flex';
                endWeekRow.style.display = 'none';
                reportTypeOptions.style.display = 'none';
            }
        }

        // If Ctrl or Command key is pressed, let the default behavior (open in new tab) happen.

        function handleEditClick(event, id, projectId) {
            if (event.ctrlKey || event.metaKey || event.button === 1) {
                // Append project_id to the fragment (#) part of the URL
                let url = `{{ route('week-state.edit', ':id') }}`.replace(':id', id) + `#${projectId}`;
                window.open(url, '_blank'); // Open in new tab
                event.preventDefault();
                return;
            }

            // Otherwise, submit the form normally
            event.preventDefault();
            document.getElementById('editForm-' + id).submit();
        }
    </script>
</body>

</html>
