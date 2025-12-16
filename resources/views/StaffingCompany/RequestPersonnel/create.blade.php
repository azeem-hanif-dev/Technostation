<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div id="myapp">
            <create-request-personnel
                :current_date_time="{{ json_encode(_currentDateTime()) }}"
                :e_functions="{{ json_encode($e_functions) }}"
                :projects="{{ json_encode($projects) }}"
                :translations="{{ json_encode($translations) }}"
                :common="{{ json_encode($common) }}"
            >
            </create-request-personnel>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')
</body>
</html>
