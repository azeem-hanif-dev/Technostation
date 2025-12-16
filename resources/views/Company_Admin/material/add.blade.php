@extends('Company_Admin.layouts.main')

@section('outer_css')
  <link href="{{asset('select2/dist/css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('title', 'Dashboard')

  <div id="wrapper">
    @section('content')
    <div class="row">
      <div class="col-sm-8">
        <h1>@lang('Company_Admin/dashboard.Materials') @lang('Company_Admin/dashboard.Management')</h1>
      </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('Company_Admin/dashboard.Add') @lang('Company_Admin/dashboard.New') @lang('Company_Admin/dashboard.Material')
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                  <div class="">
                    {{ Form::open(['route' => 'material.store', 'method' => 'POST', 'data-parsley-validate' => '']) }}

                      <div class="form-group row">
                        <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Material') @lang('Company_Admin/dashboard.Name'):*</label>
                        <!-- {{ Form::label('name', 'Material Name:*', ['class' => 'col-md-2 col-form-label']) }} -->
                          <div class="col-md-8">
                            {{ Form::text('name', null, ['class' => 'form-control', 'required' => '', 'maxlength' => '255']) }}
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Price'):*</label>
                          <!-- {{ Form::label('price', 'Price:*', ['class' => 'col-md-2 col-form-label']) }} -->
                            <div class="col-md-8">
                              {{ Form::text('price', null, ['class' => 'form-control', 'required' => '', 'maxlength' => '255']) }}
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Suppliers'):*</label>
                              <div class="col-md-8">
                                <select class="form-control js-example-basic-multiple" name="suppliers[]" multiple="multiple">
                                  @foreach ($suppliers as $key => $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>



                        <div class="inline pull-right">
                          <button type="submit" class="btn btn-success">@lang('Company_Admin/dashboard.Add')</button>
                          <a href="{{ route('material.index') }}" type="button" class="btn btn-danger">@lang('Company_Admin/dashboard.Cancel')</a>
                        </div>

                    {{ Form::close() }}
                  </div>

                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>

    @endsection
  </div>

  @section('outer_script')
  <script src="{{asset('select2/dist/js/select2.min.js')}}"></script>
  <script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
  </script>
  @endsection

<!-- Content Header (Page header) -->
