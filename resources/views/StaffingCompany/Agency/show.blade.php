<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div class="row">
            <div class="card col-md-11">
                <div class="card-header row">
                    <div class="col-md-6">
                        <h4>Customer</h4>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url('employment-agencies') }}"
                           class="btn btn-success btn-xs float-right"
                        > {{ __('Staffing_Company/staff/create.back') }} </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>{{ __('Staffing_Company/Customer/crud.name') }}:</strong> {{$agency->name}}</p>
                            <p><strong>{{ __('Staffing_Company/Customer/crud.email') }}:</strong> {{$agency->email}}</p>
                            <p><strong>{{ __('Staffing_Company/Customer/crud.contact') }}:</strong> {{$agency->contact_person}}</p>
                            <p><strong>{{ __('Staffing_Company/Customer/crud.phone') }}:</strong> {{ _formatPhoneNumber($agency->phone) }}</p>
                            <p><strong>URL:</strong> {{$agency->url}}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>{{ __('Staffing_Company/Customer/crud.address') }}:</strong> {{$agency->address}}</p>
                            <p><strong>{{ __('Staffing_Company/Customer/crud.city') }}:</strong> {{$agency->city}}</p>
                            <p><strong>{{ __('Staffing_Company/Customer/crud.country') }}:</strong> {{$agency->country}}</p>
                            <p><strong>Postcode:</strong> {{$agency->zipcode}}</p>
                        </div>
                        <div class="col-md-12">
                            <p><strong>Notes:</strong> {{$agency->notes}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-11">
                <table class="table table-bordered table-striped ml-2">
                    <thead>
                    <tr>
                        <th>Document Type</th>
                        <th>Document Expiry</th>
                        <th>File</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($agency->documents)
                        @foreach($agency->documents as $document)
                            <tr>
                                <td>{{$document->type}}</td>
                                <td>{{$document->expiry_date}}</td>
                                <td><a href="{{ asset('storage/app/public/employment_agency_docs/'.$document->file) }}" target="_blank">Open File</a></td>
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
