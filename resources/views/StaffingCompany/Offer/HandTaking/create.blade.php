<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div class="container p-4">

                <h2>{{ __('Staffing_Company/offers/create.handover_cleaning') }}</h2>

                <form action="{{ route('offers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Left -->
                        <div class="col-md-6 form-group">
                            <label>{{ __('Staffing_Company/offers/create.date') }}:*</label>
                            <input type="date" name="date" class="form-control" required>

                            <label class="mt-3">{{ __('Staffing_Company/offers/create.title') }}:*</label>
                            <input type="text" name="title" class="form-control"
                                placeholder="{{ __('Staffing_Company/offers/create.enter_title') }}" required>

                            <label class="mt-3">{{ __('Staffing_Company/offers/create.subject') }}:*</label>
                            <input type="text" name="subject" class="form-control"
                                placeholder="{{ __('Staffing_Company/offers/create.enter_subject') }}" required>

                            <label class="mt-3">{{ __('Staffing_Company/offers/create.project') }}:*</label>
                            <select name="project_id" class="form-control searchable-select" required>
                                <option value=""></option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>

                            {{-- <label class="mt-3">{{ __('Staffing_Company/offers/create.scope') }}:</label>
                            <div id="scopeWrapper">
                                <div class="d-flex mb-2">
                                    <select class="form-control scope-select" style="width:40%">
                                        <option value="Toiletgroepen 2 per verdieping">Toiletgroepen 2 per verdieping
                                        </option>
                                        <option value="Gevelkozijnen en ramen">Gevelkozijnen en ramen</option>
                                        <option value="Separatie glas, deuren">Separatie glas, deuren</option>
                                        <option value="Vloeren">Vloeren</option>
                                        <option value="Vensterbanken en overige">Vensterbanken en overige</option>
                                        <option value="Algemene ruimte, lifthallen en traphuizen">Algemene ruimte,
                                            lifthallen en traphuizen</option>
                                    </select>

                                    <textarea class="form-control ml-2 scope-desc"
                                        placeholder="{{ __('Staffing_Company/offers/create.enter_description') }}"></textarea>

                                    <button type="button" class="btn btn-success ml-2 add-scope"
                                        data-target="handover">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="scope" id="scopeJsonHandover">
                            <ul id="addedScopesHandover" class="mt-2"></ul> --}}
                        </div>

                        <!-- Right -->
                        <div class="col-md-6 form-group">
                            {{-- <label>{{ __('Staffing_Company/offers/create.total_price') }} (€):*</label>
              <input type="number" step="0.01" name="total_price" class="form-control" placeholder="{{ __('Staffing_Company/offers/create.enter_price') }}" required> --}}

                            <label style="margin-top: -4px;">{{ __('Staffing_Company/offers/create.notes') }}:</label>
                            <textarea class="form-control" name="notes" rows="5"
                                placeholder="{{ __('Staffing_Company/offers/create.optional_notes') }}"></textarea>

                            <label class="mt-2">{{ __('Staffing_Company/offers/create.scope') }}:</label>
                            <div id="scopeWrapper">
                                <div class="d-flex mb-2">
                                    <select class="form-control scope-select" style="width:40%">
                                        <option value="Toiletgroepen 2 per verdieping">Toiletgroepen 2 per verdieping
                                        </option>
                                        <option value="Gevelkozijnen en ramen">Gevelkozijnen en ramen</option>
                                        <option value="Separatie glas, deuren">Separatie glas, deuren</option>
                                        <option value="Vloeren">Vloeren</option>
                                        <option value="Vensterbanken en overige">Vensterbanken en overige</option>
                                        <option value="Algemene ruimte, lifthallen en traphuizen">Algemene ruimte,
                                            lifthallen en traphuizen</option>
                                    </select>

                                    <textarea class="form-control ml-2 scope-desc"
                                        placeholder="{{ __('Staffing_Company/offers/create.enter_description') }}"></textarea>

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

                    <div class="row">
                        <div class="col-lg-12 text-right mt-3">
                            <a href="{{ url()->previous() }}" class="btn btn-danger"
                                style="width:100px;">{{ __('Staffing_Company/offers/create.cancel') }}</a>
                            <button type="submit" class="btn btn-primary"
                                style="width:120px; margin-left:10px;">{{ __('Staffing_Company/offers/create.generate_pdf') }}</button>
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
            $('.searchable-select').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                    width: '100%',
                    placeholder: "{{ __('Staffing_Company/offers/create.enter_project') }}"
                });
            });
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-scope') || e.target.closest('.add-scope')) {
                let btn = e.target.closest('.add-scope');
                let target = 'handover';
                let wrap = btn.closest('.d-flex');
                let sel = wrap.querySelector('.scope-select');
                let desc = wrap.querySelector('.scope-desc');
                let scopeJson = document.getElementById('scopeJsonHandover');
                let added = document.getElementById('addedScopesHandover');
                let data = scopeJson.value ? JSON.parse(scopeJson.value) : [];
                if (sel.value && desc.value) {
                    let ex = data.find(s => s.name === sel.value);
                    if (ex) {
                        ex.descriptions.push(desc.value);
                        added.querySelector(`li[data-scope="${sel.value}"] ul`).append(Object.assign(document
                            .createElement('li'), {
                                textContent: desc.value
                            }));
                    } else {
                        data.push({
                            name: sel.value,
                            descriptions: [desc.value]
                        });
                        let li = document.createElement('li');
                        li.setAttribute('data-scope', sel.value);
                        li.innerHTML = `<strong>${sel.value}</strong> <ul><li>${desc.value}</li></ul>`;
                        added.appendChild(li);
                    }
                    scopeJson.value = JSON.stringify(data);
                    desc.value = "";
                } else {
                    alert("Please select scope and enter description");
                }
            }
        });
    </script>

</body>

</html>
