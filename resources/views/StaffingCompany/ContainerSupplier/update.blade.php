<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div id="myapp">
            <update-container-supplier
                :container_supplier="{{ json_encode($container_supplier) }}"
                :translations="{{ json_encode($translations) }}"
                :common="{{ json_encode($common) }}"
            >
            </update-container-supplier>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')
</body>
</html>
