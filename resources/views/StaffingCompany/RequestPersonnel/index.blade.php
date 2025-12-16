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
                    <h3>{{ __('Staffing_Company/Request_Staff/r_index.request_staff') }}</h3>
                    <a href="{{ route('request_personnels.create') }}">
                        <button
                            class="btn btn-success float-right">{{ __('Staffing_Company/Request_Staff/r_index.new') }}</button>
                    </a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>{{ __('Staffing_Company/Request_Staff/r_index.application_no') }}</th>
                                <th>{{ __('Staffing_Company/common.project') }}</th>
                                <th>{{ __('Staffing_Company/Request_Staff/r_index.submission_date') }}</th>
                                <th>{{ __('Staffing_Company/Request_Staff/crud.starting_date_time') }}</th>
                                <th>{{ __('Staffing_Company/Request_Staff/r_index.no_of_person') }}</th>
                                <th>{{ __('Staffing_Company/Request_Staff/r_index.how_many_days') }}</th>
                                <th>{{ __('Staffing_Company/Request_Staff/r_index.activities') }}</th>
                                <th>{{ __('Staffing_Company/common.done') }}</th>
                                <th>Status</th>
                                <th>{{ __('Staffing_Company/common.action') }}</th>
                                 {{-- ✅ New column for Approve button or badge --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($request_personnels as $request_personnel)
                                <tr>
                                    <td>{{ $request_personnel->id }}</td>
                                    <td>{{ $request_personnel->application_no }}</td>
                                    <td>{{ $request_personnel->project->name ?? '' }}</td>
                                    <td>{{ $request_personnel->application_date_time }}</td>
                                    <td>{{ $request_personnel->starting_date_time }}</td>
                                    <td>{{ $request_personnel->no_of_people }}</td>
                                    <td>{{ $request_personnel->days }}</td>
                                    <td>
                                        @foreach ($request_personnel->employeeFunction as $function)
                                            {{ $function->name }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    <td style="color: {{ $request_personnel->complete ? 'green' : 'red' }};">
                                        {{ $request_personnel->complete ? 'Done' : 'Open' }}
                                    </td>
                                    <td>
                                        @if ($request_personnel->status == 0)
                                            <form method="POST"
                                                action="{{ route('request_personnels.approve', $request_personnel->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">Pending</button>
                                            </form>
                                        @elseif ($request_personnel->status == 1)
                                            <span style='font-size:30px;'>&#10003;</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <a
                                                    href="{{ route('request-personnel.view', $request_personnel->id) }}">
                                                    <i class="fa fa-eye text-success"></i>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a
                                                    href="{{ route('request_personnels.edit', $request_personnel->id) }}">
                                                    <i class="far fa-edit text-primary"></i>
                                                </a>
                                            </div>
                                            <div class="col-6 mt-2">
                                                <a href="{{ route('request-personnel-pdf', $request_personnel->id) }}">
                                                    <i class="far fa-file-pdf text-danger"></i>
                                                </a>
                                            </div>
                                            <div class="col-6 mt-2">
                                                <form method="POST"
                                                    action="{{ route('request_personnels.destroy', $request_personnel->id) }}"
                                                    id="delete-form-{{ $request_personnel->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <i class="fas fa-trash text-danger" type="button"
                                                        onclick="confirmDelete({{ $request_personnel->id }})"></i>
                                                </form>
                                            </div>
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
