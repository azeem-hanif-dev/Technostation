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
                <h3>Activity Logs</h3>
            </div>

            <div class="card-body">
                <ul class="nav nav-tabs" id="activityTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="activity-tab" data-toggle="tab" href="#activity" role="tab">User Activities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="error-tab" data-toggle="tab" href="#error" role="tab">Error Logs</a>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="activityTabsContent">
                    {{-- ✅ TAB 1: Login / Logout / CRUD --}}
                    <div class="tab-pane fade show active" id="activity" role="tabpanel">
                      
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Model</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($allActivityLogs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->user_name }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->model }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- ✅ TAB 2: Errors --}}
                    <div class="tab-pane fade" id="error" role="tabpanel">
                       
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Description</th>
                                <th>URL</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($errorLogs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->user_name }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->url }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@include('StaffingCompany.partials._footer')

<!-- Page specific script -->
<script>
    $(function () {
        $("#example1").DataTable({
            "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "order": [[ 0, "desc" ]],
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $("#example2").DataTable({
            "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "order": [[ 0, "desc" ]],
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
</body>
</html>
