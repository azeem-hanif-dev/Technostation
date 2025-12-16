<!DOCTYPE html>
<html lang="en">
@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div class="container p-4">
                @if (session('success'))
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            let swalText = '';

                            @if (session('success') == 'retail_create_success')
                                swalText = "{{ __('Staffing_Company/offers/index.retail_create_success') }}";
                            @elseif (session('success') == 'retail_delete_success')
                                swalText = "{{ __('Staffing_Company/offers/index.retail_delete_success') }}";
                            @endif

                            @if (session('download_offer_id'))
                                // CREATE: download PDF and then show popup
                                const offerId = "{{ session('download_offer_id') }}";
                                const downloadUrl = "{{ route('retail-offers.downloadPDF', ':id') }}".replace(':id', offerId);

                                // Trigger download
                                const a = document.createElement('a');
                                a.href = downloadUrl;
                                a.download = '';
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);

                                // Show popup
                                Swal.fire({
                                    title: "Good Job!",
                                    text: swalText,
                                    icon: "success",
                                    confirmButtonText: "OK",
                                    timer: 2000,
                                });
                            @else
                                // DELETE: only show popup
                                Swal.fire({
                                    title: "Good Job!",
                                    text: swalText,
                                    icon: "success",
                                    confirmButtonText: "OK",
                                    timer: 2000,
                                });
                            @endif
                        });
                    </script>
                @endif

                <div class="card-header mb-3">
                    <h3 class="mb-0 d-inline">{{ __('Staffing_Company/offers/index.retail_bouw') }}</h3>
                    <a href="{{ route('retail-offers.create') }}" class="btn btn-success float-right">
                        {{ __('Staffing_Company/offers/index.create_offer') }}
                    </a>
                </div>

                <table id="example1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('Staffing_Company/offers/index.id') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.service_type') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.project') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.our_reference') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.construction_price') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.traffic_price') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.status') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($retailOffers as $retail)
                            <tr>
                                <td>{{ $retail->id }}</td>
                                <td>{{ $retail->service_type }}</td>
                                <td>{{ $retail->project->name ?? '-' }}</td>
                                <td>{{ $retail->our_reference }}</td>
                                <td>{{ $retail->construction_price }}</td>
                                <td>{{ $retail->traffic_controllers_price }}</td>
                                <td>
                                    <!-- Status Update Form -->
                                    <form method="POST" action="{{ route('offer.accept', ['type' => 'retail', 'id' => $retail->id]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="form-control form-control-sm">
                                            <option value="pending" {{ $retail->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="accepted" {{ $retail->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                        </select>
                                    </form>
                                </td>

                                <td class="text-nowrap">
                                    <!-- History -->
                                    <a href="{{ route('offers.history', ['type' => 'retail', 'id' => $retail->id]) }}" title="View History" style="margin-right: 6px;">
                                        <i class="fa fa-history" style="color:#0a0a0a;"></i>
                                    </a>

                                    <!-- Send Email -->
                                    <a href="{{ route('offers.send', ['type' => 'retail', 'id' => $retail->id]) }}" title="Send Email" style="margin-right: 6px;">
                                        <i class="fas fa-envelope" style="color:green;"></i>
                                    </a>

                                    <!-- PDF Download -->
                                    <a href="{{ route('retail-offers.downloadPDF', $retail->id) }}" title="Download PDF" style="margin-right: 6px;">
                                        <i class="fas fa-file-pdf" style="color:red;"></i>
                                    </a>

                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('retail-offers.destroy', $retail->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete Offer" style="border:none; background:none; cursor:pointer; margin-right: 6px;">
                                            <i class="fas fa-trash" style="color:red;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">—</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                "order": [],
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "ordering": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</html>
