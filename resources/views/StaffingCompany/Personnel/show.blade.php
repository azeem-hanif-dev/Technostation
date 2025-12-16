
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
                        <h4>Staff</h4>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url('personnels') }}"
                           class="btn btn-success btn-xs float-right"
                        > {{ __('Staffing_Company/staff/create.back') }} </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- <div class="row float-lg-right">
                        <img style="height: 100px; width: 100px;" src="{{ asset('storage/personnel_pictures/'.$personnel->picture) }}" alt="Image not found">
                    </div> --}}
                    <div class="row float-lg-right">
                        <img 
                            style="height: 110px; width: 100px; border: 3px solid #180606; border-radius: 3px; padding: 5px;" 
                            src="{{ asset('storage/personnel_pictures/'.$personnel->picture) }}" 
                            alt="Image not found"
                        >
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            @if($personnel->salutation == 1)
                                <p><strong>Salutation:</strong> Mr </p>
                            @else
                                <p><strong>Salutation:</strong> Ms </p>
                            @endif

                            @if($personnel->gender==1)
                                    <p><strong>Gender:</strong> Male </p>
                                @elseif($personnel->gender==2)
                                    <p><strong>Gender:</strong> Female </p>
                                @else
                                    <p><strong>Gender:</strong> Other </p>
                            @endif
                            <p><strong>{{ __('Staffing_Company/staff/create.initials') }}:</strong> {{$personnel->initials}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.first_name') }}:</strong> {{$personnel->first_name}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.last_name') }}:</strong> {{$personnel->last_name}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.dob') }}:</strong> {{$personnel->dob}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.ssn') }}:</strong> {{$personnel->social_security_number}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.date_service') }}:</strong> {{$personnel->date_service}}</p>
                                <p><strong>{{ __('Staffing_Company/staff/create.emp_agency') }}:</strong> {{$personnel->agency->name}}</p>
                                <p><strong>{{ __('Staffing_Company/staff/create.emp_agency_note') }}:</strong> {{$personnel->employment_agency_note}}</p>
                        </div>
                        <div class="col-md-4">
                            @if($personnel->id_type == 1)
                                <p><strong>{{ __('Staffing_Company/staff/create.id_type') }}:</strong> Passport </p>
                            @elseif($personnel->id_type == 2)
                                <p><strong>{{ __('Staffing_Company/staff/create.id_type') }}:</strong> ID card </p>
                            @elseif($personnel->id_type == 3)
                                <p><strong>{{ __('Staffing_Company/staff/create.id_type') }}:</strong> Anders </p>
                            @endif
                            <p><strong>{{ __('Staffing_Company/staff/create.id_number') }}:</strong> {{$personnel->id_number}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.expiry_date') }}:</strong> {{$personnel->id_expiry}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.nationality') }}:</strong> {{$personnel->nationality}}</p>
                                @if($personnel->vca_certificate == 1)
                                    <p><strong>{{ __('Staffing_Company/staff/create.vca') }}:</strong> Yes</p>
                                    <p><strong>{{ __('Staffing_Company/staff/create.vca_number') }}:</strong> {{$personnel->vca_number}}</p>
                                    <p><strong>{{ __('Staffing_Company/staff/create.vca_expiry') }}:</strong> {{$personnel->vca_expiry}}</p>
                                @else
                                    <p><strong>{{ __('Staffing_Company/staff/create.vca') }}:</strong> No</p>
                                @endif

                                @if($personnel->own_car == 1)
                                    <p><strong>{{ __('Staffing_Company/staff/create.own_car') }}:</strong> Yes</p>
                                @else
                                    <p><strong>{{ __('Staffing_Company/staff/create.own_car') }}:</strong> No</p>
                                @endif

                                @if($personnel->active == 1)
                                    <p><strong>{{ __('Staffing_Company/staff/create.active') }}:</strong> Yes</p>
                                @else
                                    <p><strong>{{ __('Staffing_Company/staff/create.active') }}:</strong> No</p>
                                @endif
                                <p><strong>{{ __('Staffing_Company/staff/create.rate') }}3:</strong> {{$personnel->rate_per_hour}}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>{{ __('Staffing_Company/staff/create.mobile') }}:</strong> {{$personnel->mobile}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.mobile') }}2:</strong> {{$personnel->mobile2}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.mobile') }}3:</strong> {{$personnel->mobile3}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.email') }}:</strong> {{$personnel->email}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.telephone') }}:</strong> {{$personnel->telephone}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.address') }}:</strong> {{$personnel->address}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.post_code') }}:</strong> {{$personnel->postcode}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.city') }}:</strong> {{$personnel->city}}</p>
                            <p><strong>{{ __('Staffing_Company/staff/create.cost') }}:</strong> {{$personnel->cost_per_hour}}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>{{ __('Staffing_Company/staff/create.suitable_for') }}:</strong>
                            @foreach($personnel->employeeFunction as $function)
                                <p>{{ $function->name }}</p>
                            @endforeach
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
                    @foreach($personnel->documents as $document)
                        <tr>
                            <td>{{$document->type}}</td>
                            <td>{{$document->expiry_date}}</td>
                            <td><a href="{{ asset('storage/app/public/personnel_docs/'.$document->file) }}" target="_blank">Open File</a></td>
                        </tr>
                    @endforeach
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
