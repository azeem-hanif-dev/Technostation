<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div id="myapp">
            <update-project
                :translations="{{ json_encode($translations) }}"
                :latitude="{{ json_encode($latitude) }}"
                :longitude="{{ json_encode($longitude) }}"
                :project="{{ json_encode($id) }}"
                :project_data="{{ json_encode($project) }}"
                :customers="{{ json_encode($customers) }}"
            >
            </update-project>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')
</body>
</html>
