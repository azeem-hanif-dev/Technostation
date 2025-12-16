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
                    <div class="row">
                        <div class="col-md-6">
                            <h3>{{ __('Staffing_Company/Order_Container/o_index.order_waste') }}</h3>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('order-waste-container.create') }}">
                                <button
                                    class="btn btn-success float-right">{{ __('Staffing_Company/Order_Container/o_index.new_order') }}</button>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table  table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th style="width: 95px">{{ __('Staffing_Company/Order_Container/o_index.order') }}#</th>
                                <th>{{ __('Staffing_Company/Order_Container/o_index.order_date') }}</th>
                                <th>{{ __('Staffing_Company/Order_Container/o_index.execution_date') }}</th>
                                <th>{{ __('Staffing_Company/Order_Container/o_index.customer_name') }}</th>
                                <th>{{ __('Staffing_Company/Order_Container/o_index.project_name') }}</th>
                                <th>{{ __('Staffing_Company/Order_Container/o_index.waste_process') }}</th>
                                <th>Status</th>
                                <th>{{ __('Staffing_Company/common.option') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_containers as $order_container)
                                <tr>
                                    <td>{{ $order_container->id }}</td>
                                    <td>BN-{{ $order_container->id ?? '' }}</td>
                                    <td>{{ $order_container->order_date_time ?? '' }}</td>
                                    <td>{{ $order_container->execution_date ?? '' }}</td>
                                    <td>{{ $order_container->order_by ?? '' }}</td>
                                    <td>{{ $order_container->project->name ?? '' }}</td>
                                    <td>{{ $order_container->supplier->company_name ?? '' }}</td>

                                    <td>
                                        <button style="font-size: 12px;"
                                            class="btn @if ($order_container->status == 'open') btn-primary
                                                                     @elseif($order_container->status == 'close') btn-secondary
                                                                     @elseif($order_container->status == 'send to supplier') btn-warning
                                                                     @elseif($order_container->status == 'pick up') btn-info
                                                                     @elseif($order_container->status == 'picked') btn-success
                                                                     @elseif($order_container->status == 'canceled') btn-danger
                                                                     @elseif($order_container->status == 'in progress') btn-warning
                                                                     @else btn-dark @endif
                                                                     "
                                            data-toggle="modal" data-target="#exampleModalCenter"
                                            data-id="{{ $order_container->id }}"
                                            data-status="{{ $order_container->status }}">
                                            {{ $order_container->status }}
                                        </button>

                                    </td>
                                    <td>
                                        <div class="row">
                                            <a href="{{ route('order-container.view', $order_container->id) }}">
                                                <i style="color: green;" class="col fa fa-eye"></i>

                                            </a>
                                            <a href="{{ route('order-container-pdf', $order_container->id) }}">
                                                <i style="color: green;" class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="{{ route('order-waste-container.edit', $order_container->id) }}">
                                                <i style="color: green;" class="col far fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('order-waste-container.destroy', $order_container->id) }}"--}}
                                                id="delete-form-{{ $order_container->id }}">
                                                @csrf
                                                @method('Delete') <i style="color: red" type="button"
                                                    onclick="confirmDelete({{ $order_container->id }})"
                                                    class="col fas fa-trash"></i>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="statusUpdateForm" class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="statusSelect" class="form-label">Select Status</label>
                        <select class="form-control" id="statusSelect" name="status">
                            <option value="open">Open</option>
                            <option value="in progress">In Progress</option>
                            <option value="send to supplier">Send to Supplier</option>
                            <option value="pick up">Pick Up</option>
                            <option value="picked">Picked</option>
                            <option value="canceled">Canceled</option>
                            <option value="close">Close</option>
                        </select>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="$('#statusUpdateForm').submit()">Save
                        changes</button>

                </div>

            </div>

        </div>
    </div>



    @include('StaffingCompany.partials._footer')

    <!-- Page specific script -->
    {{-- <script>
        $(function() {
            $("#example1").DataTable({
                "lengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                "order": [
                    [0, "desc"]
                ],
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        function confirmDelete(itemId) {
            if (confirm('Are you sure you want to delete this?')) {
                document.getElementById('delete-form-' + itemId).submit();
            }
        }

        $('#exampleModalCenter').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var status = button.data('status');

            var modal = $(this);
            modal.find('#statusSelect').val(status);
        });
    </script> --}}
    <script>
        $("#example1").DataTable({
            "lengthMenu": [
                [25, 50, 100, -1],
                [25, 50, 100, "All"]
            ],
            "order": [
                [0, "desc"]
            ],
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });


        $(function() {
            let orderId = null;

            $('#exampleModalCenter').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                orderId = button.data('id');
                const status = button.data('status');

                $('#orderId').val(orderId);
                $('#statusSelect').val(status);
            });

            function getStatusClass(status) {
                switch (status) {
                    case 'open':
                        return 'btn-primary';
                    case 'close':
                        return 'btn-secondary';
                    case 'send to supplier':
                        return 'btn-warning';
                    case 'pick up':
                        return 'btn-info';
                    case 'picked':
                        return 'btn-success';
                    case 'canceled':
                        return 'btn-danger';
                    case 'in progress':
                        return 'btn-warning ';
                        // default:
                        //     return 'btn-dark';
                }
            }

            $('#statusUpdateForm').on('submit', function(e) {
                e.preventDefault();

                const status = $('#statusSelect').val();
                const token = $('input[name="_token"]').val();

                $.ajax({
                    url: `/order-waste-container/${orderId}/status`,
                    method: 'POST',
                    data: {
                        _token: token,
                        status: status
                    },
                    success: function(response) {
                        $('#exampleModalCenter').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated!',
                            text: 'The status has been updated successfully.',
                            timer: 3000,
                            showConfirmButton: false
                        });


                        const newStatus = status.toLowerCase();
                        const button = $(`button[data-id="${orderId}"]`);
                        button.text(status);

                        button.removeClass();


                        button.addClass('btn btn-sm');
                        button.addClass(getStatusClass(newStatus));
                    },
                    error: function(xhr) {
                        alert('Error updating status');
                        console.error(xhr.responseText);
                    }
                });
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: translations.are_you_sure,
                text: translations.warning_text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: translations.confirm_button,
                cancelButtonText: translations.cancel_button
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }



        const translations = {
            are_you_sure: "{{ __('Staffing_Company/Container_supplier/c_index.are_you_sure') }}",
            warning_text: "{{ __('Staffing_Company/Container_supplier/c_index.warning_text') }}",
            confirm_button: "{{ __('Staffing_Company/Container_supplier/c_index.confirm_button') }}",
            cancel_button: "{{ __('Staffing_Company/Container_supplier/c_index.cancel_button') }}"
        };
    </script>


</body>

</html>
