<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')

    <div class="content-wrapper">
        <div class="card">
            <h3 class="ml-3 mt-2">Employment Agency Overview</h3>
            <!-- /.card-header -->
            <div style="margin-top: 10px;" class="row ml-3">
                <div class="col-3">
                    <b>Agency:</b> {{ $agency->name }}
                </div>
                <div class="col-2">
                    <b>Week no:</b> {{ $week_no }}
                </div>
                <div class="col-2">
                    <b>Start date:</b> {{ $start_date }}
                </div>
                <div class="col-2">
                    <b>End Date:</b> {{ $end_date }}
                </div>
                <a class="col-3" href="{{url('employment-agency-overview/'.$week_state_ids.'/create/'.$agency->id.'/week-no/'.$week_no)}}">
                    <button class="btn btn-success float-right">Process</button>
                </a>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Emp ID</th>
                        <th>Staff</th>
                        <th>Mo</th>
                        <th>Tu</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                        <th>Sun</th>
                        <th>Total</th>
                        <th style="width: 150px;">Notes</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($week_cards as $week_card)
                        <tr>
                            <td>{{$week_card->personnel->id}}</td>
                            <td>{{$week_card->personnel->first_name}} {{$week_card->personnel->last_name}}</td>
                            <td>{{$week_card->hours_1}}</td>
                            <td>{{$week_card->hours_2}}</td>
                            <td>{{$week_card->hours_3}}</td>
                            <td>{{$week_card->hours_4}}</td>
                            <td>{{$week_card->hours_5}}</td>
                            <td>{{$week_card->hours_6}}</td>
                            <td>{{$week_card->hours_7}}</td>
                            <td>{{$week_card->total_hours}}</td>
                            <td>{{$week_card->comments}}</td>
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
    $(function () {
        $("#example1").DataTable({
            "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "order": [[ 0, "desc" ]],
            "responsive": true, "lengthChange": true, "autoWidth": false,
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
