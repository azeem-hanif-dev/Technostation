<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div id="myapp">
            <create-personnel
                :agencies="{{ json_encode($agencies) }}"
                :e_functions="{{ json_encode($e_functions) }}"
                :staff_types="{{ json_encode($staff_types) }}"
                :translations="{{ json_encode($translations) }}"
                :common="{{ json_encode($common) }}"
                :nationalities="{{ json_encode($nationalities) }}">
            </create-personnel>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')
</body>
</html>
