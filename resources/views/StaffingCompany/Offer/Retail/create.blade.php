{{-- <!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div class="container p-4"> --}}

{{-- Retail Bouw (no tabs) --}}
{{-- <h2>{{ __('Staffing_Company/offers/create.retail_bouw') }}</h2>

                <form action="{{ route('retail-offers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{ __('Staffing_Company/offers/create.prices_valid') }}</label>
                            <input type="date" name="date" class="form-control" required>

                            <label class="mt-3">{{ __('Staffing_Company/offers/create.project') }}:*</label>
                            <select name="project_id" class="form-control searchable-select" required>
                                <option value=""></option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>

                            <label class="mt-3">{{ __('Staffing_Company/offers/create.notes') }}:</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>{{ __('Staffing_Company/offers/create.service_type') }}:*</label> --}}
{{-- <input type="text" name="service_type" class="form-control" required> --}}
{{-- <select name="service_type" class="form-control" required>
                                <option value="" disabled selected>Select service type</option>
                                <option value="Traffic Controller">Traffic Controller</option>
                                <option value="Construction Helper">Construction Helper</option>
                                <option value="Both">Both</option>
                            </select>


                            <label
                                class="mt-3">{{ __('Staffing_Company/offers/create.construction_price') }}:*</label>
                            <input type="number" step="0.01" name="construction_price" class="form-control"
                                required>

                            <label class="mt-3">{{ __('Staffing_Company/offers/create.traffic_price') }}:*</label>
                            <input type="number" step="0.01" name="traffic_controllers_price" class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 text-right mt-3">
                            <a href="{{ url()->previous() }}" class="btn btn-danger" style="width: 100px;">
                                {{ __('Staffing_Company/offers/create.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary" style="width: 120px; margin-left: 10px;">
                                {{ __('Staffing_Company/offers/create.generate_pdf') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>

    @include('StaffingCompany.partials._footer') --}}

<!-- jQuery + Select2 -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            line-height: 38px !important;
            padding: 2px 6px !important;
            transform: translateY(29px);
        }

        .select2-selection__rendered {
            line-height: 38px !important;
        }

        .select2-selection__arrow {
            height: 38px !important;
        }
    </style>

    <script>
        $(function() {
            $('.searchable-select').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                    width: '100%',
                    placeholder: "{{ __('Staffing_Company/offers/create.enter_project') }}"
                });
            });
        });
    </script>

</body>

</html> --}}




































<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div class="container p-4">

                {{-- Retail Bouw --}}
                <h2>{{ __('Staffing_Company/offers/create.retail_bouw') }}</h2>

                <form action="{{ route('retail-offers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{ __('Staffing_Company/offers/create.prices_valid') }}</label>
                            <input type="date" name="date" class="form-control" required>

                            <label class="mt-3">{{ __('Staffing_Company/offers/create.project') }}:*</label>
                            <select name="project_id" class="form-control searchable-select" required>
                                <option value=""></option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>

                            <label>{{ __('Staffing_Company/offers/create.notes') }}:</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>{{ __('Staffing_Company/offers/create.service_type') }}:*</label>
                            <select name="service_type" id="service_type" class="form-control" required>
                                <option value="" disabled selected>
                                    {{ __('Staffing_Company/offers/create.select_service_type') }}</option>
                                <option value="Traffic Controller">
                                    {{ __('Staffing_Company/offers/create.traffic_controller') }}</option>
                                <option value="Construction Helper">
                                    {{ __('Staffing_Company/offers/create.construction_helper') }}</option>
                                <option value="Both">{{ __('Staffing_Company/offers/create.both') }}
                                </option>
                            </select>

                            {{-- Construction Price --}}
                            <div id="construction_price_container" class="mt-3" style="display: none;">
                                <label>{{ __('Staffing_Company/offers/create.construction_price') }}:*</label>
                                <input type="number" step="0.01" name="construction_price" id="construction_price"
                                    class="form-control">
                            </div>

                            {{-- Traffic Controller Price --}}
                            <div id="traffic_price_container" class="mt-3" style="display: none;">
                                <label>{{ __('Staffing_Company/offers/create.traffic_price') }}:*</label>
                                <input type="number" step="0.01" name="traffic_controllers_price"
                                    id="traffic_controllers_price" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 text-right mt-3">
                            <a href="{{ url()->previous() }}" class="btn btn-danger" style="width: 100px;">
                                {{ __('Staffing_Company/offers/create.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary" style="width: 120px; margin-left: 10px;">
                                {{ __('Staffing_Company/offers/create.generate_pdf') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>
    @include('StaffingCompany.partials._footer')

    <!-- jQuery + Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            line-height: 38px !important;
            padding: 2px 6px !important;
            transform: translateY(29px);
        }

        .select2-selection__rendered {
            line-height: 38px !important;
        }

        .select2-selection__arrow {
            height: 38px !important;
        }
    </style>

    <script>
        $(function() {
            // Initialize Select2
            $('.searchable-select').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                    width: '100%',
                    placeholder: "{{ __('Staffing_Company/offers/create.enter_project') }}"
                });
            });

            // Handle service type changes
            $('#service_type').on('change', function() {
                const type = $(this).val();
                const constructionContainer = $('#construction_price_container');
                const trafficContainer = $('#traffic_price_container');
                const construction = $('#construction_price');
                const traffic = $('#traffic_controllers_price');

                // Reset both
                constructionContainer.hide();
                trafficContainer.hide();
                construction.prop('required', false).val('');
                traffic.prop('required', false).val('');

                // Show relevant fields
                if (type === 'Traffic Controller') {
                    trafficContainer.show();
                    traffic.prop('required', true);
                } else if (type === 'Construction Helper') {
                    constructionContainer.show();
                    construction.prop('required', true);
                } else if (type === 'Both') {
                    constructionContainer.show();
                    trafficContainer.show();
                    construction.prop('required', true);
                    traffic.prop('required', true);
                }
            });
        });
    </script>
</body>

</html>
