<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div id="myapp">
            <update-personnel
                :agencies="{{ json_encode($agencies) }}"
                :personnel_functions="{{ json_encode($personnel_functions) }}"
                :e_functions="{{ json_encode($e_functions) }}"
                :staff_types="{{ json_encode($staff_types) }}"
                :translations="{{ json_encode($translations) }}"
                :nationalities="{{ json_encode($nationalities) }}"
                :common="{{ json_encode($common) }}"
                :token="{{ json_encode($token) }}"
                :personnel_function_names="{{ json_encode($personnel_function_names) }}"
                :personnel="{{ json_encode($personnel) }}">
            </update-personnel>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')
</body>
</html>
