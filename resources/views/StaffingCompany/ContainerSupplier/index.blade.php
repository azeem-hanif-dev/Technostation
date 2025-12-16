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
                    <div class="col-md-6">
                        <h3>{{ __('Staffing_Company/Container_Supplier/c_index.container_supplier') }}</h3>
                    </div>
                    <div class="col-md-6">
                        <a href="{{route('container-supplier.create')}}">
                            <button class="btn btn-success float-right">{{ __('Staffing_Company/Container_Supplier/c_index.new_supplier') }}</button>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>{{ __('Staffing_Company/Container_Supplier/c_index.company_name') }}</th>
                        <th>{{ __('Staffing_Company/Container_Supplier/c_index.code') }}</th>
                        <th>{{ __('Staffing_Company/common.address') }}</th>
                        <th>{{ __('Staffing_Company/common.telephone') }}</th>
                        <th>{{ __('Staffing_Company/common.email') }}</th>
                        <th>{{ __('Staffing_Company/common.option') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td>{{$supplier->company_name ?? ''}}</td>
                            <td>{{$supplier->code ?? ''}}</td>
                            <td>{{$supplier->address ?? ''}}</td>
                            <td>{{$supplier->telephone ?? ''}}</td>
                            <td>{{$supplier->email ?? ''}}</td>
                            <td><div class="row">
                                    <a href="{{ route('container-supplier.view', $supplier->id) }}">
                                        <i style="color: green;" class="col fa fa-eye"></i>
                                    </a>
                                    <a href="{{route('container-supplier.edit', $supplier->id)}}">
                                        <i style="color: green;" class="col far fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('container-supplier.destroy', $supplier->id) }}" id="delete-form-{{ $supplier->id }}">
                                        @csrf
                                        @method('Delete')
                                        <i style="color: red" type="button" onclick="confirmDelete({{ $supplier->id }})" class="col fas fa-trash"></i>
                                    </form>
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
