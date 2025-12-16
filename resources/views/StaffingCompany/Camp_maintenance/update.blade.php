<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        {{-- Navbar --}}
        @include('StaffingCompany.partials._navBar')

        {{-- Sidebar --}}
        @include('StaffingCompany.partials._sideBar')

        {{-- Main Content --}}
        <div class="content-wrapper">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Edit</h3>
                </div>

                <div class="card-body">
                    <div id="myapp">
                        <update-maintenance :projects='@json($projects)'
                            :personnels='@json($personnels)' :camp='@json($camp)'
                            :translations='@json($translations)'>
                        </update-maintenance>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Vue App --}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    {{-- Footer --}}
    @include('StaffingCompany.partials._footer')

</body>

</html>
