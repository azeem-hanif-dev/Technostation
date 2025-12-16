<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="checkbox"] {
            transform: scale(1.5);
            margin-right: 5px;
        }


    </style>


    <div class="content-wrapper">
        <div class="card">
            <div style="margin-top: 10px;" class="card-header">
                <h3>User Rights</h3>
                <a href="{{route('user-options.create')}}">
                    <button class="btn btn-success float-right">Add User</button>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Group</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $user_option)
                        <tr>
                            <td>{{$user_option['id']}}</td>
                            <td>{{$user_option['user']['name'] ?? ''}}</td>
                            <td>{{$user_option['user']['email'] ?? ''}}</td>
                            <td>{{$user_option['role']['name'] ?? ''}}</td>
                            <td>{{$user_option['status'] ?? ''}}</td>
                            <td><div class="row">
                                    <a href="#" onclick="setModalData({{ $user_option['id'] }},{!! json_encode($user_option['user_rights']->pluck('id')->toArray()) !!})" data-toggle="modal" data-target="#exampleModal">
                                        <i style="color: green;" class="col fa fa-list-ol"></i>
                                    </a>
                                    <a href="{{route('user-options.edit', $user_option['id'])}}">
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
                                        <h4 class="modal-title">Delete</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this?</p>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <form method="POST" action="{{ route('user-options.destroy', $user_option['id']) }}">
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
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 id="heading"></h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="rightsForm" method="POST" action="">
                            @csrf
                            <div class="modal-body">
                                <table>
                                    <thead>
                                    <tr>
                                        <th>Right</th>
                                        <th>Module</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($options as $module)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="module_ids[]" value="{{$module->id}}">
                                            </td>
                                            <td>{{$module->name}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
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
    });

    function setModalData(userId, userRights) {
        var form = document.getElementById('rightsForm');
        form.action = "{{ url('/user-rights/') }}" + '/' + userId;

        var checkboxes = document.getElementsByName('module_ids[]');
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = userRights.includes(parseInt(checkbox.value));
        });
    }

</script>
</body>
</html>
