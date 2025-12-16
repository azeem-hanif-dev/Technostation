@extends('Company_Admin.layouts.main')

@section('outer_css')

<!-- <style src="{{asset('select2/dist/css/select2.min.css')}}"></style> -->
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<link rel="stylesheet" href="https://unpkg.com/vue-select@3.0.0/dist/vue-select.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" rel="stylesheet" type="text/css" />
<!-- Latest compiled JavaScript -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<style>
[v-cloak] {
  display: none;
}

input[type=checkbox] {
    transform: scale(1.5);
    margin-right: 10px;
}
input[type=radio] {
    transform: scale(1.5);
    margin-right: 10px;
}
/* Always set the map height explicitly to define the size of the div
* element that contains the map. */
#map {
  height: 100%;
}
/* Optional: Makes the sample page fill the window. */
/* html, body {
height: 100%;
margin: 0;
padding: 0;
} */
.controls {
  margin-top: 10px;
  border: 1px solid transparent;
  border-radius: 2px 0 0 2px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  height: 32px;
  outline: none;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

#pac-input {
  background-color: #fff;
  font-family: Roboto;
  font-size: 15px;
  font-weight: 300;
  margin-left: 12px;
  padding: 0 11px 0 13px;
  text-overflow: ellipsis;
  width: 300px;
}

#pac-input:focus {
  border-color: #4d90fe;
}

.pac-container {
  font-family: Roboto;
}

#type-selector {
  color: #fff;
  background-color: #4d90fe;
  padding: 5px 11px 0px 11px;
}

#type-selector label {
  font-family: Roboto;
  font-size: 13px;
  font-weight: 300;
}

