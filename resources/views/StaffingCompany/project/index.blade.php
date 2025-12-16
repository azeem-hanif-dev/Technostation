<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')

        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            @if (session('message'))
                <div class="alert alert-warning">
                    {{ session('message') }}

                </div>
            @endif

            <div class="card">
                <div style="margin-top: 10px;" class="card-header">
                    <h3>{{ __('Staffing_Company/Project/p_index.project') }}</h3>
                    <a href="{{ route('staffing_projects.create') }}">
                        <button
                            class="btn btn-success float-right">{{ __('Staffing_Company/Project/p_index.add_new') }}</button>
                    </a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">


                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>{{ __('Staffing_Company/Project/p_index.name') }}</th>
                                <th>{{ __('Staffing_Company/Project/p_index.department') }}</th>
                                <th>{{ __('Staffing_Company/Project/p_index.address') }}</th>
                                <th>{{ __('Staffing_Company/Project/p_index.city') }}</th>
                                <th>{{ __('Staffing_Company/Project/p_index.status') }}</th>
                                <th>{{ __('Staffing_Company/Project/p_index.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td>{{ $project->name ?? '' }}</td>
                                    <td>{{ $project->department->name ?? '' }}</td>
                                    <td>{{ $project->address ?? '' }}</td>
                                    <td>{{ $project->city ?? '' }}</td>
                                    <td>
                                        @if ($project->active)
                                            <span
                                                style="color: white; padding: 3px; font-size: 12px; background-color: green">{{ __('Staffing_Company/common.active') }}</span>
                                        @else
                                            <span
                                                style="color: white; padding: 3px; font-size: 12px; background-color: red">{{ __('Staffing_Company/common.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row">
                                            <a href="{{ route('staffing-project.view', $project->id) }}">
                                                <i style="color: green;" class="col fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('staffing_projects.edit', $project->id) }}">
                                                <i style="color: green;" class="col far fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('staffing_projects.destroy', $project->id) }}"
                                                id="delete-form-{{ $project->id }}">
                                                @csrf
                                                @method('Delete')
                                                <i style="color: red; cursor: pointer"
                                                    onclick="confirmDelete({{ $project->id }})"
                                                    class="col fas fa-trash"></i>
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

    <!-- Page specific script -->
    {{-- <script>
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

    </script> --}}
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        function confirmDelete(projectId) {
            Swal.fire({
                title: translations.deleteTitle,
                text: translations.deleteText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: translations.deleteConfirm
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/staffing_projects/" + projectId,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        },
                        success: function(response) {
                            if (response.status === 'has_related') {
                                Swal.fire(
                                    translations.cannotDeleteTitle,
                                    response.message,
                                    'warning'
                                );
                            } else if (response.status === 'deleted') {
                                Swal.fire(
                                    translations.deletedTitle,
                                    translations.deletedText,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                translations.errorTitle,
                                translations.errorText,
                                'error'
                            );
                        }
                    });
                }
            });
        }


        const translations = {
            deleteTitle: @json(__('Staffing_Company/Project/p_index.delete_title')),
            deleteText: @json(__('Staffing_Company/Project/p_index.delete_text')),
            deleteConfirm: @json(__('Staffing_Company/Project/p_index.delete_confirm')),
            deletedTitle: @json(__('Staffing_Company/Project/p_index.deleted_title')),
            deletedText: @json(__('Staffing_Company/Project/p_index.deleted_text')),
            cannotDeleteTitle: @json(__('Staffing_Company/Project/p_index.cannot_delete_title')),
            errorTitle: @json(__('Staffing_Company/Project/p_index.error_title')),
            errorText: @json(__('Staffing_Company/Project/p_index.error_text')),
        };
    </script>

</body>

</html>
