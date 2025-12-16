<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')
<style>
    /* Style the dialogue box */
    #dialogue {
        display: none;
        position: absolute;
        z-index: 1;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    /* Style the dialogue box content */
    #dialogue-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    /* Style the input fields */
    .input_dialogue {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
    }

    /* Style the submit button */
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        float: right;
    }

    /* Style the cancel button */
    .cancel-button {
        background-color: #f44336;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        float: right;
        margin-right: 10px;
    }

    /* Clear floats */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')

        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div class="card" style="height: 100%">
                <div style="margin-top: 10px;" class="card-header">
                    <h3 class="mb-1">Project Planning</h3>

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-muted mb-0">Current Week: {{ $currentWeek }} ({{ $count }})</h5>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            New Plan
                        </button>
                    </div>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>{{ __('Staffing_Company/common.week_no') }}</th>
                                <th>{{ __('Staffing_Company/common.date') }}</th>
                                <th>{{ __('Staffing_Company/common.day') }}</th>
                                <th>{{ __('Staffing_Company/common.directing') }}</th>
                                <th>{{ __('Staffing_Company/common.accepting') }}</th>
                                <th>{{ __('Staffing_Company/common.total') }}</th>
                                <th>{{ __('Staffing_Company/common.option') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plannings as $planning)
                                <tr>
                                    <td>{{ $planning->id }}</td>
                                    @if ($planning->week_no)
                                        <td>{{ $planning->week_no }}</td>
                                    @else
                                        <td>{{ $planning->employeeProjects()->value('week_no') ?? '' }}</td>
                                    @endif
                                    <td>{{ $planning->date ?? '' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($planning->date)->format('D') ?? '' }}</td>
                                    <td>{{ $planning->countActiveEmployeeProjects() }}</td>
                                    <td>{{ $planning->countInactiveEmployeeProjects() }}</td>
                                    <td>{{ $planning->countAllEmployeeProjects() }}</td>
                                    <td>
                                        <div class="row">
                                            {{--                                    <a href="#"> --}}
                                            {{--                                        <i style="color: green;" class="col fa fa-eye"></i> --}}
                                            {{--                                    </a> --}}
                                            <a href="{{ route('project_plannings.edit', $planning->id) }}">
                                                <i style="color: green;" class="col far fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('project_plannings.destroy', $planning->id) }}"
                                                id="delete-form-{{ $planning->id }}">
                                                @csrf
                                                @method('Delete')
                                                <i style="color: red" type="button"
                                                    onclick="confirmDelete({{ $planning->id }})"
                                                    class="col fas fa-trash"></i>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>{{ __('Staffing_Company/Project_Planning/p_index.create_plan') }}</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('project_plannings.store') }}">
                            @csrf
                            <div class="modal-body">
                                <label
                                    for="planning_date">{{ __('Staffing_Company/Project_Planning/p_index.select_date') }}</label>
                                <input required class="input_dialogue" type="date" name="planning_date">

                                <div>
                                    <label
                                        for="project">{{ __('Staffing_Company/Project_Planning/p_index.select_project') }}</label>
                                </div>
                                <select class="js-example-basic-single form-control " style="width:100% ; height: 100%"
                                    name="project_id">
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('StaffingCompany.partials._footer')

    <!-- Page specific script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "lengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                "order": [
                    [0, "desc"]
                ],
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        function confirmDelete(itemId) {
            if (confirm('Are you sure you want to delete this?')) {
                document.getElementById('delete-form-' + itemId).submit();
            }
        }

        // // Get the dialogue box element
        // var dialogue = document.getElementById("dialogue");
        //
        // // Open the dialogue box
        // function openDialogue() {
        //     dialogue.style.display = "flex";
        // }
        //
        // // Close the dialogue box
        // function closeDialogue() {
        //     dialogue.style.display = "none";
        // }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".js-example-basic-single").select2();
        });
    </script>
</body>

</html>
