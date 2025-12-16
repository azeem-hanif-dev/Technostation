<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div class="container p-4">

                <!-- Tabs -->
                <ul class="nav nav-tabs" id="activityTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="activity-tab" data-toggle="tab" href="#activity" role="tab">
                            {{ __('Staffing_Company/offers/create.tab_handover') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="retail-tab" data-toggle="tab" href="#retail" role="tab">
                            {{ __('Staffing_Company/offers/create.tab_retail') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="keeth-tab" data-toggle="tab" href="#keeth" role="tab">
                            {{ __('Staffing_Company/offers/create.tab_keet') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="container-tab" href="{{ route('quotations.index') }}">
                            {{ __('Staffing_Company/offers/create.tab_container') }}
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3">

                    <!-- First Form (Handover Cleaning) -->
                    <div class="tab-pane fade show active" id="activity" role="tabpanel">
                        <h2>{{ __('Staffing_Company/offers/create.handover_heading') }}</h2>
                        <form action="{{ route('offers.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6 form-group">
                                    <label>{{ __('Staffing_Company/offers/create.date') }}</label>
                                    <input type="date" name="date" class="form-control" required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.title') }}</label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="{{ __('Staffing_Company/offers/create.title') }}" required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.subject') }}</label>
                                    <input type="text" name="subject" class="form-control"
                                        placeholder="{{ __('Staffing_Company/offers/create.subject') }}" required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.project') }}</label>
                                    <select name="project_id" class="form-control" required>
                                        <option value="">{{ __('Staffing_Company/offers/create.select_project') }}</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6 form-group">
                                    <label>{{ __('Staffing_Company/offers/create.total_price') }}</label>
                                    <input type="number" step="0.01" name="total_price" class="form-control"
                                        placeholder="{{ __('Staffing_Company/offers/create.total_price') }}" required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.notes') }}</label>
                                    <textarea class="form-control" name="notes" rows="5"
                                        placeholder="{{ __('Staffing_Company/offers/create.notes_placeholder') }}"></textarea>
                                </div>
                            </div>

                            <!-- Scope Section -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>{{ __('Staffing_Company/offers/create.scope') }}</label>
                                    <div id="scopeWrapper">
                                        <div class="d-flex mb-2">
                                            <select class="form-control scope-select" style="width:40%">
                                                <option value="{{ __('Staffing_Company/offers/create.scope_toiletgroepen') }}">
                                                    {{ __('Staffing_Company/offers/create.scope_toiletgroepen') }}
                                                </option>
                                                <option value="{{ __('Staffing_Company/offers/create.scope_kozijnen') }}">
                                                    {{ __('Staffing_Company/offers/create.scope_kozijnen') }}
                                                </option>
                                                <option value="{{ __('Staffing_Company/offers/create.scope_separatie') }}">
                                                    {{ __('Staffing_Company/offers/create.scope_separatie') }}
                                                </option>
                                                <option value="{{ __('Staffing_Company/offers/create.scope_vloeren') }}">
                                                    {{ __('Staffing_Company/offers/create.scope_vloeren') }}
                                                </option>
                                                <option value="{{ __('Staffing_Company/offers/create.scope_vensterbanken') }}">
                                                    {{ __('Staffing_Company/offers/create.scope_vensterbanken') }}
                                                </option>
                                                <option value="{{ __('Staffing_Company/offers/create.scope_algemene_ruimte') }}">
                                                    {{ __('Staffing_Company/offers/create.scope_algemene_ruimte') }}
                                                </option>
                                            </select>

                                            <textarea class="form-control ml-2 scope-desc"
                                                placeholder="{{ __('Staffing_Company/offers/create.scope_placeholder') }}"></textarea>

                                            <button type="button" class="btn btn-success ml-2 add-scope"
                                                data-target="handover">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <input type="hidden" name="scope" id="scopeJsonHandover">
                                    <ul id="addedScopesHandover" class="mt-2"></ul>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row">
                                <div class="col-lg-12 text-right mt-3">
                                    <a href="{{ url()->previous() }}" class="btn btn-danger" style="width: 100px;">
                                        {{ __('Staffing_Company/offers/create.btn_cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary"
                                        style="width: 120px; margin-left: 10px;">
                                        {{ __('Staffing_Company/offers/create.btn_generate_pdf') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Second Form (Retail Bouw) -->
                    <div class="tab-pane fade" id="retail" role="tabpanel">
                        <h2>{{ __('Staffing_Company/offers/create.retail_heading') }}</h2>
                        <form action="{{ route('retail-offers.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>{{ __('Staffing_Company/offers/create.prices_valid_until') }}</label>
                                    <input type="date" name="date" class="form-control" required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.project') }}</label>
                                    <select name="project_id" class="form-control" required>
                                        <option value="">{{ __('Staffing_Company/offers/create.select_project') }}</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.notes') }}</label>
                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="col-md-6 form-group">

                                    <label>{{ __('Staffing_Company/offers/create.service_type') }}</label>
                                    <select name="service_type" class="form-control" required>
                                        <option value="">{{ __('Staffing_Company/offers/create.service_type_select') }}</option>
                                        <option value="traffic_controller">
                                            {{ __('Staffing_Company/offers/create.service_type_traffic_controller') }}
                                        </option>
                                        <option value="construction_helper">
                                            {{ __('Staffing_Company/offers/create.service_type_construction_helper') }}
                                        </option>
                                    </select>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.construction_price') }}</label>
                                    <input type="number" step="0.01" name="construction_price"
                                        class="form-control" required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.traffic_controllers_price') }}</label>
                                    <input type="number" step="0.01" name="traffic_controllers_price"
                                        class="form-control" required>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 text-right mt-3">
                                    <a href="{{ url()->previous() }}" class="btn btn-danger" style="width: 100px;">
                                        {{ __('Staffing_Company/offers/create.btn_cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary"
                                        style="width: 120px; margin-left: 10px;">
                                        {{ __('Staffing_Company/offers/create.btn_generate_pdf') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Third Form (Site Hut Maintenance / Keetonderhoud) -->
                    <div class="tab-pane fade" id="keeth" role="tabpanel">
                        <h2>{{ __('Staffing_Company/offers/create.site_heading') }}</h2>
                        <form action="{{ route('site-offers.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>{{ __('Staffing_Company/offers/create.date') }}</label>
                                    <input type="date" name="date" class="form-control" required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.title') }}</label>
                                    <input type="text" name="title" class="form-control" required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.project') }}</label>
                                    <select name="project_id" class="form-control" required>
                                        <option value="">{{ __('Staffing_Company/offers/create.select_project') }}</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Scope Section -->
                                    <div class="form-group mt-3">
                                        <label>{{ __('Staffing_Company/offers/create.scope') }}</label>
                                        <div id="scopeWrapperKeet">
                                            <div class="d-flex mb-2">
                                                <select class="form-control scope-select" style="width:40%">
                                                    <option
                                                        value="{{ __('Staffing_Company/offers/create.scope_schaftkeet') }}">
                                                        {{ __('Staffing_Company/offers/create.scope_schaftkeet') }}
                                                    </option>
                                                    <option value="{{ __('Staffing_Company/offers/create.scope_toilet') }}">
                                                        {{ __('Staffing_Company/offers/create.scope_toilet') }}
                                                    </option>
                                                    <option
                                                        value="{{ __('Staffing_Company/offers/create.scope_kantoor') }}">
                                                        {{ __('Staffing_Company/offers/create.scope_kantoor') }}
                                                    </option>
                                                </select>

                                                <textarea class="form-control ml-2 scope-desc"
                                                    placeholder="{{ __('Staffing_Company/offers/create.scope_placeholder') }}"></textarea>

                                                <button type="button" class="btn btn-success ml-2 add-scope"
                                                    data-target="keet">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="scope" id="scopeJsonKeet">
                                        <ul id="addedScopesKeet" class="mt-2"></ul>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6 form-group">
                                    <label>{{ __('Staffing_Company/offers/create.unit') }}</label>
                                    <input type="text" name="unit" class="form-control" required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.price_hour') }}</label>
                                    <input type="number" step="0.01" name="price_hour" class="form-control"
                                        required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.desc_hour') }}</label>
                                    <textarea class="form-control" name="desc_hour" rows="3"></textarea>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.price_time') }}</label>
                                    <input type="number" step="0.01" name="price_time" class="form-control"
                                        required>

                                    <label class="mt-3">{{ __('Staffing_Company/offers/create.desc_time') }}</label>
                                    <textarea class="form-control" name="desc_time" rows="3"></textarea>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="row">
                                <div class="col-lg-12 text-right mt-3">
                                    <a href="{{ url()->previous() }}" class="btn btn-danger"
                                        style="width: 100px;">
                                        {{ __('Staffing_Company/offers/create.btn_cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary"
                                        style="width: 120px; margin-left: 10px;">
                                        {{ __('Staffing_Company/offers/create.btn_generate_pdf') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div> <!-- End Tab Content -->
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-scope') || e.target.closest('.add-scope')) {
                let btn = e.target.closest('.add-scope');
                let target = btn.getAttribute('data-target');
                let wrapper = btn.closest('.d-flex');
                let select = wrapper.querySelector('.scope-select');
                let desc = wrapper.querySelector('.scope-desc');

                let scopeJson = document.getElementById('scopeJson' + target.charAt(0).toUpperCase() + target.slice(
                    1));
                let addedScopes = document.getElementById('addedScopes' + target.charAt(0).toUpperCase() + target
                    .slice(1));

                // Parse existing scope array for this form
                let scopeData = scopeJson.value ? JSON.parse(scopeJson.value) : [];

                if (select.value && desc.value) {
                    let existingScope = scopeData.find(s => s.name === select.value);

                    if (existingScope) {
                        existingScope.descriptions.push(desc.value);

                        let existingLi = addedScopes.querySelector(`li[data-scope="${select.value}"] ul`);
                        let descLi = document.createElement('li');
                        descLi.textContent = desc.value;
                        existingLi.appendChild(descLi);
                    } else {
                        scopeData.push({
                            name: select.value,
                            descriptions: [desc.value]
                        });

                        let li = document.createElement('li');
                        li.setAttribute('data-scope', select.value);
                        li.innerHTML = `<strong>${select.value}</strong> <ul><li>${desc.value}</li></ul>`;
                        addedScopes.appendChild(li);
                    }

                    scopeJson.value = JSON.stringify(scopeData);
                    desc.value = "";
                } else {
                    alert("{{ __('Staffing_Company/offers/create.scope_alert') }}");
                }
            }
        });
    </script>

    @include('StaffingCompany.partials._footer')
</body>

</html>
