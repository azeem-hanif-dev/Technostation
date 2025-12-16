<!DOCTYPE html>
<html lang="en">
@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div class="container p-4">

                {{-- Card Header --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            Email History – {{ ucfirst($type) }} Offer #{{ $offer->id }}
                        </h3>

                        <a href="{{ url()->previous() }}" class="btn btn-primary justify-content-end ml-auto">
                             Back
                        </a>
                    </div>

                    {{-- Card Body with Table --}}
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sent At</th>
                                    <th>Email Type</th>
                                    <th>Recipient</th>
                                    <th>Subject</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($emailLogs as $log)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($log->sent_at)->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <span
                                                class="badge 
                                            @if ($log->email_type == 'initial') badge-primary
                                            @elseif($log->email_type == 'reminder') badge-warning
                                            @else badge-info @endif">
                                                {{ ucfirst($log->email_type) }}
                                            </span>
                                        </td>

                                        <td>{{ $log->to_email }}</td>
                                        <td>{{ $log->subject }}</td>

                                        <td>
                                            @if ($log->pdf_path)
                                                <a href="{{ asset('storage/' . $log->pdf_path) }}" target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-download"></i> Download
                                                </a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Email History Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div> {{-- End Card --}}

            </div>
        </div>

    </div>

    @include('StaffingCompany.partials._footer')

</body>

</html>
