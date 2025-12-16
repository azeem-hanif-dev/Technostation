<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div id="myapp">
            <create-order-waste-container
                :user="{{ json_encode($user) }}"
                :projects="{{ json_encode($projects) }}"
                :suppliers="{{ json_encode($suppliers) }}"
                :translations="{{ json_encode($translations) }}"
                :containerType="{{ json_encode($containerType) }}"
{{--                :suppliers="{{ json_encode($suppliers) }}"--}}
            >
            </create-order-waste-container>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')
</body>
</html>
