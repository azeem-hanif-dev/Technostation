@extends('Company_Admin.layouts.main')

@section('outer_css')
  <link href="{{asset('/multiple-select/multiple-select.css')}}" rel="stylesheet"/>
@endsection

@section('title', 'Dashboard')

<div id="wrapper">
  @section('content')
    <div class="row">
      <div class="col-sm-8">
        <h1>@lang('common.Staff Management')</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <a class="btn btn-primary btn-md pull-right" href="{{route('staff.index')}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('Company_Admin/dashboard.Back')</a>
        <div class="panel panel-info">

          <div class="panel-heading">
            @lang('common.Staff details')
          </div>
          <!-- /.panel-heading -->
          <div class="panel-body">
            <div class="col-md-8 col-md-offset-4">
              <div class="inline">
                <label for="name">@lang('Company_Admin/dashboard.Name'):</label>
                <span style="position: absolute; left: 150px;">{{$user->name}}</span>
              </div>

              <br>
              <div class="inline">
                <label for="name">@lang('common.Associated agency'):</label>
                <span style="position: absolute; left: 150px;">{{ ($user->employment_agency_id) ? $user->agency->name: $user->companyName['name'] }}</span>
              </div>
              <br>
              <div class="inline">
                <label for="name">@lang('common.Worker type'):</label>
                <span style="position: absolute; left: 150px;">{{ ($user->worker_type_id) ? $user->worker_type->name: ''}}</span>
              </div>
              <br>
              <div class="inline">
                <label for="name">@lang('Company_Admin/dashboard.Role'):</label>
                <span style="position: absolute; left: 150px;">{{$user->role->name}}</span>
              </div>
              <br>
              <div class="inline">
                <label for="name">@lang('Company_Admin/dashboard.Email'):</label>
                <span style="position: absolute; left: 150px;">{{$user->email}}</span>
              </div>
              <br>
              {{--                    <div class="inline">--}}
              {{--                      <label for="name">@lang('Company_Admin/dashboard.Address'):</label>--}}
              {{--                      <span style="position: absolute; left: 150px;">{{$user->address}}</span>--}}
              {{--                    </div>--}}
              <br>
              {{--                    <div class="inline">--}}
              {{--                      <label for="name">@lang('Company_Admin/dashboard.Phone'):</label>--}}
              {{--                      <span style="position: absolute; left: 150px;">{{$user->phone}}</span>--}}
              {{--                    </div>--}}
              <br>
              {{--                    <div class="inline">--}}
              {{--                      <label for="name">@lang('Company_Admin/dashboard.City'):</label>--}}
              {{--                      <span style="position: absolute; left: 150px;">{{$user->city}}</span>--}}
              {{--                    </div>--}}
            <!-- <br>
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.Zip Code'):</label>
                      <span style="position: absolute; left: 150px;">{{$user->zipcode}}</span>
                    </div> -->
              {{--                    <br>--}}
              {{--                    <br>--}}
              <div class="inline">
                @if(Auth::user()->companyAllowedSickLeaves(Auth::user()->id))
                  <label for="name">@lang('Company_Admin/dashboard.Permissions Assigned'):</label>
                  <ul style="padding-left:150px; list-style-type:square">
                    @foreach ($user->permissions as $permission)
                      <li> <i>{{$permission->name}}</i> </li>
                    @endforeach
                  </ul>
                @endif
              </div>

            </div>
          </div>
          <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
      </div>
      <!-- /.col-lg-12 -->
    </div>

  @endsection
</div>


<!-- Content Header (Page header) -->
