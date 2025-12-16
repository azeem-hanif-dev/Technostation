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
                    <h3>{{ __('Staffing_Company/Department/d_index.department') }}</h3>
                    <a href="{{ route('departments.create') }}">
                        <button
                            class="btn btn-success float-right">{{ __('Staffing_Company/Department/d_index.add_new') }}</button>
                    </a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>{{ __('Staffing_Company/Department/d_index.name_department') }}</th>
                                <th>{{ __('Staffing_Company/Department/d_index.customer') }}</th>
                                <th>{{ __('Staffing_Company/Department/d_index.email') }}</th>
                                {{-- <th>{{ __('Staffing_Company/Department/d_index.customer') }}</th> --}}
                                <th>{{ __('Staffing_Company/Department/d_index.phone') }}</th>
                                <th>{{ __('Staffing_Company/Department/d_index.address') }}</th>
                                <th>{{ __('Staffing_Company/Department/d_index.action') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $department->id }}</td>
                                    <td>{{ $department->name ?? '' }}</td>
                                    <td>{{ $department->customer->name ?? '' }}</td>

                                    <td>{{ $department->email ?? '' }}</td>
                                    {{-- <td>{{$department->customer->name ?? ''}}</td> --}}
                                    <td>{{ $department->phone ?? '' }}</td>
                                    <td>
                                        @if (!empty($department->city) && !empty($department->address))
                                            {{ $department->city }}, {{ $department->address }}
                                        @elseif (!empty($department->city))
                                            {{ $department->city }}
                                        @elseif (!empty($department->address))
                                            {{ $department->address }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row">
                                            <a href="{{ route('department.view', $department->id) }}">
                                                <i style="color: green;" class="col fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('departments.edit', $department->id) }}">
                                                <i style="color: green;" class="col far fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('departments.destroy', $department->id) }}"
                                                id="delete-form-{{ $department->id }}">
                                                @csrf
                                                @method('Delete')
                                                <i style="color: red; cursor: pointer;"
                                                    onclick="confirmDelete(
                                                    {{ $department->id }},
                                                    '{{ addslashes($department->name) }}',
                                                    {{ $department->staffing_projects_count }},
                                                    '{{ addslashes($department->staffingProjects->first()->name ?? '') }}',
                                                    {{ $department->customer ? 1 : 0 }},
                                                    '{{ addslashes($department->customer->name ?? '') }}'
                                                )"
                                                    class="col fas fa-trash">
                                                </i>





                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                {{-- <div class="modal fade" id="modal-danger">
                                    <div class="modal-dialog">
                                        <div class="modal-content bg-danger">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Delete Department</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this department?</p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <form method="POST"
                                                    action="{{ route('departments.destroy', $department->id) }}">
                                                    @csrf
                                                    @method('Delete')
                                                    <button type="submit"
                                                        class="float-left btn btn-outline-light">Delete</button>
                                                </form>
                                                <button type="button" class="btn btn-outline-light"
                                                    data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div> --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>


    </div>
    {{-- <div class="modal fade" id="modal-danger">
        <div class="modal-dialog">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Department</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="delete-message">Are you sure you want to delete this department?</p>
                </div>
                <div class="modal-footer justify-content-end">
                    <form method="POST" id="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="float-right btn btn-outline-light">Delete</button>
                    </form>
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div> --}}


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


        function confirmDelete(departmentId, departmentName, projectCount, firstProjectName = '', customerCount = 0,
            customerName = '') {
            let htmlMessage = '';

            // Projects info
            if (projectCount > 1) {
                htmlMessage += deptDeleteLang.multipleProjects
                    .replace(':name', `<strong style="color:red;">${departmentName}</strong>`)
                    .replace(':count', `<strong>${projectCount}</strong>`) + '<br>';
            } else if (projectCount === 1 && firstProjectName) {
                htmlMessage += deptDeleteLang.singleProject
                    .replace(':name', `<strong style="color:red;">${departmentName}</strong>`)
                    .replace(':project', `<strong>${firstProjectName}</strong>`) + '<br>';
            } else {
                htmlMessage += deptDeleteLang.noProjects.replace(':name',
                    `<strong style="color:red;">${departmentName}</strong>`) + '<br>';
            }

            // Customer info
            if (customerCount > 1) {
                htmlMessage += deptDeleteLang.multipleCustomers
                    .replace(':count', `<strong>${customerCount}</strong>`) + '<br>';
            } else if (customerCount === 1 && customerName) {
                htmlMessage += deptDeleteLang.singleCustomer
                    .replace(':customer', `<strong>${customerName}</strong>`) + '<br>';
            } else {
                htmlMessage += deptDeleteLang.noCustomers + '<br>';
            }

            Swal.fire({
                title: deptDeleteLang.deleteTitle,
                html: htmlMessage + deptDeleteLang.confirmDeleteMessage,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: deptDeleteLang.deleteConfirmBtn,
                cancelButtonText: deptDeleteLang.deleteCancelBtn
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: deptDeleteLang.successTitle,
                        text: deptDeleteLang.successText,
                        icon: "success",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });

                    document.getElementById(`delete-form-${departmentId}`).submit();
                }
            });
        }



        const deptDeleteLang = {
            deleteTitle: @json(__('Staffing_Company/Department/crud.delete_title')),
            deleteConfirmBtn: @json(__('Staffing_Company/Department/crud.delete_confirm_button')),
            deleteCancelBtn: @json(__('Staffing_Company/Department/crud.delete_cancel_button')),
            successTitle: @json(__('Staffing_Company/Department/crud.delete_success_title')),
            successText: @json(__('Staffing_Company/Department/crud.delete_success_text')),
            noProjects: @json(__('Staffing_Company/Department/crud.no_projects')),
            multipleProjects: @json(__('Staffing_Company/Department/crud.multiple_projects')),
            singleProject: @json(__('Staffing_Company/Department/crud.single_project')),
            noCustomers: @json(__('Staffing_Company/Department/crud.no_customers')),
            multipleCustomers: @json(__('Staffing_Company/Department/crud.multiple_customers')),
            singleCustomer: @json(__('Staffing_Company/Department/crud.single_customer')),
            confirmDeleteMessage: @json(__('Staffing_Company/Department/crud.confirm_delete_message')),
        };
        // function confirmDelete(itemId) {
        //     if (confirm('Are you sure you want to delete this?')) {
        //         document.getElementById('delete-form-' + itemId).submit();
        //     }
        // }
    </script>
</body>

</html>
