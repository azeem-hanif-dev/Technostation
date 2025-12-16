<!DOCTYPE html>
<html lang="en">
@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif --}}

        <div class="content-wrapper">
            <div class="container p-4">
                @if (session('success'))
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            let swalText = '';

                            @if (session('success') == 'handover_create_success')
                                swalText = "{{ __('Staffing_Company/offers/index.handover_create_success') }}";
                            @elseif (session('success') == 'handover_delete_success')
                                swalText = "{{ __('Staffing_Company/offers/index.handover_delete_success') }}";
                            @endif

                            @if (session('download_offer_id'))
                                // CREATE: download PDF and then show popup
                                const offerId = "{{ session('download_offer_id') }}";
                                const downloadUrl = "{{ route('offers.downloadPDF', ':id') }}".replace(':id', offerId);

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
                    <h3 class="mb-0 d-inline">{{ __('Staffing_Company/offers/index.handover_cleaning') }}</h3>
                    <a href="{{ route('offers.create') }}" class="btn btn-success float-right">
                        {{ __('Staffing_Company/offers/index.create_offer') }}
                    </a>
                </div>

                <table id="example1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('Staffing_Company/offers/index.id') }}</th>
                            {{-- <th>{{ __('Staffing_Company/offers/index.title') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.subject') }}</th> --}}
                            <th>{{ __('Staffing_Company/offers/index.project') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.our_reference') }}</th>
                            {{-- <th>{{ __('Staffing_Company/offers/index.total_price') }}</th> --}}
                            <th>{{ __('Staffing_Company/offers/index.details') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.status') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            <tr>
                                <td>{{ $offer->id }}</td>
                                {{-- <td>{{ $offer->title }}</td> --}}
                                {{-- <td>{{ $offer->subject }}</td> --}}
                                <td>{{ $offer->project->name ?? '-' }}</td>
                                <td>{{ $offer->our_reference }}</td>
                                {{-- <td>{{ $offer->total_price }}</td> --}}
                                <td>
                                    @if (!empty($offer->scope))
                                        @foreach ($offer->scope as $scopeItem)
                                            <strong>{{ $scopeItem['name'] }}</strong><br>
                                            @if (!empty($scopeItem['descriptions']))
                                                <ul class="mb-1">
                                                    @foreach ($scopeItem['descriptions'] as $desc)
                                                        <li>{{ $desc }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <form method="POST"
                                        action="{{ route('offer.accept', ['type' => 'handover', 'id' => $offer->id]) }}">
                                        @csrf
                                        @method('PATCH') <!-- PATCH for updating -->
                                        <select name="status" onchange="this.form.submit()"
                                            class="form-control form-control-sm">
                                            <option value="pending"
                                                {{ $offer->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="accepted"
                                                {{ $offer->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                        </select>
                                    </form>

                                </td>

                                <td class="text-nowrap">
                                    <!-- History Link -->
                                    <a href="{{ route('offers.history', ['type' => 'handover', 'id' => $offer->id]) }}"
                                        title="View Offer History">
                                        <i class="fa fa-history" style="color:rgb(3, 10, 3);" aria-hidden="true"></i>
                                    </a>

                                    <!-- Send Email Link -->
                                    <a href="{{ route('offers.send', ['type' => 'handover', 'id' => $offer->id]) }}"
                                        title="Send Offer Email">
                                        <i class="fas fa-envelope" style="color:green;" aria-hidden="true"></i>
                                    </a>

                                    <!-- Download PDF Link -->
                                    <a href="{{ route('offers.downloadPDF', $offer->id) }}" title="Download PDF">
                                        <i class="fas fa-file-pdf" aria-hidden="true"></i>
                                    </a>

                                    <!-- Delete Offer Form -->
                                    <form method="POST" action="{{ route('offers.destroy', $offer->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="border:none; background:none; cursor:pointer; color:red;"
                                            title="Delete Offer">
                                            <i class="fas fa-trash" aria-hidden="true"></i>
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
