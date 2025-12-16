@extends('Company_Admin.layouts.main')

@section('outer_css')

<style src="{{asset('select2/dist/css/select2.min.css')}}"></style>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<link rel="stylesheet" href="https://unpkg.com/vue-select@3.0.0/dist/vue-select.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
<style>
  table{
    border: 1px solid;
  }
  tr{
    border: 1px solid;
  }
  td {
    border: 1px solid;
    text-align: center;
  }
  th {
    border: 1px solid;
    border-bottom: 1px solid;
  }
  thead {
    border: 1px solid;
  }
</style>
@endsection

@section('title', 'Dashboard')

  <div id="wrapper">
   <!-- <div id="app"> -->
    @section('content')
<div id="app">
    <div class="row">
      <div class="col-sm-8">
        <h1>@lang('common.Projects Management')</h1>
      </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
          <a class="btn btn-primary btn-md pull-right" href="{{route('project.index')}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('Company_Admin/dashboard.Back')</a>
            <div class="panel panel-info">
                <div class="panel-heading">
                    @lang('common.Project details')
                </div>
                <div class="panel-body">
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.Name') :</label>
                      <span style="position: absolute; left: 150px;">{{$project->name}}</span>
                    </div>
                    <br>
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.Description') :</label>
                      <span style="position: absolute; left: 150px;">{{$project->description}}</span>
                    </div>
                    <br>
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.Customer') :</label>
                      <span style="position: absolute; left: 150px;">{{$project->customer->name}}</span>
                    </div>
                    <br>
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.Supervisor') :</label>
                      <span style="position: absolute; left: 150px;">{{($project->projectSupervisor)? $project->projectSupervisor->name : ''}}</span>
                    </div>
                    <br>
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.Inspector') :</label>
                      <span style="position: absolute; left: 150px;">{{($project->projectInspector)? $project->projectInspector->name : ''}}</span>
                    </div>
                    <br>
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.Phone') :</label>
                      <span style="position: absolute; left: 150px;">{{$project->phone}}</span>
                    </div>
                    <br>
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.Address') :</label>
                      <span style="position: absolute; left: 150px;">{{$project->address}}</span>
                    </div>
                    <br>
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.Country') :</label>
                      <span style="position: absolute; left: 150px;">{{$project->country}}</span>
                    </div>
                    <br>
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.Start Date') :</label>
                      <span style="position: absolute; left: 150px;">{{ date('M j, Y', strtotime($project->start_date)) }}</span>
                    </div>
                    <br>
                    <div class="inline">
                      <label for="name">@lang('Company_Admin/dashboard.End Date') :</label>
                      <span style="position: absolute; left: 150px;">{{ date('M j, Y', strtotime($project->end_date)) }}</span>
                    </div>
                    <br>

                    <div class="inline">
                      <label for="name"><strong>@lang('Company_Admin/dashboard.Locations') :</strong></label>
                      <ul style="padding-left:150px; list-style-type:square">
                        @foreach ($project->locations as $location)
                          <li> <i>{{$location->name}}</i> </li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="row">
                      <div class="col-md-4 col-md-offset-2">
                          <button class="btn btn-danger pull-right"
                          @click.prevent="showAllJobs = true" role="button" >@lang('Company_Admin/dashboard.show_all_Jobs')</button>

                          <button class="btn btn-success pull-right" @click.prevent="showAllJobs = false" role="button" >@lang('Company_Admin/dashboard.filter_jobs')</button>
                      </div>
                    </div>
                    <br>
                    <input type="hidden" value="{{$project->id}}" ref="proj_id">

                    <template v-if="showAllJobs">
                      <div class="row">
                        <div class="panel panel-primary">
                          <div class="panel-heading inline">
                            <label for="name"><strong>@lang('Company_Admin/dashboard.Work Program') :</strong></label>

                            <a class="btn btn-md btn-danger pull-right" name="button" href="{{route('projectAllJobPdf',$project->id)}}"><i class="far fa-file-pdf"></i> @lang('Company_Admin/dashboard.Download') PDF</a>
                          </div>

                          <div class="panel-body table-responsive">
                            @foreach($project->jobs as $key=>$job)
                            <template>
                              <h4>{{$job->floor->name}}</h4>
                              <table class="table table-bordered table-striped table-hover">
                                <thead>
                                  <tr>
                                    <th>@lang('Company_Admin/dashboard.Area')</th>
                                    <th>@lang('Company_Admin/dashboard.Worker')</th>
                                    <th>@lang('Company_Admin/dashboard.Location')</th>
                                    <th>@lang('Company_Admin/dashboard.Element')</th>
                                    <th>@lang('Company_Admin/dashboard.Task')</th>
                                    <th>@lang('Company_Admin/dashboard.Type')</th>
                                    <th>@lang('Company_Admin/dashboard.Mon')</th>
                                    <th>@lang('Company_Admin/dashboard.Tue')</th>
                                    <th>@lang('Company_Admin/dashboard.Wed')</th>
                                    <th>@lang('Company_Admin/dashboard.Thur')</th>
                                    <th>@lang('Company_Admin/dashboard.Fri')</th>
                                    <th>@lang('Company_Admin/dashboard.Sat')</th>
                                    <th>@lang('Company_Admin/dashboard.Sun')</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($job->days as $key => $day)
                                  <tr>
                                    <td>{{$day->area->name}}</td>
                                    <td>{{$day->user->name}}</td>
                                    <td>{{$day->location['name']}}</td>
                                    <td>{{$day->element->name}}</td>
                                    <td>{{$day->task->name}}</td>
                                    <td>{{$day->type}}</td>
                                    <td>{{($day->mon == "1") ? 'X':''}}</td>
                                    <td>{{($day->tue == "1") ? 'X':''}}</td>
                                    <td>{{($day->wed == "1") ? 'X':''}}</td>
                                    <td>{{($day->thu == "1") ? 'X':''}}</td>
                                    <td>{{($day->fri == "1") ? 'X':''}}</td>
                                    <td>{{($day->sat == "1") ? 'X':''}}</td>
                                    <td>{{($day->sun == "1") ? 'X':''}}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </template>
                            @endforeach
                          </div>
                      </div>
                      </div>
                    </template>

                    <template v-else>
                      <div class="inline">
                        <div class="panel panel-primary">
                         <div class="panel-heading inline">
                            <label for="name"><strong>@lang('Company_Admin/dashboard.Work Program') :</strong></label>

                            <template v-if="downloadButton">
                                <a class="btn btn-md btn-danger pull-right" name="button" v-bind:href="route"><i class="far fa-file-pdf"></i> @lang('Company_Admin/dashboard.Download') PDF</a>
                            </template>
                        </div>

                        <div class="panel-body">


                          <div class="inline">
                            <div class="row">
                              <div class="col-md-5">
                                <!-- Floors -->
                                <div class="row">
                                  <div class="col-md-4 col-md-offset-1">
                                    <label for="name"><strong>@lang('Company_Admin/dashboard.Floor') :*</strong></label>
                                  </div>

                                  <div class="col-md-7">
                                    <v-select :options="{{$floors}}" label="name"
                                    v-model="floor_id"
                                    @input="floorChanged"
                                    placeholder="@lang('Company_Admin/dashboard.Select') @lang('Company_Admin/dashboard.Floor')">
                                    </v-select>
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-5">
                                <div class="row">
                                  <div class="col-md-3 col-md-offset-1">
                                    <label for="name"><strong>@lang('Company_Admin/dashboard.Area') :*</strong></label>
                                  </div>

                                  <div class="col-md-8">
                                    <v-select :options="areas" label="name" v-model="area"
                                    placeholder="@lang('Company_Admin/dashboard.Select') @lang('Company_Admin/dashboard.Area')">
                                    </v-select>
                                  </div>
                                </div>
                                <!-- areas -->
                              </div>

                              <div class="col-md-2">
                                <button href="#" class="btn btn-success btn-md" @click.prevent="search"><i class="fab fa-searchengin"></i> @lang('Company_Admin/dashboard.Search')</button>
                              </div>
                            </div>
                            <!-- row -->
                            <br>
                            <template v-if="days.length > 0">

                              <div class="">
                                <table class="table table-hover table-striped table-bordered">
                                  <thead>
                                    <tr>
                                      <th>@lang('Company_Admin/dashboard.Area')</th>
                                      <th>@lang('Company_Admin/dashboard.Element')</th>
                                      <th>@lang('Company_Admin/dashboard.Location')</th>
                                      <th>@lang('Company_Admin/dashboard.Task')</th>
                                      <th>@lang('Company_Admin/dashboard.Worker') @lang('Company_Admin/dashboard.Name')</th>
                                      <th>@lang('Company_Admin/dashboard.Type')</th>
                                      <th>@lang('Company_Admin/dashboard.Mon')</th>
                                      <th>@lang('Company_Admin/dashboard.Tue')</th>
                                      <th>@lang('Company_Admin/dashboard.Wed')</th>
                                      <th>@lang('Company_Admin/dashboard.Thur')</th>
                                      <th>@lang('Company_Admin/dashboard.Fri')</th>
                                      <th>@lang('Company_Admin/dashboard.Sat')</th>
                                      <th>@lang('Company_Admin/dashboard.Sun')</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr v-for="(day, index) in days">
                                      <td>@{{day.area.name}}</td>
                                      <td>@{{day.element_name}}</td>
                                      <td>@{{day.location}}</td>
                                      <td>@{{day.task_name}}</td>
                                      <td>@{{day.worker}}</td>
                                      <td>@{{day.type}}</td>
                                      <td>@{{(day.mon == 1) ? 'X' : ''}}</td>
                                      <td>@{{(day.tue == 1) ? 'X' : ''}}</td>
                                      <td>@{{(day.wed == 1) ? 'X' : ''}}</td>
                                      <td>@{{(day.thu == 1) ? 'X' : ''}}</td>
                                      <td>@{{(day.fri == 1) ? 'X' : ''}}</td>
                                      <td>@{{(day.sat == 1) ? 'X' : ''}}</td>
                                      <td>@{{(day.sun == 1) ? 'X' : ''}}</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </template>
                          </div>
                        </div>
                     </div>
                   </div>
                  </template>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
 <!-- </div> -->
<!-- vue ends here -->
    @endsection
</div>

  @section('outer_script')



  <script src="{{asset('select2/dist/js/select2.min.js')}}"></script>
  <script src="{{asset('js/lodash.min.js')}}"></script>
  <script src="{{asset('js/axios.min.js')}}"></script>
  <script src="{{asset('js/vue.min.js')}}"></script>
  <script src="{{asset('js/vue-select-latest.js')}}"></script>
  <!-- <script src="https://unpkg.com/vue-select@latest"></script> -->
  <!-- <script src="{{asset('js/vue-select.js')}}"></script> -->
  <script src="{{asset('js/view_project.js')}}"></script>



  @endsection


<!-- Content Header (Page header) -->
