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
                    <h3>{{ __('Staffing_Company/Customer/c_index.cm') }}</h3>
                    <a href="{{ route('customers.create') }}">
                        <button
                            class="btn btn-success float-right">{{ __('Staffing_Company/Customer/c_index.add_new') }}</button>
                    </a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>{{ __('Staffing_Company/Customer/c_index.name') }}</th>
                                <th>{{ __('Staffing_Company/Customer/c_index.contact_person') }}</th>
                                {{-- <th>{{ __('Staffing_Company/common.notes') }}</th> --}}
                                {{--                        <th>Company</th> --}}
                                <th>{{ __('Staffing_Company/Customer/c_index.email') }}</th>
                                {{-- <th>{{ __('Staffing_Company/Customer/c_index.contact_person') }}</th> --}}
                                {{-- <th>{{ __('Staffing_Company/Customer/c_index.phone') }}</th> --}}
                                <th>{{ __('Staffing_Company/Customer/c_index.address') }}</th>
                                <th>{{ __('Staffing_Company/Customer/c_index.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @dd($customers->staffing_project->name) --}}
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->name ?? 'null' }}</td>
                                    <td>{{ $customer->user->contact_person1 ?? 'null' }}</td>
                                    {{-- <td>{{$customer->notes ?? ''}}</td> --}}
                                    <td>{{ $customer->user->email ?? 'null' }}</td>

                                    {{-- <td>{{$customer->user->phone ?? ''}}</td> --}}
                                    <td>{{ $customer->user->address ?? 'null' }},
                                        {{ $customer->user->city ?? 'null' }},
                                        {{ $customer->user->country ?? 'null' }}</td>
                                    <td>
                                        <div class="row">
                                            <a href="{{ route('customer.view', $customer->id) }}">
                                                <i style="color: green;" class="col fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer->id) }}">
                                                <i style="color: green;" class="col far fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('customers.destroy', $customer->id) }}"
                                                id="delete-form-{{ $customer->id }}">
                                                @csrf
                                                @method('Delete')
                                                {{-- <i style="color: red; cursor: pointer;"
                                                    onclick="confirmDelete({{ $customer->id }})"
                                                    class="col fas fa-trash"></i> --}}
                                                <i style="color: red; cursor: pointer;"
                                                    onclick="confirmDelete({{ $customer->id }}, '{{ addslashes($customer->name) }}', {{ $customer->staffingproject_count }}, '{{ $customer->staffingproject->first()->name ?? '' }}')"
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
                                                <h4 class="modal-title">Delete Customer</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this customer?</p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <form method="POST"
                                                    action="{{ route('customers.destroy', $customer->id) }}">
                                                    @csrf
                                                    @method('Delete')
                                                    <button type="submit"
                                                        class="float-left btn btn-outline-light">Delete</button>
                                                </form>
                                                <button type="button" class="btn btn-outline-light"
                                                    data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- Delete Confirmation Modal -->
    {{-- <div class="modal fade" id="modal-danger" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Customer {{ $customer->name ?? '' }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span style="color:white;">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modal-body-text"></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" id="delete-form-modal">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-light">Delete</button>
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


        function confirmDelete(id, fullName, departmentName = '', firstProjectName = '', projectCount = 0) {
            let extraInfo = '';

            if (departmentName) {
                extraInfo +=
                    `${deleteTranslations.departmentLabel.replace(':name', `<strong>${departmentName}</strong>`)}<br>`;
            } else {
                extraInfo += `${deleteTranslations.noDepartment}<br>`;
            }

            if (projectCount > 1) {
                extraInfo += `${deleteTranslations.multipleProjects
            .replace(':count', `<strong>${projectCount}</strong>`)}<br>`;
            } else if (projectCount === 1 && firstProjectName) {
                extraInfo += `${deleteTranslations.singleProject
            .replace(':project', `<strong>${firstProjectName}</strong>`)}<br>`;
            } else {
                extraInfo += `${deleteTranslations.noProjects}<br>`;
            }

            Swal.fire({
                title: deleteTranslations.title,
                html: deleteTranslations.messageHtml
                    .replace(':name', `<strong style="color:red;">${fullName}</strong>`)
                    .replace(':extraInfo', extraInfo),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: deleteTranslations.confirmButton,
                cancelButtonText: deleteTranslations.cancelButton
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: deleteTranslations.successTitle,
                        text: deleteTranslations.successText,
                        icon: "success",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: true,
                        willClose: () => {
                            document.getElementById(`delete-form-${id}`).submit();
                        }
                    });
                }
            });
        }

        const deleteTranslations = {
            title: @json(__('Staffing_Company/Customer/crud.delete_title')),
            confirmButton: @json(__('Staffing_Company/Customer/crud.delete_confirm_button')),
            cancelButton: @json(__('Staffing_Company/Customer/crud.delete_cancel_button')),
            successTitle: @json(__('Staffing_Company/Customer/crud.delete_success_title')),
            successText: @json(__('Staffing_Company/Customer/crud.delete_success_text')),
            messageHtml: @json(__('Staffing_Company/Customer/crud.delete_html')),
            noDepartment: @json(__('Staffing_Company/Customer/crud.no_department')),
            departmentLabel: @json(__('Staffing_Company/Customer/crud.department_label')), // ":name"
            noProjects: @json(__('Staffing_Company/Customer/crud.no_projects')),
            singleProject: @json(__('Staffing_Company/Customer/crud.single_project')),
            multipleProjects: @json(__('Staffing_Company/Customer/crud.multiple_projects')),
        };


        // function confirmDelete(itemId) {
        //     if (confirm('Are you sure you want to delete this?')) {
        //         document.getElementById('delete-form-' + itemId).submit();
        //     }
        // }
    </script>
</body>

</html>
