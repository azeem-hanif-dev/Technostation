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
                <h3>Personnel</h3>
                <a href="{{route('personnels.create')}}">
                    <button class="btn btn-success float-right">Add Personnel</button>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>BSN Number</th>
                        <th>Employment Agency</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($personnels as $personnel)
                        <tr>
                            <td>{{$personnel->id}}</td>
                            <td>{{$personnel->first_name ?? ''}}</td>
                            <td>{{$personnel->last_name ?? ''}}</td>
                            <td>{{$personnel->social_security_number ?? ''}}</td>
                            <td>{{$personnel->agency->name ?? ''}}</td>
                            <td>{{$personnel->active ?? ''}}</td>
                            <td><div class="row">
                                    <a href="#">
                                        <i style="color: green;" class="col fa fa-eye"></i>
                                    </a>
                                    <a href="{{route('personnels.edit', $personnel->id)}}">
                                        <i style="color: green;" class="col far fa-edit"></i>
                                    </a>
{{--                                    <a data-toggle="modal" data-target="#modal-danger" class="btn-link" style="text-decoration: underline; color: blue; cursor: pointer;">--}}
{{--                                        <i style="color: red" class="col fas fa-trash"></i>--}}
{{--                                    </a>--}}
                                </div></td>
                        </tr>
                        <div class="modal fade" id="modal-danger">
                            <div class="modal-dialog">
                                <div class="modal-content bg-danger">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Delete Department</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this department?</p>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <form method="POST" action="{{ route('personnels.destroy', $personnel->id) }}">
                                            @csrf
                                            @method('Delete')
                                            <button type="submit" class="float-left btn btn-outline-light">Delete</button>
                                        </form>
                                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
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
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
</body>
</html>
