<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let projectId = window.location.hash.substring(1); // Extract project_id from fragment
        if (projectId) {
            // Create a form and submit it
            let form = document.createElement("form");
            form.method = "POST";
            form.action = "{{ url()->current() }}"; // Same URL
            form.innerHTML = `
            @csrf
            <input type="hidden" name="project_id" value="${projectId}">
        `;
            document.body.appendChild(form);
            form.submit(); // Auto-submit form
        }
    });
</script>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div id="myapp">
            <update-week-state
                :id="{{ json_encode($id) }}"
                :project_id="{{ json_encode($project_id) }}"
                :projects="{{ json_encode($projects) }}"
                :personnels="{{ json_encode($personnels) }}"
                :wages="{{ json_encode($comments) }}"
                :translations="{{ json_encode($translations) }}"
                :approved_allow="{{ json_encode($approved_allow) }}"
                :week_cards="{{ json_encode($week_cards) }}"
            >
            </update-week-state>
        </div>
    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')

</body>
</html>
