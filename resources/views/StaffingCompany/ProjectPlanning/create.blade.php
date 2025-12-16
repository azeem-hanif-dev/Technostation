<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">

        <div id="myapp">
            <create-project-planning
                :e_functions="{{ json_encode($e_functions) }}"
                :projects="{{ json_encode($projects) }}"
                :personnels="{{ json_encode($personnels) }}"
                :plan_date="{{ json_encode($date) }}"
                :project_id="{{ json_encode($project_id) }}"
                :groups="{{ json_encode($groups) }}"
                :translations="{{ json_encode($translations) }}"
                :planning_id="{{ json_encode($planning_id) }}"
            >
            </create-project-planning>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')
</body>
</html>
