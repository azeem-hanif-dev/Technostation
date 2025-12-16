<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div id="myapp">
{{--                        <table1></table1>--}}

            <week-weekly-state
                :week_no="{{ json_encode($week_no) }}"
                :projects="{{ json_encode($projects) }}"
                :personnels="{{ json_encode($personnels) }}"
                :wages="{{ json_encode($comments) }}"
                :translations="{{ json_encode($translations) }}"
                :years="{{ json_encode($years) }}"
            >
            </week-weekly-state>
        </div>

    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>

@include('StaffingCompany.partials._footer')

</body>
</html>
