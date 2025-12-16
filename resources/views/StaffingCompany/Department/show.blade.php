<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')

        @include('StaffingCompany.partials._sideBar')
        <div class="content-wrapper">
            <div id="myapp">
                <show-department :translations="{{ json_encode($translations) }}"
                    :department='@json($id)'></show-department>
            </div>
            <div class="row">
                <div class="col-md-11">
                    <table class="table table-bordered table-striped ml-4">
                        <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>Document Expiry</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($department->documents)
                                @foreach ($department->documents as $document)
                                    <tr>
                                        <td>{{ $document->type }}</td>
                                        <td>{{ $document->expiry_date }}</td>
                                        {{-- <td><a href="{{ asset('storage/app/department_docs/'.$document->file) }}" target="_blank">Open File</a></td> --}}
                                        <td><a href="{{ asset('storage/department_docs/' . $document->file) }}"
                                                target="_blank">Open File</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td>
                                    Not found
                                </td>
                                <td>
                                    Not found
                                </td>
                                <td>
                                    Not found
                                </td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @include('StaffingCompany.partials._footer')
</body>

</html>
