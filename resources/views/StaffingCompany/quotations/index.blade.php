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
                    <h3>{{ __('Staffing_Company/Quotations/create.title') }}</h3>
                    <a href="{{ route('quotations.create') }}">
                        <button class="btn btn-success float-right">{{ __('Staffing_Company/Quotations/create.title') }}</button>
                    </a>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Staffing_Company/Quotations/index.id') }}</th>
                                <th>{{ __('Staffing_Company/Quotations/index.customer') }}</th>
                                <th>{{ __('Staffing_Company/Quotations/index.project') }}</th>
                                <th>{{ __('Staffing_Company/Quotations/index.contact_person') }}</th>
                                <th>{{ __('Staffing_Company/Quotations/index.total') }}</th>
                                <th>{{ __('Staffing_Company/Quotations/index.options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotations as $quotation)
                                <tr>
                                    <td>{{ $quotation->id }}</td>
                                    <td>{{ $quotation->customer->name }}</td>
                                    <td>{{ $quotation->project }}</td>
                                    <td>{{ $quotation->contact_person }}</td>
                                    <td>{{ $quotation->total }}</td>

                                    <td>
                                        <div class="d-flex flex-column text-center">
                                            <div class="mb-2">
                                                <a href="{{ route('quotations.show', $quotation->id) }}">
                                                    <i style="color: blue;" class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('quotations.edit', $quotation->id) }}">
                                                    <i style="color: orange;" class="fa fa-edit"></i>
                                                </a>
                                            </div>
                                            <div>
                                                <form action="{{ route('quotations.destroy', $quotation->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('{{ __('Staffing_Company/Quotations/index.delete_confirm') }}')"
                                                        style="border:none; background:none;">
                                                        <i style="color: red; cursor:pointer;" class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('quotations.viewPdf', $quotation->id) }}"
                                                    target="_blank">
                                                    <i style="color: green;" class="fa fa-file-pdf"></i>
                                                </a>
                                            </div>
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
    </script>
</body>

</html>
