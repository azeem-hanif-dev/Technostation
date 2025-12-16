<!DOCTYPE html>
<html lang="en">
@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div class="card">
                <div class="card-header mt-2">
                    <h3>{{ $translations['title'] }}</h3>

                    <a href="{{ route('camp-maintenance.create') }}">
                        <button class="btn btn-success float-right">
                            {{ $translations['buttons']['create'] }}
                        </button>
                    </a>
                </div>

                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>{{ $translations['labels']['camp_name'] }}</th>
                                <th>{{ $translations['labels']['customer_name'] }}</th>
                                {{-- <th>{{ $translations['labels']['department'] }}</th> --}}
                                <th>{{ $translations['labels']['week_no'] }}</th>
                                <th>{{ $translations['labels']['created_by'] }}</th>
                                {{-- <th>{{ $translations['labels']['status'] }}</th> --}}
                                <th>{{ $translations['labels']['actions'] }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td>{{ $project->project->name ?? '-' }}</td>
                                    <td>{{ $project->project->customer->name ?? '-' }}</td>
                                    {{-- <td>{{ $project->project->department->name ?? '-' }}</td> --}}
                                    <td>{{ $project->week_no }}</td>
                                    {{-- <td>{{ $project->date ?? '-' }}</td> --}}
                                    <td>{{ $project->creator->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-around">
                                            <a href="{{ route('camp-maintenance.show', $project->id) }}">
                                                <i class="fa fa-eye text-success"></i>
                                            </a>

                                            <a href="{{ route('camp-maintenance.download-pdf', $project->id) }}"
                                                title="Download PDF">
                                                <i class="far fa-file-pdf text-danger"></i>
                                            </a>
                                            <a href="{{ route('camp-maintenance.edit', $project->id) }}">
                                                <i class="far fa-edit text-primary"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('camp-maintenance.destroy', $project->id) }}"
                                                id="delete-form-{{ $project->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <i type="button" class="fas fa-trash text-danger"
                                                    onclick="confirmDelete({{ $project->id }})"></i>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        function confirmDelete(id) {
            if (confirm("{{ $translations['messages']['delete_confirm'] }}")) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</body>

</html>
