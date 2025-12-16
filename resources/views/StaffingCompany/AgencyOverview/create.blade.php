<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div id="myapp">
            <create-employment-agency-overview
                :week_state_week_cards="{{ json_encode($week_cards) }}"
                :week_state_ids="{{ json_encode($ids) }}"
                :agency_id="{{ json_encode($agency_id) }}"
                :current_week_no="{{ json_encode($week_no) }}"
                :personnels="{{ json_encode($personnels) }}"
                :week_state_over_view="{{ json_encode($week_state_over_view) }}"
                :wages="{{ json_encode($comments) }}"
                :translations="{{ json_encode($translations) }}"
            ></create-employment-agency-overview>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')
</body>
</html>
