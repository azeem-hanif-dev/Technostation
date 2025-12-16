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
                    <h3> {{ __('Staffing_Company/staff/s_index.staff') }}</h3>
                    <a href="{{ route('personnels.create') }}">
                        <button
                            class="btn btn-success float-right">{{ __('Staffing_Company/staff/s_index.add_new') }}</button>
                    </a>
                    <button class="btn btn-primary float-right" id="send-email-btn" style="margin-right: 10px;">
                        {{ __('Staffing_Company/staff/s_index.send_credentials') }}
                    </button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox"
                                        id="select-all">{{ __('Staffing_Company/staff/s_index.all') }}</th>
                                <th>Id</th>
                                <th>{{ __('Staffing_Company/staff/s_index.first_name') }}</th>
                                <th>{{ __('Staffing_Company/staff/s_index.last_name') }}</th>
                                <th>{{ __('Staffing_Company/staff/s_index.bsn') }}</th>
                                <th>{{ __('Staffing_Company/staff/s_index.emp_agency') }}</th>
                                {{-- <th>{{ __('Staffing_Company/staff/s_index.mobile') }}</th> --}}
                                {{-- <th>{{ __('Staffing_Company/staff/s_index.email') }}</th> --}}
                                <th>{{ __('Staffing_Company/staff/s_index.job') }}</th>
                                <th>{{ __('Staffing_Company/staff/s_index.option') }}</th>

                                {{-- <th>{{ __('Staffing_Company/staff/s_index.name') }}</th>
                        <th>{{ __('Staffing_Company/staff/s_index.email') }}</th>
                        <th>{{ __('Staffing_Company/staff/s_index.vca_number') }}</th>
                        <th>{{ __('Staffing_Company/staff/s_index.mobile') }}</th>
                        <th>{{ __('Staffing_Company/staff/s_index.nationailty') }}</th>
                        <th>{{ __('Staffing_Company/staff/s_index.emp_agency') }}</th>
                        <th>{{ __('Staffing_Company/staff/s_index.address') }}</th>
                        <th>Online</th>
                         <th>{{ __('Staffing_Company/staff/s_index.job') }}</th>
                   
                     <th>{{ __('Staffing_Company/staff/s_index.option') }}</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($personnels as $personnel)
                                <tr>
                                    {{-- <td>
                                        <input type="checkbox" name="personnel_ids[]" class="select-row"
                                            value="{{ $personnel->id }}">
                                    </td> --}}
                                    <td>
                                        <input type="checkbox" name="personnel_ids[]" class="select-row"
                                            value="{{ $personnel->id }}">
                                    </td>
                                    <td>{{ $personnel->id }}</td>
                                    <td>{{ $personnel->first_name ?? '' }}</td>
                                    <td> {{ $personnel->last_name ?? '' }}</td>
                                    <td>{{ $personnel->social_security_number ?? '' }}</td>
                                    <td>{{ $personnel->agency->name ?? '' }}</td>
                                    {{-- <td>{{$personnel->mobile ?? ''}}</td> --}}
                                    {{-- <td>{{$personnel->email ?? ''}}</td> --}}
                                    <td style="text-align: center">
                                        @if ($personnel->active)
                                            <span
                                                style="color: white; padding: 3px; font-size: 12px; background-color: green">{{ __('Staffing_Company/staff/s_index.active') }}</span>
                                        @else
                                            <span
                                                style="color: white; padding: 3px; font-size: 12px; background-color: red">{{ __('Staffing_Company/staff/s_index.inactive') }}</span>
                                        @endif
                                    </td>
                                    {{-- <td>{{$personnel->city ?? ''}}</td> --}}
                                    {{-- <td>{{$personnel->nationality ?? ''}}</td>
                            <td>{{$personnel->social_security_number ?? ''}}</td>
                            <td>{{$personnel->agency->name ?? ''}}</td>
                            <td>{{$personnel->city ?? ''}} {{$personnel->adress ?? ''}}</td> --}}
                                    {{-- 
                            @if ($personnel->user)
                                <td style="text-align: center">{{ $personnel->user->loggedIn=='1'? 'Yes':'No' }}</td>
                                @if ($personnel->user->loggedIn != null)
                                <td style="text-align: center"><p id="last-seen-online-{{ $personnel->id }}" data-last-logged-in="{{ $personnel->user->lastLoggedIn }}"></p></td>
                                @else
                                    <td style="text-align: center"><small>🔴</small></td>
                                @endif
                            @else
                                <td style="text-align: center"><small>🔴</small></td>  <!-- Display a default value or message if user relation doesn't exist -->
                            @endif


                            <td style="text-align: center">@if ($personnel->active)
                                    <span style="color: white; padding: 3px; font-size: 12px; background-color: green">{{ __('Staffing_Company/staff/s_index.active') }}</span>
                                @else
                                    <span style="color: white; padding: 3px; font-size: 12px; background-color: red">{{ __('Staffing_Company/staff/s_index.inactive') }}</span>
                                @endif</td> --}}
                                    <td>
                                        <div class="row">
                                            <a href="{{ route('employee-history', $personnel->id) }}">
                                                <i style="color: green;" class="col fa fa-history"></i>
                                            </a>
                                            <a href="{{ route('personnels.show', $personnel->id) }}">
                                                <i style="color: green;" class="col fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('personnels.edit', $personnel->id) }}">
                                                <i style="color: green;" class="col far fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('personnels.destroy', $personnel->id) }}"
                                                id="delete-form-{{ $personnel->id }}">
                                                @csrf
                                                @method('Delete')
                                                <i style="color: red" type="button"
                                                    onclick="confirmDelete({{ $personnel->id }})"
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

    @include('StaffingCompany.partials._footer')

    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "lengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                //"order": [[ 0, "desc" ]],
                "order": [],
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "ordering": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],


            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            // });

            $(document).on('change', '#select-all', function() {
                $('.select-row').prop('checked', $(this).prop('checked'));
            });

            // Send Email button
            $(document).on('click', '#send-email-btn', function() {
                let ids = [];
                $('.select-row:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length === 0) {
                    alert("Please select at least one personnel.");
                    return;
                }

                //console.log("IDs being sent to controller:", ids);

                $.ajax({
                    url: "{{ route('personnels.send-email') }}",
                    type: "POST",
                    data: {
                        ids: ids,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert("Something went wrong.");
                        console.log(xhr.responseText);
                    }
                });
            });
        });

        // $(document).on('click', '#send-email-btn', function() {
        //     let ids = [];

        //     $('.select-row:checked').each(function() {
        //         ids.push($(this).val());
        //     });

        //     if (ids.length === 0) {
        //         alert("Please select at least one personnel.");
        //         return;
        //     }

        //     $.ajax({
        //         url: "{{ route('personnels.send-email') }}",
        //         type: "POST",
        //         data: {
        //             ids: ids,
        //             _token: "{{ csrf_token() }}"
        //         },
        //         success: function(response) {
        //             alert(response.message);
        //             location.reload();
        //         }
        //     });
        // });


        function confirmDelete(itemId) {
            if (confirm('Are you sure you want to delete this?')) {
                document.getElementById('delete-form-' + itemId).submit();
            }
        }

        // Online Functionality
        /// RUN AFTER DATATABLE IS LOADED but sorting is lost
        //     $("#example1").on('draw.dt', function() {
        //     // Get all elements with the data-last-logged-in attribute
        //     const elements = document.querySelectorAll('[data-last-logged-in]');

        //     console.log('xxx', elements.length);

        //     // Loop through each element and calculate the time difference
        //     elements.forEach(element => {
        //         calculateTimeDifference(element);
        //     });
        // })

        function calculateTimeDifference(element) {
            const lastLoggedIn = element.getAttribute('data-last-logged-in');

            if (lastLoggedIn !== '') {
                const lastLoggedInDate = new Date(lastLoggedIn);

                // Get current time in Netherlands
                const now = new Date(new Date().toLocaleString('en-US', {
                    timeZone: 'Europe/Amsterdam'
                }));

                const timeDiff = now - lastLoggedInDate;
                const timeDiffHours = Math.floor(timeDiff / 3600000);

                if (timeDiffHours > 168) {
                    element.innerHTML = '<small>🔴</small>';
                } else if (timeDiffHours > 8) {
                    element.innerHTML = `<small>${timeDiffHours}h 🔴</small>`;
                } else {
                    element.innerHTML = `<small>${timeDiffHours}h 🟢</small>`;
                }
            } else {
                element.innerHTML = '<small>🔴</small>';
            }
        }
        const elements = document.querySelectorAll('[data-last-logged-in]');

        console.log('xxx', elements.length);

        // Loop through each element and calculate the time difference
        elements.forEach(element => {
            calculateTimeDifference(element);
        });
    </script>
</body>

</html>
