<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    @include('StaffingCompany.partials._navBar')
    @include('StaffingCompany.partials._sideBar')

    <div class="content-wrapper">
      <div class="container p-4">

        {{-- Site Maintenance (no tabs) --}}
        <h2>{{ __('Staffing_Company/offers/create.site_maintenance') }}</h2>

        <form action="{{ route('site-offers.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-6 form-group">
              <label>{{ __('Staffing_Company/offers/create.date') }}:*</label>
              <input type="date" name="date" class="form-control" required>

              <label class="mt-3">{{ __('Staffing_Company/offers/create.title') }}:*</label>
              <input type="text" name="title" class="form-control" required>

              <label class="mt-3">{{ __('Staffing_Company/offers/create.project') }}:*</label>
              <select name="project_id" class="form-control searchable-select" required>
                <option value=""></option>
                @foreach ($projects as $project)
                  <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
              </select>

              <label class="mt-3">{{ __('Staffing_Company/offers/create.scope') }}:</label>
              <div id="scopeWrapperKeet">
                <div class="d-flex mb-2">
                  <select class="form-control scope-select" style="width:40%">
                    <option value="Schoonmaken schaftkeet">Schoonmaken schaftkeet</option>
                    <option value="Schoonmaken toilet">Schoonmaken toilet</option>
                    <option value="Schoonmaken uitvoerdersruimte / projectleiderskantoor/ kantoor en vergaderruimte">
                      Schoonmaken uitvoerdersruimte / projectleiderskantoor/ kantoor en vergaderruimte
                    </option>
                  </select>

                  <textarea class="form-control ml-2 scope-desc"
                            placeholder="{{ __('Staffing_Company/offers/create.enter_description') }}"></textarea>

                  <button type="button" class="btn btn-success ml-2 add-scope" data-target="keet">
                    <i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>
              <input type="hidden" name="scope" id="scopeJsonKeet">
              <ul id="addedScopesKeet" class="mt-2"></ul>
            </div>

            <div class="col-md-6 form-group">
              <label>{{ __('Staffing_Company/offers/create.unit') }}:*</label>
              <input type="text" name="unit" class="form-control" required>

              <label class="mt-3">{{ __('Staffing_Company/offers/create.price_hour') }} (€):*</label>
              <input type="number" step="0.01" name="price_hour" class="form-control" required>

              <label class="mt-3">{{ __('Staffing_Company/offers/create.description') }}:</label>
              <textarea class="form-control" name="desc_hour" rows="3"></textarea>

              <label class="mt-3">{{ __('Staffing_Company/offers/create.price_time') }} (€):*</label>
              <input type="number" step="0.01" name="price_time" class="form-control" required>

              <label class="mt-3">{{ __('Staffing_Company/offers/create.description') }}:</label>
              <textarea class="form-control" name="desc_time" rows="3"></textarea>
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
    .select2-selection__rendered { line-height: 38px !important; }
    .select2-selection__arrow { height: 38px !important; }
  </style>

  <script>
    // init select2 per element
    $(function () {
      $('.searchable-select').each(function () {
        $(this).select2({
          dropdownParent: $(this).parent(),
          width: '100%',
          placeholder: "{{ __('Staffing_Company/offers/create.enter_project') }}"
        });
      });
    });

    // scope add logic (target = 'keet')
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('add-scope') || e.target.closest('.add-scope')) {
        let btn = e.target.closest('.add-scope');
        let target = btn.getAttribute('data-target'); // 'keet'
        let wrapper = btn.closest('.d-flex');
        let select = wrapper.querySelector('.scope-select');
        let desc = wrapper.querySelector('.scope-desc');

        let scopeJson = document.getElementById('scopeJson' + target.charAt(0).toUpperCase() + target.slice(1));
        let addedScopes = document.getElementById('addedScopes' + target.charAt(0).toUpperCase() + target.slice(1));

        let scopeData = scopeJson.value ? JSON.parse(scopeJson.value) : [];

        if (select.value && desc.value) {
          let existingScope = scopeData.find(s => s.name === select.value);

          if (existingScope) {
            existingScope.descriptions.push(desc.value);
            let existingUl = addedScopes.querySelector(`li[data-scope="${select.value}"] ul`);
            let descLi = document.createElement('li');
            descLi.textContent = desc.value;
            existingUl.appendChild(descLi);
          } else {
            scopeData.push({ name: select.value, descriptions: [desc.value] });
            let li = document.createElement('li');
            li.setAttribute('data-scope', select.value);
            li.innerHTML = `<strong>${select.value}</strong> <ul><li>${desc.value}</li></ul>`;
            addedScopes.appendChild(li);
          }

          scopeJson.value = JSON.stringify(scopeData);
          desc.value = "";
        } else {
          alert("Please select scope and enter description");
        }
      }
    });
  </script>

</body>
</html>
