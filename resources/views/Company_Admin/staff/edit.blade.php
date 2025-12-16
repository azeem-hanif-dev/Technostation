@extends('Company_Admin.layouts.main')

@section('outer_css')
  <link href="{{asset('/select2/dist/css/select2.min.css')}}" rel="stylesheet" />
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
        <div class="panel panel-default">
          <div class="panel-heading">
            @lang('common.Edit staff')
          </div>
          <div class="panel-body">
            {{ Form::model($user,['route' => ['staff.update',$user->id], 'method' => 'PUT', 'data-parsley-validate' => '']) }}
            <input type="hidden" id="language" value="{{App::getLocale()}}">
            <div class="form-group row">
              <label class="col-md-2" for=""> @lang('Company_Admin/dashboard.Name'):*</label>
              <div class="col-md-8">
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => '', 'maxlength' => '255']) }}
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Email'):*</label>
              <div class="col-md-8">
                {{ Form::email('email', null, ['class' => 'form-control', 'required' => '', 'maxlength' => '255']) }}
              </div>
            </div>
            {{--                    <div class="form-group row">--}}
            {{--                      <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Phone'):*</label>--}}
            {{--                      <div class="col-md-8">--}}
            {{--                        {{ Form::text('phone', null, ['class' => 'form-control', 'required' => '', 'maxlength' => '255']) }}--}}
            {{--                      </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="form-group row">--}}
            {{--                      <div class="col-md-4">--}}
            {{--                        <label class="col-md-4" for="">@lang('Company_Admin/dashboard.Post code'):*</label>--}}
            {{--                        <div class="col-md-6 pull-right">--}}
            {{--                          <input class="form-control" id="postCode" name="postCode"--}}
            {{--                          style="padding-left: 18px;" type="text"--}}
            {{--                          required value="{{$user->postcode}}" readonly="true">--}}
            {{--                        </div>--}}
            {{--                      </div>--}}
            {{--                      <div class="col-md-4" style="">--}}
            {{--                        <label class="col-md-6" for="">@lang('common.House Number'):*</label>--}}
            {{--                        <div class="col-md-6">--}}
            {{--                          <input class="form-control" id="houseNumber" name="houseNumber" type="text"--}}
            {{--                          value="{{$user->houseNumber}}" readonly="true">--}}

            {{--                        </div>--}}
            {{--                      </div>--}}
            {{--                      <div class="col-md-2" style="">--}}
            {{--                        <button class="btn btn-danger btn-md" type="button" name="button" onclick="editPostcode()"><i class="fa fa-edit"></i></button>--}}
            {{--                        <button class="btn btn-success btn-md" id="getAddres" type="button" name="button" onclick="getAddress()" disabled="true"><i class="fa fa-arrow-circle-right"></i></button>--}}
            {{--                      </div>--}}
            {{--                    </div>--}}

            {{--                    <div class="form-group row">--}}
            {{--                      <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Address'):*</label>--}}
            {{--                      <div class="col-md-8">--}}
            {{--                        {{ Form::text('address', null, ['class' => 'form-control', 'id' => 'address', 'required' => '','readonly'=>true, 'maxlength' => '255']) }}--}}
            {{--                      </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="form-group row">--}}
            {{--                      <label class="col-md-2" for="">@lang('Company_Admin/dashboard.City'):*</label>--}}
            {{--                      <div class="col-md-8">--}}
            {{--                        {{ Form::text('city', null, ['class' => 'form-control','id' => 'city', 'required' => '','readonly'=>true, 'maxlength' => '255']) }}--}}
            {{--                      </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="form-group row">--}}
            {{--                      <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Country'):*</label>--}}
            {{--                      <div class="col-md-8">--}}
            {{--                        {{ Form::text('country', null, ['class' => 'form-control', 'required' => '', 'maxlength' => '255']) }}--}}
            {{--                      </div>--}}
            {{--                    </div>--}}
            <div class="form-group row">
              <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Role') :*</label>
              <div class="col-md-8">
                <select class="form-control" name="role" onchange="hideShowDiv(this)">
                  @foreach($roles as $key=>$role)
                    <option value="{{$role}}" {{ ($user->role_id == $key)? 'selected':'' }}>{{$role}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row"  id="supervisors"
                 @if($user->role->name == 'supervisor' || $user->role->name == 'manager')
                 style="display:none"
                    @endif
            >
              <label class="col-md-2" for="">@lang('Company_Admin/dashboard.report_to'):*</label>
              <div class="col-md-8">
                <select class="form-control" name="supervisor_id" id="sup-select">
                  @foreach($supervisors as $key=>$supervisor)
                    <option value="{{$key}}" {{ ($user->reports_to_id == $key)? 'selected':'' }}>{{$supervisor}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row"  id="managers"
                 @if($user->role->name == 'user' || $user->role->name == 'manager')
                 style="display:none"
                    @endif
            >
              <label class="col-md-2" for="">@lang('Company_Admin/dashboard.report_to'):*</label>
              <div class="col-md-8">
                <select class="form-control" name="manager_id" id="man-select">
                  @foreach($managers as $key=>$manager)
                    <option value="{{$key}}" {{ ($user->reports_to_id == $key)? 'selected':'' }}>{{$manager}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row" id="agency"
                 @if($user->role->name == 'supervisor' || $user->role->name == 'manager')
                 style="display:none"
                    @endif
            >
              <div class="inline">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Associated') @lang('Company_Admin/dashboard.Agency'):</label>
                <div class="col-md-8">
                  {{ Form::select('agency_id', $agencies, $user->employment_agency_id ? $user->employment_agency_id : null, ['class' => 'form-control','id' => 'multi-select2', 'placeholder' => 'Select Agency...'])}}
                </div>
              </div>
            </div>
            <div class="form-group row" id="workerType"
                 @if($user->role->name == 'supervisor' || $user->role->name == 'manager')
                 style="display:none"
                    @endif
            >
              <div class="inline">
                <label class="col-md-2" for="">@lang('common.Worker type'):*</label>
                <div class="col-md-8">
                  {{ Form::select('worker_type_id', $types, $user->worker_type_id ? $user->worker_type_id : null, ['class' => 'form-control','id' => 'multi-select1', 'placeholder' => 'Select Type...'])}}
                </div>
              </div>
            </div>
            <br>
            <div class="form-group row" id="permissions"
                 @if($user->role->name == 'user' || $user->role->name == 'manager')
                 style="display:none"
                    @endif
            >
              @if(Auth::user()->companyAllowedSickLeaves(Auth::user()->id))
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Permissions'):*</label>
                <div class="col-md-8">
                  @foreach ($status as $key => $permission)
                    @if($permission['before'])
                      <div class="inline">
                        {{ Form::checkbox('Permission'.$key, $permission['id'], true) }}
                        <?php $name = $permission['name']; ?>
                        <label for="description">@lang("Company_Admin/dashboard.$name")</label>
                      </div>
                    @else
                      <div class="inline">
                        {{ Form::checkbox('Permission'.$key, $permission['id']) }}
                        <?php $name = $permission['name']; ?>
                        <label for="description">@lang("Company_Admin/dashboard.$name")</label>
                      </div>
                    @endif
                  @endforeach
                </div>
              @endif
            </div>
            <div class="form-group row">
              <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Password'):*</label>
              <div class="col-md-8">
                <div class="form-group input-group">
                  <input type="password" id="pass" class="form-control" name="password" value="">
                  <span class="input-group-addon"> <button type="button" name="button" onclick="changeMode()"><i class="fa fa-eye"></i></button></span>
                </div>
              </div>
            </div>
            <div class="inline pull-right">
              <button type="submit" class="btn btn-success">@lang('Company_Admin/dashboard.Save')</button>
              <a href="{{ route('staff.index') }}" type="button" class="btn btn-danger">@lang('Company_Admin/dashboard.Cancel')</a>
            </div>
            {{ Form::close() }}
          </div>
        </div>
      </div>
    </div>
  @endsection
</div>

@section('outer_script')
  <script src="{{asset('/select2/dist/js/select2.min.js')}}"></script>
  <script src="{{asset('/js/staff/editJquery.js')}}"></script>
@endsection
