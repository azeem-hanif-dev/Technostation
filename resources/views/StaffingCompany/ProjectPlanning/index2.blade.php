<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')
<style>
    /* Switch Button Style */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .inactive-row {
        background-color: #fff3cd !important;
        color: #856404 !important;
    }

    /* Employee removed row -> Red */
    .removed-row {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #00a65a;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    /* Inactive Project Card Color */
    .inactive-card {
        background-color: #ffdddd !important;
        border: 1px solid #ffb3b3 !important;
    }

    /* Disabled button style */
    .btn-disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            @if (session('message'))
                <div class="alert alert-warning">
                    {{ session('message') }}
                </div>
            @endif

            {{-- Add Project Modal --}}
            <div class="modal fade" id="exampleModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <h2 style="margin:10px">{{ __('Staffing_Company/Project_Planning/p_index.add_project') }}</h2>
                        <form method="POST" action="{{ route('project_plannings.store') }}">
                            @csrf
                            <input type="hidden" name="planning_date" value="{{ $date }}">
                            <input type="hidden" name="planning_project_id" value="{{ $id }}">

                            <label style="margin:10px"
                                for="project">{{ __('Staffing_Company/Project_Planning/p_index.select_project') }}</label>
                            <select class="js-example-basic-single form-control" style="width:100%" name="project_id">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name ?? '' }}</option>
                                @endforeach
                            </select>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Duplicate Modal --}}
            <div class="modal fade" id="duplicateModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <h2 style="margin:10px">Select Date to copy</h2>
                        <form method="POST" action="{{ route('copy-project-planning', $id) }}">
                            @csrf
                            <input type="date" class="form-control input_dialogue" name="planning_date">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Copy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <h3 class="text-md-start text-center">{{ $date }} || Week No. {{ $week_no }} ||
                            {{ $day }}</h3>
                        <h5 class="text-md-start text-center">Total Projects: {{ $total_projects }}</h5>
                    </div>

                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <form method="GET" action="{{ route('project_plannings.edit', $id) }}">
                            <div class="input-group">
                                <input type="text" id="search" name="search" class="form-control"
                                    placeholder="Search for a project..." value="{{ request()->search }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-12 col-md-4 d-flex flex-wrap justify-content-md-end justify-content-center"
                        style="gap:5px">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">+
                            {{ __('Staffing_Company/Project_Planning/p_index.add_project') }}</button>
                        <a href="{{ route('project_plannings.index') }}"><button type="button"
                                class="btn btn-danger">{{ __('Staffing_Company/Project_Planning/p_index.cancel') }}</button></a>
                        <button type="button" class="btn btn-warning" data-toggle="modal"
                            data-target="#duplicateModal">{{ __('Staffing_Company/Project_Planning/p_index.duplicate') }}</button>
                        <form action="{{ route('planning.create-week-state', $id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn btn-warning">{{ __('Staffing_Company/Project_Planning/p_index.create_weekstate') }}</button>
                        </form>
                        <a href="{{ route('project-planning-pdf', $id) }}">
                            <button type="button"
                                class="btn btn-success">{{ __('Staffing_Company/Project_Planning/p_index.pdf') }}</button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card" id="project-container">
                @foreach ($planning_projects as $planning_project)
                    @php
                        $projectEmployeePlannings = $employee_plannings->where('project_id', $planning_project->id);

                        $isProjectInactive =
                            $projectEmployeePlannings->isNotEmpty() &&
                            $projectEmployeePlannings->every(fn($emp) => $emp->inactive);
                    @endphp

                    <div class="card planning-project-card {{ $isProjectInactive ? 'inactive-card' : '' }}"
                        data-project-id="{{ $planning_project->id }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-8 mb-3 mb-md-0">
                                    <h3>{{ $planning_project->name }} ({{ $planning_project->customer->name ?? '' }})
                                        - {{ $planning_project->address }}</h3>
                                </div>

                                <div class="col-12 col-md-4 d-flex flex-wrap justify-content-md-end justify-content-center"
                                    style="gap: 6px">

                                    {{-- Remove Project --}}
                                    <form method="POST"
                                        action="{{ route('remove-planning-project', ['date' => $date, 'id' => $planning_project->id]) }}"
                                        id="delete-form-{{ $planning_project->id }}" data-type="project">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete(this)"
                                            @if ($isProjectInactive) disabled @endif>
                                            {{ __('Staffing_Company/Project_Planning/p_index.remove_project') }}
                                        </button>
                                    </form>

                                    {{-- Add Staff --}}
                                    <a
                                        href="{{ $isProjectInactive ? '#' : route('employee_plannings.create', ['planning_id' => $id, 'date' => $date, 'project_id' => $planning_project->id]) }}">
                                        <button class="btn btn-success"
                                            @if ($isProjectInactive) disabled @endif>
                                            {{ __('Staffing_Company/Project_Planning/p_index.add_staff') }}

                                        </button>
                                    </a>

                                    {{-- Toggle Project Inactive --}}
                                    <form method="POST"
                                        action="{{ route('toggle-project-inactive', ['id' => $planning_project->id]) }}">
                                        @csrf
                                        <input type="hidden" name="inactive"
                                            value="{{ $isProjectInactive ? 0 : 1 }}">
                                        <input type="hidden" name="date" value="{{ $date }}">
                                        <button type="submit" class="btn mb-2"
                                            style="padding: 6px 12px; {{ $isProjectInactive ? 'background-color:#fff3cd;color:#856404;' : 'background-color:#28a745;color:white;' }}">
                                            {{ $isProjectInactive
                                                ? __('Staffing_Company/Project_Planning/p_index.active')
                                                : __('Staffing_Company/Project_Planning/p_index.inactive') }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Employee Table --}}
                            <table class="datatable-table table table-bordered table-striped">
                                <thead>


                                    <tr>
                                        <th>Id</th>
                                        <th>{{ __('Staffing_Company/common.staff') }}</th>
                                        <th>{{ __('Staffing_Company/common.comments') }}</th>
                                        <th>{{ __('Staffing_Company/common.directing') }}</th>
                                        <th>{{ __('Staffing_Company/common.accepting') }}</th>
                                        <th>{{ __('Staffing_Company/common.e_function') }}</th>
                                        <th>{{ __('Staffing_Company/common.option') }}</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($projectEmployeePlannings as $employee_planning)
                                        @php
                                            $personnel =
                                                $employee_planning->personnel ??
                                                \App\Models\StaffingCompany\Personnel::find(
                                                    $employee_planning->employee_id,
                                                );
                                            $isRemoved = $employee_planning->planning_delete == 1;
                                            $isInactive = $employee_planning->inactive;
                                            $isInMultipleProjects = _isEmployeeInMoreThanOneProject(
                                                $employee_planning->employee_id,
                                                $employee_planning->projectPlanning->date,
                                            );
                                        @endphp
                                        <tr
                                            @if ($isRemoved) class="removed-row" @elseif($isInactive) class="inactive-row" @endif>
                                            <td>{{ $employee_planning->employee_id }}</td>

                                            {{-- Staff Name with green highlight if in multiple projects --}}
                                            <td
                                                @if ($isInMultipleProjects) style="background-color:#00a65a;color:white;" @endif>
                                                {{ $personnel->first_name ?? '' }} {{ $personnel->last_name ?? '' }}
                                                @if ($isRemoved)
                                                    <span style="font-weight:bold;">(Removed)</span>
                                                @endif
                                            </td>

                                            <td>{{ $employee_planning->notes ?? '' }}</td>
                                            <td>{{ $employee_planning->status == 1 ? 'Yes' : 'No' }}</td>
                                            <td>{{ $employee_planning->status == 2 ? 'Yes' : 'No' }}</td>
                                            <td>{{ $employee_planning->employeeFunction->name ?? (\App\Models\StaffingCompany\EmployeeFunction::find($employee_planning->geschikt)->name ?? '') }}
                                            </td>

                                            {{-- Options: Edit / Delete --}}
                                            <td>
                                                <div class="row">
                                                    <a
                                                        href="{{ $isProjectInactive ? '#' : route('employee_plannings.edit', $employee_planning->id) }}">
                                                        <i class="col far fa-edit"
                                                            style="color:green; @if ($isProjectInactive) pointer-events:none; opacity:0.6; @endif"></i>
                                                    </a>

                                                    @if (!$isRemoved)
                                                        <form method="POST"
                                                            action="{{ route('employee_plannings.destroy', $employee_planning->id) }}"
                                                            id="delete-form-{{ $employee_planning->id }}"
                                                            data-type="employee" data-day-no="{{ $day_no }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <i class="col fas fa-trash"
                                                                style="color:red; @if ($isProjectInactive) pointer-events:none; opacity:0.6; @endif"
                                                                type="button" onclick="confirmDelete(this)"></i>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                @endforeach
            </div>


            @include('StaffingCompany.partials._footer')

            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

            <script>
                $(function() {
                    $(".datatable-table").DataTable({
                        "order": [
                            [0, "desc"]
                        ],
                        "responsive": true,
                        "lengthChange": true,
                        "autoWidth": false,
                        "paging": false,
                    }).container().appendTo('.dataTables_wrapper .col-md-6:eq(0)');
                });

                function confirmDelete(el) {
                    let form = el.closest("form");
                    if (!form) return;
                    let type = form.dataset.type;
                    let dayNo = form.dataset.dayNo ?? null;
                    if (!confirm(type === "project" ? "Are you sure you want to remove this project?" :
                            "Are you sure you want to remove this employee planning?")) return;
                    let fd = new FormData(form);
                    if (dayNo) fd.append("day_no", dayNo);
                    fetch(form.action, {
                            method: "POST",
                            headers: {
                                "Accept": "application/json",
                                "X-Requested-With": "XMLHttpRequest"
                            },
                            body: fd
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.message);
                            location.reload();
                        })
                        .catch(err => console.error(err));
                }

                $(document).ready(function() {
                    $(".js-example-basic-single").select2();
                });
            </script>

</body>

</html>
