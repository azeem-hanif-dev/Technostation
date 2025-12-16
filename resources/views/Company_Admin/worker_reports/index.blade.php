<head>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css"  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

</head>

@extends('Company_Admin.layouts.main')




@section('outer_css')
    <style media="screen">
        /* table{
            table-layout: fixed;
            width: 100px;
          } */
    </style>
@endsection

@section('title', 'Dashboard')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>


<div id="wrapper">

    @section('content')
        {{--        @if($errors->any())--}}
        {{--            <div>--}}
        {{--                <ul>--}}
        {{--                    @foreach($errors->all() as $e)--}}
        {{--                        <li>{{$e}}</li>--}}
        {{--                    @endforeach--}}
        {{--                </ul>--}}

        {{--            </div>--}}
        {{--        @endif--}}

        <div class="row">
            <div class="col-sm-8">
                <h1 >@lang('common.Worker report') </h1>



            </div>
            {{--      <div class="col-md-4 text-right">--}}
            {{--        <a href="{{route('project.create')}}", class="btn btn-primary btn-sm" style="margin-top: 30px; margin-left: 15px;"><i class="fa fa-plus" aria-hidden="true"></i> @lang('Company_Admin/dashboard.Add New')</a>--}}
            {{--      </div>--}}
        </div>
        @if(session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row" id="app">

            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <p>@lang('common.Worker report') <span> <button style=" float: right;" type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal">Summary Report</button></span></p>
                    </div>
<div>


</div>
                    <form action="{{ url('getWorkerTaskDetails') }}" method="post">
                        @csrf

                        <div class="row">
                            <br>
                            <div class="col-md-3" >

                                <label for="worker" style="margin-left: 15px;">@lang('Company_Admin/dashboard.Choose a worker'):</label>
                                {{--                                                <label for="worker">choose a worker:</label>--}}

                                <select name="worker_id"  class="form-control"  style="margin-left: 15px; width: 200px;" id="worker">
                                    <option  selected disabled>Worker</option>
                                    @foreach($worker as $work)
                                        <option value="{{$work->id}}" >{{$work->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2" >
                                <label for="worker">@lang('Company_Admin/dashboard.Choose a project'):</label>
                                <select name="project_id" class="form-control"  id="project">
                                    <option  selected disabled>project</option>
                                    @foreach($project as $pro)
                                        <option value="{{$pro->id}}" >{{$pro->name}}</option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="col-md-2">
                                <label for="StartTime">@lang('Company_Admin/dashboard.Start Date'):</label><br>
                                <input type="date" id="StartDate" required class="form-control" style="width: 160px;" value="{{old('StartDate')}}" name="StartDate">
                                {{--                                                                @error(StartDate)--}}
                                {{--                                                                <p>{{$message}}</p>--}}
                                {{--                                                                @enderror--}}
                            </div>



                            <div class="col-md-2">
                                <label for="EndTime">@lang('Company_Admin/dashboard.End Date'):</label><br>
                                <input type="date" class="form-control" required id="EndDate" style="width: 160px;" value="{{old('EndDate')}}" name="EndDate">

                            </div>

                            <div class="col-md-1">
                                <br>
                                <input type="submit" class="form-control btn btn-primary" style="width: 80px; margin-top: 4px;" >


                            </div>
                        </div>

                    </form>

                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <table width="100%" id="table" class="table table-bordered table-striped table-hover">

                            <thead>
                            <tr>
                                {{--                                                                <th>@lang('Company_Admin/dashboard.Sr #')</th>--}}
                                {{--                                                                <th>@lang('Company_Admin/dashboard.Name')</th>--}}
                                {{--                                                                <th>@lang('Company_Admin/dashboard.Customer name')</th>--}}
                                {{--                                                                <th>@lang('Company_Admin/dashboard.Status')</th>--}}
                                {{--                                                                <th>@lang('Company_Admin/dashboard.Start Date')</th>--}}
                                {{--                                                                <th>@lang('Company_Admin/dashboard.End Date')</th>--}}
                                {{--                                                                <th data-orderable="false"></th>--}}

                                <th>@lang('Company_Admin/dashboard.Worker Name')</th>
                                <th>@lang('Company_Admin/dashboard.Project name')</th>
                                <th>@lang('Company_Admin/dashboard.Task Name')</th>
                                <th>@lang('Company_Admin/dashboard.Start Time')</th>
                                <th>@lang('Company_Admin/dashboard.End Time')</th>
                                <th>@lang('Company_Admin/dashboard.Total Time')</th>
                                <th>@lang('Company_Admin/dashboard.Location')</th>


                                {{--                                <th></th>--}}
                                {{--                                <th>@lang('Company_Admin/dashboard.Status')</th>--}}
                                {{--                                <th>@lang('Company_Admin/dashboard.Start Date')</th>--}}
                                {{--                                <th>@lang('Company_Admin/dashboard.End Date')</th>--}}
                                {{--                                  <th data-orderable="false"></th>--}}
                            </tr>
                            </thead>



                            <tbody>
                            @if(count($worker_detail) > 0 )
                                @foreach($worker_detail as $detail)
                                    <tr>

                                        <td>{{ $detail->worker_name }}</td>
                                        <td>{{ $detail->project_name }}</td>
                                        <td>{{ $detail->tasks_name }}</td>

                                        {{--                                  <td>{{ date("H:i:s a", strtotime($detail->check_in_time)) }}</td>--}}
                                        @if($detail->check_in_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($detail->check_in_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if($detail->check_out_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($detail->check_out_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $detail->total_time }}</td>
                                        <td>{{ $detail->location_name }}</td>

                                        {{--                                  <td></td>--}}
                                    </tr>
                                @endforeach


                            @elseif(count($project_detail) > 0)

                                @foreach($project_detail as $details)
                                    <tr>

                                        <td>{{ $details->worker_name }}</td>
                                        <td>{{ $details->project_name }}</td>
                                        <td>{{ $details->task_name }}</td>
                                        {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
                                        @if($details->check_in_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if($details->check_out_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $details->total_time }}</td>
                                        <td>{{ $details->locations_name }}</td>


                                        {{--                                  <td></td>--}}
                                    </tr>
                                @endforeach

                            @elseif(count($worker_detailss) > 0)

                                @foreach($worker_detailss as $details)
                                    <tr>

                                        <td>{{ $details->worker_name }}</td>
                                        <td>{{ $details->project_name }}</td>
                                        <td>{{ $details->taskss_name }}</td>
                                        {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
                                        @if($details->check_in_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if($details->check_out_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $details->total_time }}</td>
                                        <td>{{ $details->locations_name }}</td>


                                        {{--                                  <td></td>--}}
                                    </tr>
                                @endforeach



                            @elseif(count($worker_project) > 0)

                                @foreach($worker_project as $details)
                                    <tr>

                                        <td>{{ $details->worker_name }}</td>
                                        <td>{{ $details->project_name }}</td>
                                        <td>{{ $details->taskss_name }}</td>
                                        {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
                                        @if($details->check_in_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if($details->check_out_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $details->total_time }}</td>
                                        <td>{{ $details->locations_name }}</td>


                                        {{--                                  <td></td>--}}
                                    </tr>
                                @endforeach





                            @elseif(count($project_start_end) > 0)

                                @foreach($project_start_end as $details)
                                    <tr>

                                        <td>{{ $details->worker_name }}</td>
                                        <td>{{ $details->project_name }}</td>
                                        <td>{{ $details->taskss_name }}</td>
                                        {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
                                        @if($details->check_in_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if($details->check_out_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $details->total_time }}</td>
                                        <td>{{ $details->locations_name }}</td>


                                        {{--                                  <td></td>--}}
                                    </tr>
                                @endforeach





                            @elseif(count($start_end) > 0)

                                @foreach($start_end as $details)
                                    <tr>

                                        <td>{{ $details->worker_name }}</td>
                                        <td>{{ $details->project_name }}</td>
                                        <td>{{ $details->taskss_name }}</td>
                                        {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
                                        @if($details->check_in_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if($details->check_out_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $details->total_time }}</td>
                                        <td>{{ $details->locations_name }}</td>


                                        {{--                                  <td></td>--}}
                                    </tr>
                                @endforeach



                            @elseif(count($all_joins) > 0)

                                @foreach($all_joins as $details)
                                    <tr>

                                        <td>{{ $details->worker_name }}</td>
                                        <td>{{ $details->project_name }}</td>
                                        <td>{{ $details->taskss_name }}</td>
                                        {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
                                        @if($details->check_in_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if($details->check_out_time!==null)
                                            <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $details->total_time }}</td>
                                        <td>{{ $details->locations_name }}</td>


                                        {{--                                  <td></td>--}}
                                    </tr>
                                @endforeach

                            @else

                            @endif




                            {{--                        @foreach($worker as $work)--}}
                            {{--                            <tr >--}}
                            {{--                                <td> {{$work->name}} </td>--}}
                            {{--                                <td> {{$work->name}} </td>--}}
                            {{--                                <td> {{$work->name}} </td>--}}
                            {{--                            </tr>--}}

                            {{--                            @endforeach--}}

                            {{--                          @foreach ($projects as $key => $project)--}}
                            {{--                            <tr>--}}
                            {{--                                <td>{{ ++$key}}</td>--}}
                            {{--                                <td>{{ $project->name }}</td>--}}
                            {{--                                <td>{{ $project->customer->name }}</td>--}}
                            {{--                                <td>{{ $project->status }}</td>--}}
                            {{--                                <td>{{ date('M j, Y', strtotime($project->start_date)) }}</td>--}}
                            {{--                                <td>{{ date('M j, Y', strtotime($project->end_date)) }}</td>--}}
                            {{--                                <td style="text-align:center;">--}}
                            {{--                                    <a href="{{route('project.show',$project->id)}}"  data-toggle="tooltip" data-placement="top" title="@lang('Company_Admin/dashboard.View') @lang('Company_Admin/dashboard.Details') "><i class="fa fa-eye view-icon" style="color:blue;"></i></a>--}}

                            {{--                                    <a href="{{route('project.edit',$project->id)}}" data-toggle="tooltip" data-placement="top" title="@lang('Company_Admin/dashboard.Edit')"><i class="fa fa-pencil-square-o" style="color:green;"></i></a>--}}

                            {{--                                    <a href="{{route('project.delete',$project->id)}}" data-toggle="tooltip" data-placement="top" title="@lang('Company_Admin/dashboard.Delete')" onclick="return confirm('Delete Project, Are you sure ?')"><i class="fa fa-times" style="color:red;"></i></a>--}}
                            {{--                                </td>--}}
                            {{--                            </tr>--}}
                            {{--                          @endforeach--}}
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Summary Report</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel-body">

                            <table width="100%" id="tables" class="table table-bordered table-striped table-hover">

                                <thead>
                                <tr>
                                    {{--                                    <th data-orderable="false"></th>--}}

                                    <th>@lang('Company_Admin/dashboard.Worker Name')</th>
                                    <th>@lang('Company_Admin/dashboard.Project name')</th>
                                    <th>@lang('Company_Admin/dashboard.Task Name')</th>
{{--                                    <th>@lang('Company_Admin/dashboard.Start Time')</th>--}}
{{--                                    <th>@lang('Company_Admin/dashboard.End Time')</th>--}}
                                    <th>@lang('Company_Admin/dashboard.Total Time')</th>
                                    <th>@lang('Company_Admin/dashboard.Location')</th>


                                    {{--                                <th></th>--}}
                                    {{--                                <th>@lang('Company_Admin/dashboard.Status')</th>--}}
                                    {{--                                <th>@lang('Company_Admin/dashboard.Start Date')</th>--}}
                                    {{--                                <th>@lang('Company_Admin/dashboard.End Date')</th>--}}
                                    {{--                                  <th data-orderable="false"></th>--}}
                                </tr>
                                </thead>



                                <tbody>
                                @if(count($worker_detail) > 0 )

                                      @foreach($worker_detail as $detail)
                                        <tr>

                                            <td>{{ $detail->worker_name }}</td>
                                            <td>{{ $detail->project_name }}</td>
                                            <td>{{ $detail->tasks_name }}</td>

                                            {{--                                  <td>{{ date("H:i:s a", strtotime($detail->check_in_time)) }}</td>--}}
{{--                                            @if($detail->check_in_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($detail->check_in_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
{{--                                            @if($detail->check_out_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($detail->check_out_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
                                            <td>{{ $detail->total_time }}</td>
                                            <td>{{ $detail->location_name }}</td>

                                            {{--                                  <td></td>--}}
                                        </tr>
                                    @endforeach


                                @elseif(count($project_detail) > 0)

                                    @foreach($project_detail as $details)
                                        <tr>

                                            <td>{{ $details->worker_name }}</td>
                                            <td>{{ $details->project_name }}</td>
                                            <td>{{ $details->task_name }}</td>
                                            {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
{{--                                            @if($details->check_in_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
{{--                                            @if($details->check_out_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
                                            <td>{{ $details->total_time }}</td>
                                            <td>{{ $details->locations_name }}</td>


                                            {{--                                  <td></td>--}}
                                        </tr>
                                    @endforeach

                                @elseif(count($worker_detailss) > 0)

                                    @foreach($worker_detailss as $details)
                                        <tr>

                                            <td>{{ $details->worker_name }}</td>
                                            <td>{{ $details->project_name }}</td>
                                            <td>{{ $details->taskss_name }}</td>
                                            {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
{{--                                            @if($details->check_in_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
{{--                                            @if($details->check_out_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
                                            <td>{{ $details->total_time }}</td>
                                            <td>{{ $details->locations_name }}</td>


{{--                                                                              <td></td>--}}
                                        </tr>

                                    @endforeach

{{--                                    <tr>--}}
{{--                                        <td colspan="" style="text-align: right;">@lang('Company_Admin/dashboard.Total Time')</td>--}}

{{--                                        <td colspan="">{{ array_sum(1)}}</td>--}}


{{--                                    </tr>--}}




                                @elseif(count($worker_project) > 0)

                                    @foreach($worker_project as $details)
                                        <tr>

                                            <td>{{ $details->worker_name }}</td>
                                            <td>{{ $details->project_name }}</td>
                                            <td>{{ $details->taskss_name }}</td>
                                            {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
{{--                                            @if($details->check_in_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
{{--                                            @if($details->check_out_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
                                            <td>{{ $details->total_time }}</td>
                                            <td>{{ $details->locations_name }}</td>


                                            {{--                                  <td></td>--}}
                                        </tr>
                                    @endforeach





                                @elseif(count($project_start_end) > 0)

                                    @foreach($project_start_end as $details)
                                        <tr>

                                            <td>{{ $details->worker_name }}</td>
                                            <td>{{ $details->project_name }}</td>
                                            <td>{{ $details->taskss_name }}</td>
                                            {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
{{--                                            @if($details->check_in_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
{{--                                            @if($details->check_out_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
                                            <td>{{ $details->total_time }}</td>
                                            <td>{{ $details->locations_name }}</td>




                                            {{--                                  <td></td>--}}
                                        </tr>
                                    @endforeach





                                @elseif(count($start_end) > 0)

                                    @foreach($start_end as $details)
                                        <tr>

                                            <td>{{ $details->worker_name }}</td>
                                            <td>{{ $details->project_name }}</td>
                                            <td>{{ $details->taskss_name }}</td>
                                            {{--                                        <td>{{ date("H:i:s a", strtotime($details->check_in_time)) }}</td>--}}
{{--                                            @if($details->check_in_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($details->check_in_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
{{--                                            @if($details->check_out_time!==null)--}}
{{--                                                <td>{{ date("H:i:s a", strtotime($details->check_out_time))}}</td>--}}
{{--                                            @else--}}
{{--                                                <td></td>--}}
{{--                                            @endif--}}
                                            <td>{{ $details->total_time }}</td>
                                            <td>{{ $details->locations_name }}</td>




                                            {{--                                  <td></td>--}}
                                        </tr>
                                    @endforeach



                                @elseif(count($summary_report) > 0)


                                    @foreach($summary_report as $details)



                                        <tr>
                                            <td>{{ $details->worker_name }}</td>
                                            <td>{{ $details->project_name }}</td>
                                            <td>{{$details->taskss_name}} </td>


{{--                                            <td>{{$data}}</td>--}}

{{--                                            <td>{{ $details->taskss_name }}</td>--}}

                                            <td>{{ $details->total_time }}</td>
                                            <td>{{ $details->locations_name }}</td>

                                        </tr>


                                    @endforeach


                                @else

                                @endif



                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</div>



@section('outer_script')
{{--    <script src="{{asset('js/vue.min.js')}}"></script>--}}
{{--    <script src="{{asset('select2/dist/js/select2.min.js')}}"></script>--}}
{{--    <script src="{{asset('js/lodash.min.js')}}"></script>--}}
{{--    <script src="{{asset('js/axios.min.js')}}"></script>--}}
{{--    <script src="{{asset('js/vue-select-latest.js')}}"></script>--}}
{{--    <script src="{{asset('js/vue.min.js')}}"></script>--}}
{{--    <script src="https://unpkg.com/vue-swal"></script>--}}




    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    {{--    <script src="{{asset('vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>--}}
    {{--    <script src="{{asset('vendor/datatables-responsive/dataTables.responsive.js')}}"></script>--}}
    {{--    <script src="{{asset('dist2/js/sb-admin-2.js')}}"></script>--}}
    <script src="{{asset('js/worker_report.js')}}"></script>


    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>


    <script>

        $(document).ready(function(){
            $("#asad").click(function(e){



            });
        });
        ///////////////// Below block is used to localize DataTable data //////////////
        let languageSelected = document.getElementById('languageSwitcher').value;
        function getWorkerData(){

        }
        if(languageSelected == 'en') {
            $(document).ready(function () {
                $('#table,#tables').DataTable({
                    responsive: true,


                    dom: '<"html5buttons"B>lTfgitp',
                    order: [0, 'desc'],
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        // 'csvHtml5',
                        'pdfHtml5',
                        'print',

                    ]

                });

            });

        }
        else {
            $(function () {
                $('#table,#tables').DataTable({
                    dom: '<"html5buttons"B>lTfgitp',
                    order: [0, 'desc'],
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        // 'csvHtml5',
                        'pdfHtml5',
                        'print',

                    ],


                    language: {

                        WorkerReport :"Uren overzicht",
                        ListOfWorker: "Remove it",
                        ChooseAWorker:"Selecteer de medewerker",
                        Worker : "Medewerker",
                        ChooseAProject: "Slecteer de project",
                        WorkerName: "Naam medewerker",
                        ProjectName: "Naam project",
                        TaskName: "Taak",
                        Start: "Start",
                        Date: "Datum",
                        Time: "Tijd",




                        sProcessing: "Bezig...",
                        sLengthMenu: "Laten zien _MENU_ entries",
                        sZeroRecords: "Geen resultaten gevonden",
                        sInfo: "_START_ tot _END_ van _TOTAL_ resultaten",
                        sInfoEmpty: "Geen resultaten om weer te geven",
                        sInfoFiltered: " (gefilterd uit _MAX_ resultaten)",
                        sInfoPostFix: "",
                        sSearch: "Zoeken:",
                        sEmptyTable: "Geen resultaten aanwezig in de tabel",
                        sInfoThousands: ".",
                        sLoadingRecords: "Een moment geduld aub - bezig met laden...",
                        oPaginate: {
                            sFirst: "Eerste",
                            sLast: "Laatste",
                            sNext: "Volgende",
                            sPrevious: "Vorige"
                        },
                        oAria: {
                            sSortAscending: ": activeer om kolom oplopend te sorteren",
                            sSortDescending: ": activeer om kolom aflopend te sorteren"
                        }
                    }


                })
            })
        }
        /////////////////////////////////////////////////////////////////////////
    </script>
@endsection
