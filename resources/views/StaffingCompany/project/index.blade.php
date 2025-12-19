<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')
    @include('StaffingCompany.partials._sideBar')

    <div class="content-wrapper">

        <div class="card">
            <div style="margin-top: 10px;" class="card-header">
                <h3>{{ __('Staffing_Company/Project/p_index.project') }}</h3>

                <a href="{{ route('staffing_projects.create') }}">
                    <button class="btn btn-success float-right">
                        {{ __('Staffing_Company/Project/p_index.add_new') }}
                    </button>
                </a>

            </div>

            <div class="card-body">

                <table id="example1" class="table table-bordered table-striped w-100">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>{{ __('Staffing_Company/Project/p_index.name') }}</th>
                        <th>{{ __('Staffing_Company/Project/p_index.department') }}</th>
                        <th>{{ __('Staffing_Company/Project/p_index.address') }}</th>
                        <th>{{ __('Staffing_Company/Project/p_index.city') }}</th>
                        <th>{{ __('Staffing_Company/Project/p_index.status') }}</th>
                        <th>{{ __('Staffing_Company/Project/p_index.action') }}</th>
                    </tr>
                    </thead>

                    <tbody></tbody>

                </table>

            </div>
        </div>

    </div>

</div>

@include('StaffingCompany.partials._footer')


<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTable Export Dependencies -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>


<script>
let table = $('#example1').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('projects.data') }}",

    lengthMenu: [
        [25, 50, 100, -1],
        [25, 50, 100, "All"]
    ],
    order: [[0, "desc"]],
    responsive: true,
    lengthChange: true,
    autoWidth: false,

    dom: `
        <'row mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>
        <'row mb-2'<'col-sm-12'B>>
        <'row'<'col-sm-12'tr>>
        <'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>
    `,

    buttons: [
        "copy",
        "csv",
        "excel",
        "pdf",
        "print",
        {
            extend: 'colvis',
            text: 'Column Visibility',
            collectionLayout: 'fixed two-column'
        }
    ],

columns: [
    { data: 'id' },
    { data: 'name' },
    { data: 'department.name', defaultContent: '' },
    { data: 'address' },
    { data: 'city' },

    {
        data: 'active',
        render: function (active) {
            if(active){
                return `
                    <span style="
                        color:white;
                        padding:3px 6px;
                        font-size:12px;
                        background-color:green;
                        border-radius:3px;
                    ">Active</span>
                `;
            }
            return `
                <span style="
                    color:white;
                    padding:3px 6px;
                    font-size:12px;
                    background-color:red;
                    border-radius:3px;
                ">Inactive</span>
            `;
        }
    },

    {
        data: 'id',
        orderable: false,
        searchable: false,
        render: function (data) {
            return `
                <div class="row">

                    <a href="/staffing_project/${data}/show">
                        <i style="
                            color:green;
                            font-size:18px;
                            padding:4px;
                            cursor:pointer;
                        " class="col fa fa-eye"></i>
                    </a>

                    <a href="/staffing_projects/${data}/edit">
                        <i style="
                            color:green;
                            font-size:18px;
                            padding:4px;
                            cursor:pointer;
                        " class="col far fa-edit"></i>
                    </a>

                    <i onclick="confirmDelete(${data})"
                        style="
                            color:red;
                            font-size:18px;
                            padding:4px;
                            cursor:pointer;
                        "
                        class="col fas fa-trash">
                    </i>

                </div>
            `;
        }
    }
]

});



// Delete Handler
function confirmDelete(projectId) {

    Swal.fire({
        title: translations.deleteTitle,
        text: translations.deleteText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: translations.deleteConfirm
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "/staffing_projects/" + projectId,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                },

                success: function(response) {

                    if (response.status === 'has_related') {

                        Swal.fire(
                            translations.cannotDeleteTitle,
                            response.message,
                            'warning'
                        );

                    } else if (response.status === 'deleted') {

                        Swal.fire(
                            translations.deletedTitle,
                            translations.deletedText,
                            'success'
                        ).then(() => {

                            table.ajax.reload(null, false);

                        });
                    }

                },

                error: function() {

                    Swal.fire(
                        translations.errorTitle,
                        translations.errorText,
                        'error'
                    );

                }

            });

        }

    });

}


const translations = {
    deleteTitle: @json(__('Staffing_Company/Project/p_index.delete_title')),
    deleteText: @json(__('Staffing_Company/Project/p_index.delete_text')),
    deleteConfirm: @json(__('Staffing_Company/Project/p_index.delete_confirm')),
    deletedTitle: @json(__('Staffing_Company/Project/p_index.deleted_title')),
    deletedText: @json(__('Staffing_Company/Project/p_index.deleted_text')),
    cannotDeleteTitle: @json(__('Staffing_Company/Project/p_index.cannot_delete_title')),
    errorTitle: @json(__('Staffing_Company/Project/p_index.error_title')),
    errorText: @json(__('Staffing_Company/Project/p_index.error_text')),
};

</script>

</body>
</html>
