
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
                        <h4>Weekly State</h4>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('week-state.index') }}"
                           class="btn btn-success btn-xs float-right"
                        > {{ __('Staffing_Company/Week_State/crud.back') }} </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <table class="table table-bordered sort">
                            <thead>
                            <tr>
                                <th>{{ __('Staffing_Company/Week_State/crud.staff') }}</th>

                                <th>{{ __('Staffing_Company/Week_State/crud.mon') }}</th>

                                <th>{{ __('Staffing_Company/Week_State/crud.tue') }}</th>

                                <th>{{ __('Staffing_Company/Week_State/crud.wed') }}</th>

                                <th>{{ __('Staffing_Company/Week_State/crud.thu') }}</th>

                                <th>{{ __('Staffing_Company/Week_State/crud.fri') }}</th>

                                <th>{{ __('Staffing_Company/Week_State/crud.sat') }}</th>

                                <th>{{ __('Staffing_Company/Week_State/crud.sun') }}</th>

                                <th>+</th>

                                <th>{{ __('Staffing_Company/Week_State/crud.customer') }}</th>

                                <th>{{ __('Staffing_Company/Week_State/crud.cost') }}</th>

                                <th style="width: 10px;">{{ __('Staffing_Company/Week_State/crud.directing') }}</th>

                                <th>{{ __('Staffing_Company/Week_State/crud.comments') }}</th>
                            </tr>

                            </thead>

                            <tbody>
                            @foreach($week_state->weekCards as $wee_card)
                                <tr>
                                <td><p>
                                        @if($wee_card->personnel)
                                            {{$wee_card->personnel->first_name}}
                                        @endif
                                    </p>
                                </td>
                                <td><p>
                                        {{$wee_card->hours_1}}
                                    </p>
                                </td>

                                <td><p>
                                        {{$wee_card->hours_2}}
                                    </p>
                                </td>

                                <td><p>
                                        {{$wee_card->hours_3}}
                                    </p>
                                </td>

                                <td><p>
                                        {{$wee_card->hours_4}}
                                    </p>
                                </td>

                                <td><p>
                                        {{$wee_card->hours_5}}
                                    </p>
                                </td>

                                <td><p>
                                        {{$wee_card->hours_6}}
                                    </p>
                                </td>

                                <td><p>
                                        {{$wee_card->hours_7}}
                                    </p>
                                </td>

                                <td>
                                    <p>{{ $wee_card->total_hours }}</p>
                                </td>

                                <td><p>{{$wee_card->rate}}</p></td>

                                <td><p>{{$wee_card->cost}}</p></td>
                                <td>
                                    @if($wee_card->directing)
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                                <td>
                                    <p>{{$wee_card->wage}}</p>
                                </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.week_no') }}:</strong> {{$week_state->week_no}}</p>
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.invoice_no') }}:</strong> {{$week_state->invoice_no}}</p>
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.approved') }}:
                            @if($week_state->approved)
                                </strong>Yes</p>
                            @else
                                </strong>No</p>
                            @endif
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.worksheet') }}:
                                    @if($week_state->via_worksheet)
                                </strong>Yes</p>
                            @else
                                </strong>No</p>
                            @endif
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.delay_date') }}:</strong> {{$week_state->delay_date}}</p>
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.received_date') }}:</strong> {{$week_state->receive_date}}</p>
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.invoice_date') }}:</strong> {{$week_state->invoice_date}}</p>
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.comments') }}:</strong> {{$week_state->comments}}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.customer') }}:</strong> {{$project->customer->name}}</p>
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.department') }}:</strong> {{$project->department->name}}</p>
                            <p><strong>Performer:</strong> {{ $project->projectPerformer ? $project->projectPerformer->first_name : '' }}</p>
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.notes') }}:</strong>{{$project->notes}}</p>
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.approval') }}:</strong>{{$project->approval}}</p>
                            <p><strong>{{ __('Staffing_Company/Week_State/crud.internal') }}:</strong> {{$week_state->internal_notes}}</p>
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
                    @if($week_state->documents)
                        @foreach($week_state->documents as $document)
                            <tr>
                                <td>{{$document->type}}</td>
                                <td>{{$document->expiry_date}}</td>
                                <td><a href="{{ asset('storage/app/public/week_state_docs/'.$document->file) }}" target="_blank">Open File</a></td>
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
@include('StaffingCompany.partials._footer')
</body>
</html>
