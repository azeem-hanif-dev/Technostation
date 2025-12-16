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
                    <h3>Contact {{ __('Staffing_Company/common.management') }}</h3>
                    <a href="{{ route('contacts.create') }}">
                        <button class="btn btn-success float-right">{{ __('Staffing_Company/common.add_new') }}
                            Contact</button>
                    </a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                {{-- <th>{{ __('Staffing_Company/common.name') }}</th> --}}
                                <th>{{ __('Staffing_Company/common.first_name') }}</th>
                                <th>{{ __('Staffing_Company/common.last_name') }}</th>
                                <th>{{ __('Staffing_Company/common.department') }}</th>
                                <th>{{ __('Staffing_Company/common.email') }}</th>
                                {{-- <th>{{ __('Staffing_Company/common.employee_function') }}</th> --}}
                                <th>{{ __('Staffing_Company/common.phone') }}</th>
                                <th>{{ __('Staffing_Company/staff/s_index.status') }}</th>
                                <th>{{ __('Staffing_Company/common.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $contact)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $contact->first_name ?? '' }}</td>
                                    <td>{{ $contact->last_name ?? '' }}</td>
                                    {{-- <td>{{$contact->function_text ?? ''}}</td> --}}
                                    {{-- <td>{{ $contact->department->name ?? '' }}</td> --}}
                                    <td>
                                        <ul>
                                            @foreach ($contact->departments as $department)
                                                <li>{{ $department->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $contact->email ?? '' }}</td>
                                    <td>{{ $contact->mobile ?? '' }}</td>

                                    <td>
                                        @if ($contact->active)
                                            <span
                                                style="color: white; padding: 3px; font-size: 12px; background-color: green">{{ __('Staffing_Company/staff/s_index.active') }}</span>
                                        @else
                                            <span
                                                style="color: white; padding: 3px; font-size: 12px; background-color: red">{{ __('Staffing_Company/staff/s_index.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row">
                                            <a href="{{ route('contacts.show', $contact->id) }}">
                                                <i style="color: green;" class="col fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('contacts.edit', $contact->id) }}">
                                                <i style="color: green;" class="col far fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('contacts.destroy', $contact->id) }}"
                                                id="delete-form-{{ $contact->id }}">
                                                @csrf
                                                @method('Delete')

                                                {{-- <i style="color: red; cursor: pointer;" class="col fas fa-trash"
                                                    data-toggle="modal" data-target="#deleteModal"
                                                    onclick="setDeleteAction({{ $contact->id }}, '{{ $contact->first_name }} {{ $contact->last_name }}', '{{ $contact->department->name ?? 'N/A' }}')">
                                                </i> --}}
                                                <i style="color: red; cursor: pointer;"
                                                    onclick="confirmDelete(
                                                    {{ $contact->id }},
                                                    '{{ addslashes($contact->first_name . ' ' . $contact->last_name) }}',
                                                    '{{ addslashes(optional($contact->department)->name ?? '') }}',
                                                    '{{ addslashes(optional($contact->staffingProjects->first())->name ?? '') }}',
                                                    {{ $contact->staffing_projects_count }}
                                                )"
                                                    class="col fas fa-trash">
                                                </i>







                                            </form>
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
                extraInfo += `${lang.department}: <strong>${departmentName}</strong><br>`;
            } else {
                extraInfo += `${lang.no_department}<br>`;
            }

            if (projectCount > 1) {
                extraInfo += `<strong>${lang.project_multiple.replace(':count', projectCount)}</strong><br>`;
            } else if (projectCount === 1 && firstProjectName) {
                extraInfo += `${lang.project_single}: <strong>${firstProjectName}</strong><br>`;
            } else {
                extraInfo += `${lang.no_project}<br>`;
            }

            Swal.fire({
                title: lang.confirm_title,
                html: `<strong style="color:red;">${fullName}</strong><br>${extraInfo}${lang.confirm_html}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: lang.confirm_button,
                cancelButtonText: lang.cancel_button
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: lang.deleted_title,
                        text: lang.deleted_text,
                        icon: 'success',
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




        // function confirmDelete(itemId) {
        //     if (confirm('Are you sure you want to delete this?')) {
        //         document.getElementById('delete-form-' + itemId).submit();
        //     }
        // }


        // function setDeleteAction(id, fullName, departmentName) {
        //     // Set the modal text
        //     document.getElementById('contact-name').innerText = fullName;
        //     document.getElementById('department-name').innerText = departmentName;

        //     // Set the correct form id
        //     document.getElementById('confirmDeleteBtn').setAttribute('form', `delete-form-${id}`);
        // }

        const lang = {
            confirm_title: @json(__('Staffing_Company/Contact/crud.confirm_title')),
            confirm_html: @json(__('Staffing_Company/Contact/crud.confirm_html')),
            deleted_title: @json(__('Staffing_Company/Contact/crud.deleted_title')),
            deleted_text: @json(__('Staffing_Company/Contact/crud.deleted_text')),
            confirm_button: @json(__('Staffing_Company/Contact/crud.confirm_button')),
            cancel_button: @json(__('Staffing_Company/Contact/crud.cancel_button')),
            no_department: @json(__('Staffing_Company/Contact/crud.no_department')),
            no_project: @json(__('Staffing_Company/Contact/crud.no_project')),
            department: @json(__('Staffing_Company/Contact/crud.department')),
            project_single: @json(__('Staffing_Company/Contact/crud.project_single')),
            project_multiple: @json(__('Staffing_Company/Contact/crud.project_multiple')),
        };
    </script>
</body>

</html>
