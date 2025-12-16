<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div class="content-wrapper">
            <div class="container p-4">

                <h2>{{ __('Staffing_Company/side_bar.multi_service') }}</h2>

                <form action="{{ route('multi-service.store') }}" method="POST" id="multiServiceForm">
                    @csrf
                    <div class="row">
                        <!-- Left -->
                        <div class="col-md-6 form-group">
                            <label>{{ __('Staffing_Company/offers/create.date') }}:*</label>
                            <input type="date" name="date" class="form-control" required>

                            <label class="mt-3">{{ __('Staffing_Company/offers/create.title') }}:*</label>
                            <input type="text" name="title" class="form-control"
                                placeholder="{{ __('Staffing_Company/offers/create.enter_title') }}" required>

                            <label class="mt-3">{{ __('Staffing_Company/offers/create.customer') }}:*</label>
                            <select name="customer_id" class="form-control searchable-select" required>
                                <option value=""></option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>

                            {{-- Service picker --}}
                            <label class="mt-4 d-flex align-items-center justify-content-between">
                                <span>{{ __('Staffing_Company/offers/create.service') }}</span>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                    data-target="#addServiceModal">
                                    + {{ __('Add Service') }}
                                </button>
                            </label>

                            <div class="d-flex">
                                <select id="serviceSelect" class="form-control" style="width:60%">
                                    @foreach ($services as $service)
                                        <option value="{{ $service->name }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>

                                <input id="servicePrice" type="number" step="0.01" class="form-control ml-2"
                                    style="width:30%" placeholder="{{ __('Price (€)') }}">

                                <button type="button" id="addServiceBtn" class="btn btn-success ml-2">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>

                            <ul id="addedServices" class="mt-3"></ul>
                            <input type="hidden" name="scope" id="scopeJson">
                        </div>

                        <!-- Right -->
                        <div class="col-md-6 form-group">
                            <label>{{ __('Staffing_Company/offers/create.total_price') }} (€):*</label>
                            <input id="totalPrice" type="number" step="0.01" name="total_price" class="form-control"
                                placeholder="{{ __('Staffing_Company/offers/create.enter_price') }}" required readonly>

                            <label class="mt-3">{{ __('Staffing_Company/offers/create.notes') }}:</label>
                            <textarea class="form-control" name="notes" rows="5"
                                placeholder="{{ __('Staffing_Company/offers/create.optional_notes') }}"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 text-right mt-3">
                            <a href="{{ url()->previous() }}" class="btn btn-danger" style="width:100px;">
                                {{ __('Staffing_Company/offers/create.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary" style="width:120px; margin-left:10px;">
                                {{ __('Staffing_Company/offers/create.generate_pdf') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Add Service Modal --}}
    <div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addServiceForm" method="POST" action="{{ route('services.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addServiceModalLabel">{{ __('Add New Service') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <label>{{ __('Service Name') }}</label>
                        <input id="newServiceName" name="name" type="text" class="form-control"
                            placeholder="{{ __('Enter service name') }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary" id="saveServiceBtn">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('StaffingCompany.partials._footer')

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ✅ Bootstrap JS (Required for modal auto-hide) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 -->
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

        #addedServices li {
            margin-bottom: 6px;
        }

        .chip {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 16px;
            background: #f1f1f1;
        }

        .chip .remove {
            cursor: pointer;
            margin-left: 6px;
        }
    </style>

    <script>
        // Initialize select2
        $(function() {
            $('.searchable-select').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                    width: '100%',
                    placeholder: "{{ __('Staffing_Company/offers/create.enter_customer') }}"
                });
            });
        });

        // ====== Offer service management ======
        let services = [];
        const serviceSelect = document.getElementById('serviceSelect');
        const servicePrice = document.getElementById('servicePrice');
        const addedList = document.getElementById('addedServices');
        const scopeJson = document.getElementById('scopeJson');
        const totalPriceEl = document.getElementById('totalPrice');

        // Add service entry (dropdown + price)
        document.getElementById('addServiceBtn').addEventListener('click', () => {
            const name = serviceSelect.value?.trim();
            const priceStr = servicePrice.value?.trim();
            if (!name || !priceStr) {
                alert("Select a service and enter price");
                return;
            }
            const price = parseFloat(priceStr);
            if (isNaN(price) || price < 0) {
                alert("Enter valid price");
                return;
            }
            const existing = services.find(s => s.name === name);
            if (existing) {
                existing.price += price;
            } else {
                services.push({
                    name,
                    price
                });
            }
            servicePrice.value = '';
            renderServices();
            syncHiddenScopeAndTotal();
        });

        function renderServices() {
            addedList.innerHTML = '';
            services.forEach((s, idx) => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <span class="chip">
                        <strong>${s.name}</strong> — €${s.price.toFixed(2)}
                        <span class="remove text-danger" data-idx="${idx}" title="Remove">&times;</span>
                    </span>`;
                addedList.appendChild(li);
            });
            addedList.querySelectorAll('.remove').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const i = parseInt(e.target.getAttribute('data-idx'));
                    services.splice(i, 1);
                    renderServices();
                    syncHiddenScopeAndTotal();
                });
            });
        }

        function syncHiddenScopeAndTotal() {
            const scopeShape = services.map(s => ({
                name: s.name,
                descriptions: [`Price: €${s.price.toFixed(2)}`]
            }));
            scopeJson.value = JSON.stringify(scopeShape);
            const total = services.reduce((sum, s) => sum + (s.price || 0), 0);
            totalPriceEl.value = total.toFixed(2);
        }

        // ====== Modal: Add New Service (AJAX submit) ======
        $('#addServiceForm').on('submit', function(e) {
            e.preventDefault();

            const input = $('#newServiceName');
            const val = input.val().trim();
            if (!val) return;

            $.ajax({
                url: "{{ route('services.store') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    name: val
                },
                success: function(res) {
                    if (res.ok) {
                        if (!Array.from(serviceSelect.options).some(o => o.value === val)) {
                            const opt = document.createElement('option');
                            opt.value = val;
                            opt.textContent = val;
                            serviceSelect.appendChild(opt);
                        }

                        serviceSelect.value = val;

                        // ✅ Hide modal automatically
                        const modalEl = document.getElementById('addServiceModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap
                            .Modal(modalEl);
                        modalInstance.hide();

                        input.val('');

                        // ✅ Show success alert for 2 seconds
                        const alertBox = $(`
                            <div id="serviceSuccessAlert"
                                style="position: fixed; top: 20px; right: 20px;
                                       background: #28a745; color: white;
                                       padding: 12px 18px; border-radius: 6px;
                                       z-index: 9999; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                                Service added successfully!
                            </div>
                        `);
                        $('body').append(alertBox);
                        setTimeout(() => alertBox.fadeOut(400, () => alertBox.remove()), 2000);
                    }
                },
                error: function(err) {
                    alert('Error saving service: ' + (err.responseJSON?.message || 'Unknown error'));
                }
            });
        });

        // Sync scope before main form submit
        document.getElementById('multiServiceForm').addEventListener('submit', function(e) {
            if (services.length === 0) {
                if (!confirm('No services added. Continue?')) {
                    e.preventDefault();
                    return;
                }
            }
            syncHiddenScopeAndTotal();
        });
    </script>

</body>

</html>
