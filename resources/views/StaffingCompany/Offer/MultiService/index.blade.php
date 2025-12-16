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

                            @if (session('success') == 'multi_create_success')
                                swalText = "{{ __('Staffing_Company/offers/index.multi_create_success') }}";
                            @elseif (session('success') == 'multi_delete_success')
                                swalText = "{{ __('Staffing_Company/offers/index.multi_delete_success') }}";
                            @endif

                            @if (session('download_offer_id'))
                                // CREATE: download PDF and then show popup
                                const offerId = "{{ session('download_offer_id') }}";
                                const downloadUrl = "{{ route('multiservice-offers.downloadPDF', ':id') }}".replace(':id', offerId);

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
                    <h3 class="mb-0 d-inline">{{ __('Staffing_Company/side_bar.multi_service') }}</h3>
                    <a href="{{ route('multi-service.create') }}" class="btn btn-success float-right">
                        {{ __('Staffing_Company/offers/index.create_offer') }}
                    </a>
                </div>

                @php
                    $rows = isset($multiServiceOffers) ? $multiServiceOffers : (isset($offers) ? $offers : collect());
                @endphp

                <table id="example1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('Staffing_Company/offers/index.id') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.title') }}</th>
                            <th>{{ __('Staffing_Company/offers/create.customer') }}</th>
                            <th class="text-right">{{ __('Staffing_Company/offers/create.total_price') }} (€)</th>
                            <th>{{ __('Staffing_Company/offers/index.details') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.status') }}</th> <!-- Fixed missing closing tag -->
                            <th>{{ __('Staffing_Company/offers/index.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $offer)
                            <tr>
                                <td>{{ $offer->id }}</td>
                                <td>{{ $offer->title }}</td>
                                <td>{{ optional($offer->customer)->name ?? '-' }}</td>
                                <td class="text-right">{{ number_format((float)($offer->total_price ?? 0), 2) }}</td>
                                <td>
                                    @php
                                        $services = is_array($offer->services) ? $offer->services : [];
                                    @endphp
                                    @if (!empty($services))
                                        <ul class="mb-0">
                                            @foreach ($services as $svc)
                                                <li>
                                                    <strong>{{ $svc['name'] ?? '—' }}</strong>
                                                    @if (isset($svc['price']))
                                                        — €{{ number_format((float) $svc['price'], 2) }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('offer.accept', ['type' => 'multi', 'id' => $offer->id]) }}">
                                        @csrf
                                        @method('PATCH') <!-- PATCH for updating -->
                                        <select name="status" onchange="this.form.submit()" class="form-control form-control-sm">
                                            <option value="pending" {{ $offer->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="accepted" {{ $offer->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-nowrap">
                                    <!-- History -->
                                    <a href="{{ route('offers.history', ['type' => 'multi', 'id' => $offer->id]) }}" title="View Offer History">
                                        <i class="fa fa-history" style="color:rgb(3, 10, 3);" aria-hidden="true"></i>
                                    </a>

                                    <!-- Send Email -->
                                    <a href="{{ route('offers.send', ['type' => 'multi', 'id' => $offer->id]) }}" title="Send Email">
                                        <i class="fas fa-envelope" style="color:green;" aria-hidden="true"></i>
                                    </a>

                                    <!-- PDF Download -->
                                    @if (Route::has('multiservice-offers.downloadPDF'))
                                        <a href="{{ route('multiservice-offers.downloadPDF', $offer->id) }}" title="Download PDF" style="margin-left: 5px;">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    @endif

                                    <!-- Delete -->
                                    @if (Route::has('multiservice-offers.destroy'))
                                        <form method="POST" action="{{ route('multiservice-offers.destroy', $offer->id) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete Offer" style="border:none; background:none; cursor:pointer; color:red; margin-left: 5px;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">—</td> <!-- Fixed colspan -->
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination if provided --}}
                @if (method_exists($rows, 'hasPages') && $rows->hasPages())
                    <div class="mt-3">
                        {{ $rows->links() }}
                    </div>
                @endif
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
