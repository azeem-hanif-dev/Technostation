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

                            @if (session('success') == 'site_create_success')
                                swalText = "{{ __('Staffing_Company/offers/index.site_create_success') }}";
                            @elseif (session('success') == 'site_delete_success')
                                swalText = "{{ __('Staffing_Company/offers/index.site_delete_success') }}";
                            @endif

                            @if (session('download_offer_id'))
                                // CREATE: download PDF and then show popup
                                const offerId = "{{ session('download_offer_id') }}";
                                const downloadUrl = "{{ route('site-offers.downloadPDF', ':id') }}".replace(':id', offerId);

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
                    <h3 class="mb-0 d-inline">{{ __('Staffing_Company/offers/index.site_maintenance') }}</h3>
                    <a href="{{ route('site.create') }}" class="btn btn-success float-right">
                        {{ __('Staffing_Company/offers/index.create_offer') }}
                    </a>
                </div>

                <table id="example1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('Staffing_Company/offers/index.id') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.title') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.project') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.our_reference') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.price_hour') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.price_time') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.details') }}</th>
                            <th>{{ __('Staffing_Company/offers/index.status') }}</th> <!-- Fixed missing </th> -->
                            <th>{{ __('Staffing_Company/offers/index.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siteMaintances as $site)
                            <tr>
                                <td>{{ $site->id }}</td>
                                <td>{{ $site->title }}</td>
                                <td>{{ $site->project->name ?? '-' }}</td>
                                <td>{{ $site->our_reference }}</td>
                                <td>{{ $site->price_hour }}</td>
                                <td>{{ $site->price_time }}</td>
                                <td>
                                    @if (!empty($site->scope))
                                        @foreach ($site->scope as $scopeItem)
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
                                    <form method="POST" action="{{ route('offer.accept', ['type' => 'site', 'id' => $site->id]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="form-control form-control-sm">
                                            <option value="pending" {{ $site->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="accepted" {{ $site->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-nowrap">
                                    <!-- History -->
                                    <a href="{{ route('offers.history', ['type' => 'site', 'id' => $site->id]) }}">
                                        <i class="fas fa-history text-info" style="color:rgb(4, 14, 4);"></i>
                                    </a>

                                    <!-- Send Email -->
                                    <a href="{{ route('offers.send', ['type' => 'site', 'id' => $site->id]) }}">
                                        <i class="fas fa-envelope" style="color:green;"></i>
                                    </a>

                                    <!-- PDF -->
                                    <a href="{{ route('site-offers.downloadPDF', $site->id) }}">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>

                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('site-offers.destroy', $site->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="border:none; background:none; cursor:pointer; color:red;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">—</td> <!-- Fixed colspan -->
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
