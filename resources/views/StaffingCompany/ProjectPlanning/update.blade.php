<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div id="myapp">
            <update-project-planning
                :employee_project="{{ json_encode($planning) }}"
                :personnels="{{ json_encode($personnels) }}"
                :e_functions="{{ json_encode($e_functions) }}"
                :groups="{{ json_encode($groups) }}"
                :translations="{{ json_encode($translations) }}"
            >
            </update-project-planning>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')
</body>
</html>
