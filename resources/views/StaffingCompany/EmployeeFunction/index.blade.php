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
                <h3>{{ __('Staffing_Company/Staff_Function/f_index.staff_function') }}</h3>
                <a href="{{route('employee_functions.create')}}">
                    <button class="btn btn-success float-right">{{ __('Staffing_Company/Staff_Function/f_index.add_new') }}</button>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>{{ __('Staffing_Company/Staff_Function/f_index.id') }}.#</th>
                        <th>{{ __('Staffing_Company/Staff_Function/f_index.name') }}</th>
                        <th>{{ __('Staffing_Company/Staff_Function/f_index.code') }}</th>
                        <th>{{ __('Staffing_Company/Staff_Function/f_index.action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employee_functions as $employee_function)
                        <tr>
                            <td>{{$employee_function->id}}</td>
                            <td>{{$employee_function->name ?? ''}}</td>
                            <td>{{$employee_function->code ?? ''}}</td>
                            <td><div class="row">
                                    <a href="{{ route('employee-functions.show',$employee_function->id ) }}">
                                        <i style="color: green;" class="col fa fa-eye"></i>
                                    </a>
                                    <a href="{{route('employee_functions.edit', $employee_function->id)}}">
                                        <i style="color: green;" class="col far fa-edit"></i>
                                    </a>
{{--                                    <form method="POST" action="{{ route('employee_functions.destroy', $employee_function->id) }}" id="delete-form-{{ $employee_function->id }}">--}}
{{--                                        @csrf--}}
{{--                                        @method('Delete')--}}
{{--                                        <i style="color: red" type="button" onclick="confirmDelete({{ $employee_function->id }})" class="col fas fa-trash"></i>--}}
{{--                                    </form>--}}
                                </div></td>
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
