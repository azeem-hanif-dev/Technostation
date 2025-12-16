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
            <div id="myapp">
                <create-maintenance :projects="{{ json_encode($projects) }}"
                    :personnels="{{ json_encode($personnels) }}" :translations="{{ json_encode($translations) }}">
                </create-maintenance>
            </div>
        </div>

    </div>

    {{-- Vue App --}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    {{-- Footer --}}
    @include('StaffingCompany.partials._footer')

</body>

</html>
