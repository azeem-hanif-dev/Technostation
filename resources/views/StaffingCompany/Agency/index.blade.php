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
                  <h3>{{ __('Staffing_Company/Agency/a_index.ea') }}</h3>
                <a href="{{route('employment-agencies.create')}}">
                    <button class="btn btn-success float-right">{{ __('Staffing_Company/Agency/a_index.add_new_ea') }}</button>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>{{ __('Staffing_Company/common.name') }}</th>
                        <th>{{ __('Staffing_Company/Agency/a_index.contact_person') }}</th>
                        <th>{{ __('Staffing_Company/common.email') }}</th>
                        {{-- <th>{{ __('Staffing_Company/common.phone') }}</th> --}}
                        <th>{{ __('Staffing_Company/common.address') }}</th>
                        <th>{{ __('Staffing_Company/common.action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($agencies as $agency)
                        <tr>
                            <td>{{$agency->id}}</td>
                            <td>{{$agency->name ?? ''}}</td>
                            <td>{{$agency->contact_person ?? ''}}</td>
                            <td>{{$agency->email ?? ''}}</td>
                            {{-- <td>{{ _formatPhoneNumber($agency->phone) ?? ''}}</td> --}}
                            <td>{{$agency->address ?? ''}},{{$agency->city ?? ''}},{{$agency->country ?? ''}}</td>
                            <td><div class="row">
                                    <a href="{{route('employment-agency.view', $agency->id)}}">
                                        <i style="color: green;" class="col fa fa-eye"></i>
                                    </a>
                                    <a href="{{route('employment-agencies.edit', $agency->id)}}">
                                        <i style="color: green;" class="col far fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('employment-agencies.destroy', $agency->id) }}" id="delete-form-{{ $agency->id }}">
                                        @csrf
                                        @method('Delete')
                                        <i style="color: red" type="button" onclick="confirmDelete({{ $agency->id }})" class="col fas fa-trash"></i>
                                    </form>
                                </div></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

</div>

@include('StaffingCompany.partials._footer')

<!-- Page specific script -->
<script>
    $(function () {
        $("#example1").DataTable({
            "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "order": [[ 0, "desc" ]],
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    function confirmDelete(itemId) {
        if (confirm('Are you sure you want to delete this?')) {
            document.getElementById('delete-form-' + itemId).submit();
        }
    }
</script>
</body>
</html>
