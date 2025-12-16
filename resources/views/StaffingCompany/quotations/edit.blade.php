<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div id="myapp">
                <div id="myapp">
                    <quotation-update :quotation='@json($quotation)'
                        :customers='@json($customers)'
                        :translations='@json($translations)'>
                    </quotation-update>

                </div>
            </div>
        </div>

    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @include('StaffingCompany.partials._footer')
</body>

</html>