table{
  table-layout: auto;
  width: 100%;
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
<link href="{{asset('select2/dist/css/select2.min.css')}}" rel="stylesheet" />
@endsection


@section('title', 'Dashboard')

<div id="wrapper">
  @section('content')
  <div class="" id="edit_project" v-cloak>
    <input type="hidden" ref="language" value="{{App::getLocale()}}">
    <div class="row">
      <div class="col-sm-8">
        <h1>@lang('common.Projects Management')</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            @lang('common.Edit project')
          </div>
          <!-- /.panel-heading -->
          <div class="panel-body">
            <div class="">
              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Name'):*</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" v-model="project.name">
                  <span style="color:red;" v-if="errors.name">@{{errors.name[0]}}</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Description'):*</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" v-model="project.description">
                  <span style="color:red;" v-if="errors.description">@{{errors.description[0]}}</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Customer'):*</label>
                <div class="col-md-8">
                  <select class="form-control" v-model="project.customer_id">
                    <option v-for="customer in customers" :value="customer.id" >@{{customer.name}}</option>
                  </select>
                  <span style="color:red;" v-if="errors.customer_id">@{{errors.customer_id[0]}}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Project') @lang('Company_Admin/dashboard.Supervisor'):*</label>
                <div class="col-md-8">
                  <select class="form-control supervisors" v-model="project.supervisor_id" @change.prevent="supervisorChanged">
                    <option value="" disabled>@lang('Company_Admin/dashboard.Select') @lang('Company_Admin/dashboard.Supervisor')</option>
                    <option v-for="supervisor in supervisors"  :value="supervisor.id">@{{ supervisor.name }}</option>
                  </select>
                  <span style="color:red;" v-if="errors.supervisor_id">@{{errors.supervisor_id[0]}}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Project') @lang('Company_Admin/dashboard.Inspector'):*</label>
                <div class="col-md-8">
                  <select class="form-control inspectors" v-model="project.inspector_id">
                    <option value="" disabled>@lang('Company_Admin/dashboard.Select') @lang('Company_Admin/dashboard.Inspector')</option>
                    <option v-for="inspector in inspectors"  :value="inspector.id">@{{ inspector.name }}</option>
                  </select>
                  <span style="color:red;" v-if="errors.inspector_id">@{{errors.inspector_id[0]}}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Phone'):*</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" v-model="project.phone">
                  <span style="color:red;" v-if="errors.phone">@{{errors.phone[0]}}</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Country'):*</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" v-model="project.country" readonly>
                  <span style="color:red;" v-if="errors.country">@{{errors.country[0]}}</span>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-4">
                  <label class="col-md-4" for="">@lang('Company_Admin/dashboard.Post code'):*</label>
                  <div class="col-md-6 pull-right">
                    <input class="form-control"
                    style="padding-left: 18px;" type="text"
                    v-model:value="project.postcode"
                    v-on:keyup.enter="getAddress"
                    v-on:change="project.address=''; project.city=''"
                    required :disabled="!editZipCode">
                    <span style="color:red;" v-if="errors.postcode">@{{errors.postcode[0]}}</span>
                  </div>
                </div>
                <div class="col-md-4" style="">
                  <label class="col-md-6" for="">@lang('common.House Number'):*</label>
                  <div class="col-md-6">
                    <input class="form-control" type="text"
                    v-model:value="project.houseNumber"
                    v-on:keyup.enter="getAddress"
                    v-on:change="project.address=''; project.city=''"
                    required :disabled="!editZipCode">
                    <span style="color:red;" v-if="errors.houseNumber">@{{errors.houseNumber[0]}}</span>

                  </div>
                </div>
                <div class="col-md-2" style="">
                  <!-- <span style="color:blue;"
                  v-if="project.houseNumber && project.postcode && editZipCode"
                  >@lang('common.Press enter')</span> -->

                  <button class="btn btn-danger btn-md" type="button" name="button" @click.prevent="editPostcode"><i class="fa fa-edit"></i></button>

                  <!-- <button class="btn btn-success btn-md" type="button" name="button" @click.prevent="getAddress"
                  :disabled="!editZipCode"><i class="fa fa-arrow-circle-right"></i></button> -->
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Address'):*</label>
                <div class="col-md-8">
                <input class="form-control" type="text" v-model:value="project.address" readonly>
                <span style="color:red;" v-if="errors.address">@{{errors.address[0]}}</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.City'):*</label>
                <div class="col-md-8">
                  <input class="form-control" type="text" v-model:value="project.city" readonly>
                  <span style="color:red;" v-if="errors.city">@{{errors.city[0]}}</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Start Date'):*</label>
                <div class="col-md-8">
                  <input class="form-control" id="startDate" v-model:value="project.start_date" placeholder="dd/mm/yy" >
                  <span style="color:red;" v-if="errors.start_date">@{{errors.start_date[0]}}</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.End Date'):*</label>
                <div class="col-md-8">
                  <input class="form-control" id="endDate" placeholder="dd/mm/yy" v-model:value="project.end_date">
                  <span style="color:red;" v-if="errors.end_date">@{{errors.end_date[0]}}</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Notes'):</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" v-model="project.notes">
                  <span style="color:red;" v-if="errors.notes">@{{errors.notes[0]}}</span>
                </div>
              </div>

              <!-- <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Week Card'):*</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" v-model="project.weekcard">
                </div>
              </div> -->

              <!-- location starts here -->
              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Locations'):*</label>

                <div class="col-md-6">

                  <div class="panel panel-default">
                      <div class="panel-heading">
                          @lang('Company_Admin/dashboard.Locations')
                      </div>
                      <div class="panel-body">
                          <div class="table-responsive">
                              <table class="table table-striped table-bordered table-hover">
                                <thead>
                                  <th>@lang('Company_Admin/dashboard.Locations')</th>
                                  <th></th>
                                </thead>
                                <tbody>
                                  <tr v-for="(location,index) in locations">
                                    <td>@{{location.name}}</td>
                                    <td> <button type="button" class="btn btn-danger btn-md" name="button" @click.prevent="removeLocation(index)"><i class="fa fa-trash" aria-hidden="true"></i></button> </td>
                                  </tr>
                                </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Add') @lang('Company_Admin/dashboard.New') @lang('Company_Admin/dashboard.Locations'):*</label>

                <div class="col-md-8">
                  <div class="panel-group  row" id="accordion">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
                        </h4>
                      </div>

                    <div id="collapseTwo" class="panel-collapse collapse">
                      <div class="panel-body">
                        <div class="field_wrapper">
                            <div class="col-md-10 table-responsive">
                              <div id="map" style="height: 300px; width: 500px"></div>
                              <div id="type-selector" class="controls col-md-2">
                                <input type="radio" name="type" id="changetype-all" checked="checked">
                                <label for="changetype-all">All</label>
                              </div>
                              <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
                            </div>
                            <div class="col-md-2" v-show="locations.length > 0">
                              <button href="#" class="btn btn-info btn-md" title="Add field" @click.prevent="addMoreLocation">+ @lang('Company_Admin/dashboard.Add Location')</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <a href="#" class="pull-right btn btn-success" data-toggle="modal" data-target="#addJob" @click.prevent="addJobMethod">@lang('Company_Admin/dashboard.add_tasks')</a></div>
    </div>

    <div class="row">
      <label class="col-md-2" for=""><h3>@lang('Company_Admin/dashboard.Jobs'):*
      </h3></label>
      <div class="col-md-12">
        <template v-for="(job,key) in jobs">
          <h4>@{{job.floor_name}}</h4>
          <div class="table-responsive" style="overflow-x:auto;">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>@lang('Company_Admin/dashboard.Area')</th>
                <th>@lang('Company_Admin/dashboard.Worker')</th>
                <th>@lang('Company_Admin/dashboard.Location')</th>
                <th>@lang('Company_Admin/dashboard.Element')</th>
                <th>@lang('Company_Admin/dashboard.Task')</th>
                <th>@lang('Company_Admin/dashboard.Type')</th>
                <th>@lang('common.Weeks')</th>
                <th>@lang('Company_Admin/dashboard.Mon')</th>
                <th>@lang('Company_Admin/dashboard.Tue')</th>
                <th>@lang('Company_Admin/dashboard.Wed')</th>
                <th>@lang('Company_Admin/dashboard.Thur')</th>
                <th>@lang('Company_Admin/dashboard.Fri')</th>
                <th>@lang('Company_Admin/dashboard.Sat')</th>
                <th>@lang('Company_Admin/dashboard.Sun')</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(day,index) in job.days">
                <td>@{{day.area_name}}</td>
                <td>@{{day.worker}}</td>
                <td>@{{day.location}}</td>
                <td>@{{day.element_name}}</td>
                <td>@{{day.task_name}}</td>
                <template v-if="language === 'en'">
                  <td>@{{ day.type }}</td>
                </template>
                <template v-else>
                  <td v-if="day.type === 'daily'">dagelijks</td>
                  <td v-if="day.type === 'weekly'">wekelijks</td>
                </template>
                <template v-if="day.type === 'weekly'">
                  <template v-for="week in day.week_number">
                    <span class="badge badge-pill badge-success">@{{ week }}</span>
                  </template>
                </template>
                <template v-else>
                  <td></td>
                </template>
                <td>@{{(day.mon == "1") ? 'X':''}}</td>
                <td>@{{(day.tue == "1") ? 'X':''}}</td>
                <td>@{{(day.wed == "1") ? 'X':''}}</td>
                <td>@{{(day.thu == "1") ? 'X':''}}</td>
                <td>@{{(day.fri == "1") ? 'X':''}}</td>
                <td>@{{(day.sat == "1") ? 'X':''}}</td>
                <td>@{{(day.sun == "1") ? 'X':''}}</td>
                <td>
                  <a href="#" class="edit_row btn btn-info btn-sm" data-toggle="modal" data-target="#editJob" @click.prevent="editJobMethod(day)">@lang('Company_Admin/dashboard.Edit')</a>

                  <a class="btn btn-danger btn-sm" @click.prevent="removeJob(day.id,key,index)">@lang('Company_Admin/dashboard.Delete')</a>
              </td>
              </tr>
            </tbody>
          </table>
          </div>
        </template>
      </div>
    </div>

    <template v-if="newJobs.length > 0">
      <hr>
      <br>
      <div class="row">
        <label class="col-md-2" for=""><h3>@lang('Company_Admin/dashboard.New') @lang('Company_Admin/dashboard.Jobs'):*
        </h3></label>
        <div class="col-md-12">

          <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-hover table-striped table-bordered">
              <thead>
                <tr>
                  <th>@lang('Company_Admin/dashboard.Floor')</th>
                  <th>@lang('Company_Admin/dashboard.Area')</th>
                  <th>@lang('Company_Admin/dashboard.Worker')</th>
                  <th>@lang('Company_Admin/dashboard.Location')</th>
                  <th>@lang('Company_Admin/dashboard.Element')</th>
                  <th>@lang('Company_Admin/dashboard.Task')</th>
                  <th>@lang('Company_Admin/dashboard.Type')</th>
                  <th>@lang('common.Weeks')</th>
                  <th>@lang('Company_Admin/dashboard.Mon')</th>
                  <th>@lang('Company_Admin/dashboard.Tue')</th>
                  <th>@lang('Company_Admin/dashboard.Wed')</th>
                  <th>@lang('Company_Admin/dashboard.Thur')</th>
                  <th>@lang('Company_Admin/dashboard.Fri')</th>
                  <th>@lang('Company_Admin/dashboard.Sat')</th>
                  <th>@lang('Company_Admin/dashboard.Sun')</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(job, key) in newJobs">
                  <td>@{{ job.floor_name }}</td>
                  <td>@{{ job.area }}</td>
                  <td>@{{ job.worker }}</td>
                  <td>@{{ job.location.name }}</td>
                  <td>@{{ job.element_name }}</td>
                  <td>@{{ job.task_name }}</td>
                  <template v-if="language === 'en'">
                    <td>@{{ job.type }}</td>
                  </template>
                  <template v-else>
                    <td v-if="job.type === 'Daily' || job.type === 'Dagelijks'">dagelijks</td>
                    <td v-if="job.type === 'Weekly' || job.type === 'Wekelijks'">wekelijks</td>
                  </template>
                  <template v-if="job.type === 'weekly' || job.type === 'Wekelijks'">
                    <template v-for="week in job.week_number">
                      <span class="badge badge-pill badge-success">@{{ week }}</span>
                    </template>
                  </template>
                  <template v-else>
                    <td></td>
                  </template>
                  <td>@{{ job.mon ? 'X':'' }}</td>
                  <td>@{{ job.tue ? 'X':'' }}</td>
                  <td>@{{ job.wed ? 'X':'' }}</td>
                  <td>@{{ job.thu ? 'X':'' }}</td>
                  <td>@{{ job.fri ? 'X':'' }}</td>
                  <td>@{{ job.sat ? 'X':'' }}</td>
                  <td>@{{ job.sun ? 'X':'' }}</td>
                  <td>
                    <a href="#" class=" btn btn-danger btn-sm" @click.prevent="removeNewJob(key)"><i class="fa fa-times"></i> @lang('Company_Admin/dashboard.Delete')</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </template>
    <hr>

    <div class="row">
      <div class="col-md-12">
        <a href="{{ url('project') }}" class="btn btn-danger pull-right">@lang('Company_Admin/dashboard.Cancel')</a>
        <a href="#" class="btn btn-success pull-right" style="margin: 0 15px;" @click.prevent="updateProjectDetails">@lang('Company_Admin/dashboard.Update')</a>
      </div>
    </div>
<br>
    <!-- Modal -->
  <div class="modal fade" id="editJob" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" @click.prevent="closeEditModel">&times;</button>

        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Floor'):*</label>
            <div class="col-md-8">

              <input type="text" class="form-control" v-model="editJob.floor_name" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Area'):*</label>
            <div class="col-md-8">
              <v-select :clearable = "false" :options="areas" label="name" v-model="editJob.area" placeholder="@lang('Company_Admin/dashboard.Select') @lang('Company_Admin/dashboard.Area')">
              </v-select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Location'):*</label>
            <div class="col-md-8">
              <v-select :options="locations" label="name" v-model="editJob.location" :clearable = "false">
              </v-select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Worker'):*</label>
            <div class="col-md-8">
              <v-select :options="workers" :clearable = "false" label="name" v-model="editJob.worker" placeholder="@lang('Company_Admin/dashboard.Select') @lang('Company_Admin/dashboard.Worker')">
              </v-select>

            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Element'):*</label>
            <div class="col-md-8">
              <v-select :options="elements" :clearable = "false" label="name" v-model="editJob.element" placeholder="@lang('Company_Admin/dashboard.Select') @lang('Company_Admin/dashboard.Worker') Element">
              </v-select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Task'):*</label>
            <div class="col-md-8">
              <v-select :options="tasks" :clearable = "false" label="name" v-model="editJob.task" placeholder="@lang('Company_Admin/dashboard.Select') @lang('Company_Admin/dashboard.Task')">
              </v-select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Type'):*</label>
            <div class="col-md-8">
              <select class="form-control clean_type" v-model="editJob.type" :clearable = "false" @change.prevent="typeIsChanged">
                <option :value="key" v-for="(type, key) in types">@{{ type }}</option>
              </select>
            </div>
          </div>

          <div class="form-group col-md-offset-2" v-if="editJob.type == 'daily'">
              @lang('Company_Admin/dashboard.Mon'): <input type="checkbox" v-model="editJob.mon">
              @lang('Company_Admin/dashboard.Tue'): <input type="checkbox" v-model="editJob.tue">
              @lang('Company_Admin/dashboard.Wed'): <input type="checkbox" v-model="editJob.wed">
              @lang('Company_Admin/dashboard.Thur'): <input type="checkbox" v-model="editJob.thu">
              @lang('Company_Admin/dashboard.Fri'): <input type="checkbox" v-model="editJob.fri">
              @lang('Company_Admin/dashboard.Sat'): <input type="checkbox" v-model="editJob.sat">
              @lang('Company_Admin/dashboard.Sun'): <input type="checkbox" v-model="editJob.sun">
          </div>

          <div class="form-group col-md-offset-2" v-else>
            <div class="form-group row">
              <div class="col-md-9" style="margin-left: 5px; width: 80%;">
                <v-select :options="total_weeks" multiple v-model="selected_weeks" placeholder="@lang('Company_Admin/dashboard.Select week number')">
                </v-select>
              </div>
            </div>
          </div>

          <br>

        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-primary" data-dismiss="modal" @click.prevent="saveEditModel">@lang('Company_Admin/dashboard.Save')</a>
          <button type="button" class="btn btn-default" data-dismiss="modal" @click.prevent="closeEditModel">@lang('Company_Admin/dashboard.Cancel')</button>
        </div>
      </div>

    </div>
  </div>


  <!-- Add new Job Modal -->
  <div class="modal fade" id="addJob" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" @click.prevent="closeAddModel">&times;</button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Floor') :*</label>
            <div class="col-md-8">
              <v-select :options="floors" label="name" v-model="addJob.floor_id" placeholder="@lang('Company_Admin/dashboard.Floor Type')" style="width
              :100%;">
              </v-select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Space type'):*</label>
            <div class="col-md-8">
              <template >
                <v-select :options="areas" label="name" v-model="addJob.area_id" placeholder="@lang('Company_Admin/dashboard.Space type')">
                </v-select>
              </template>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Location'):*</label>
            <div class="col-md-8">
              <v-select :options="locations" label="name" v-model="addJob.location" placeholder="@lang('Company_Admin/dashboard.Add Location')">
              </v-select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Worker Name'):*</label>
            <div class="col-md-8">
              <v-select :options="allWorkers" label="name" v-model="addJob.worker_id" placeholder="@lang('Company_Admin/dashboard.Select Worker')">
              </v-select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Element'):*</label>
            <div class="col-md-8">
              <v-select :options="allElements" label="name" v-model="addJob.element_id" placeholder="@lang('Company_Admin/dashboard.Select Element')">
              </v-select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Task'):*</label>
            <div class="col-md-8">
              <v-select :options="allTasks" label="name" v-model="addJob.task_id" placeholder="@lang('Company_Admin/dashboard.Select Task')">
              </v-select>
            </div>
          </div>

          <!-- <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Type'):*</label>
            <div class="col-md-8">
              <select class="form-control" v-model="addJob.type" style="width: 100%;" @change.prevent="typeIsChanged">
                <option :value="type" v-for="type in types">@{{ type }}</option>
              </select>
            </div>
          </div> -->

          <div class="form-group row">
            <label class="col-md-2" for="">@lang('Company_Admin/dashboard.Type'):*</label>
            <div class="col-md-8">
              <select class="form-control clean_type" v-model="addJob.type" @change.prevent="typeIsChanged"  placeholder="@lang('Company_Admin/dashboard.Select type')">
                <option :value="type" v-for="(type, key) in types">@{{ type }}</option>
              </select>
            </div>
          </div>

          <div class="form-group row" v-if="addJob.type === 'Daily' || addJob.type === 'Dagelijks' ">
            <div class="col-md-10 col-md-offset-2">
              @lang('Company_Admin/dashboard.Mon'): <input class="N" type="checkbox" v-model="addJob.mon">
              @lang('Company_Admin/dashboard.Tue'): <input class="N" type="checkbox" v-model="addJob.tue">
              @lang('Company_Admin/dashboard.Wed'): <input class="N" type="checkbox" v-model="addJob.wed">
              @lang('Company_Admin/dashboard.Thur'): <input class="N" type="checkbox" v-model="addJob.thu">
              @lang('Company_Admin/dashboard.Fri'): <input class="N" type="checkbox" v-model="addJob.fri">
              @lang('Company_Admin/dashboard.Sat'): <input class="N" type="checkbox" v-model="addJob.sat">
              @lang('Company_Admin/dashboard.Sun'): <input class="N" type="checkbox" v-model="addJob.sun">
            </div>
          </div>

          <div class="form-group row" v-else>
              <div class="col-md-10 col-md-offset-2" style="width: 67%;">
                <v-select :options="total_weeks" multiple v-model="add_selected_weeks" placeholder="@lang('Company_Admin/dashboard.Select week number')">
                </v-select>
              </div>
          </div>

        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-primary" @click.prevent="AddJobTemp">@lang('Company_Admin/dashboard.Add')</a>
          <button type="button" class="btn btn-default" data-dismiss="modal" @click.prevent="closeAddModel">@lang('Company_Admin/dashboard.Cancel')</button>
        </div>
        <br>
      </div>

    </div>
  </div>

  </div>

  @endsection
</div>
@section('outer_script')
<script type="text/javascript">
  let project_id = {{$project->id}};
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('js/vue.min.js')}}"></script>
{{-- <script src="https://unpkg.com/vue-i18n/dist/vue-i18n.js"></script> --}}
<script src="{{asset('select2/dist/js/select2.min.js')}}"></script>
<script src="{{asset('project_edit/edit_job.js')}}"></script>
<script src="{{asset('js/lodash.min.js')}}"></script>
<script src="{{asset('js/axios.min.js')}}"></script>
<script src="{{asset('js/vue-select-latest.js')}}"></script>
<script src="https://unpkg.com/vue-swal"></script>
{{--<script--}}
{{--  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCR5PFyvraK8Cqbu-vQu7UAR-NkcABHNuw&libraries=places">--}}
{{--</script>--}}
<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC35SHRVQ0JebXbbRKgx85RTjZXDsDQH70&libraries=places">
</script>
{{--<script--}}
{{--        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrK_NSUqXJCfquvxkwW-Al_EnltT7i7-c&callback=initMap&libraries=places&v=weekly"></script>--}}

<script src="{{asset('js/edit_project.js')}}"></script>
@endsection
