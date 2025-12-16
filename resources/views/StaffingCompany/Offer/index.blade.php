<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('StaffingCompany.partials._navBar')
        @include('StaffingCompany.partials._sideBar')

        <div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>

        <div class="content-wrapper">
            <div class="container p-4">
                <div style="margin-top: 10px;" class="card-header">
                    <h3>{{ __('Staffing_Company/offers/index.page_title') }}</h3>
                    <a href="{{ route('offers.create') }}">
                        <button class="btn btn-success float-right">
                            {{ __('Staffing_Company/offers/index.page_title') }}
                        </button>
                    </a>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs" id="activityTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="handover-tab" data-toggle="tab" href="#handover" role="tab">
                            {{ __('Staffing_Company/offers/index.tab_handover') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="retail-tab" data-toggle="tab" href="#retail" role="tab">
                            {{ __('Staffing_Company/offers/index.tab_retail') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="site-tab" data-toggle="tab" href="#site" role="tab">
                            {{ __('Staffing_Company/offers/index.tab_keet') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="container-tab" href="{{ route('quotations.index') }}">
                            {{ __('Staffing_Company/offers/index.tab_container') }}
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3">

                    <!-- Handover Offers -->
                    <div class="tab-pane fade show active" id="handover" role="tabpanel">
                        <h2>{{ __('Staffing_Company/offers/index.handover_heading') }}</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Staffing_Company/offers/index.handover_table_id') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.handover_table_title') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.handover_table_subject') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.handover_table_project') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.handover_table_reference') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.handover_table_total_price') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.handover_table_details') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.handover_table_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($offers as $offer)
                                    <tr>
                                        <td>{{ $offer->id }}</td>
                                        <td>{{ $offer->title }}</td>
                                        <td>{{ $offer->subject }}</td>
                                        <td>{{ $offer->project->name ?? '-' }}</td>
                                        <td>{{ $offer->our_reference }}</td>
                                        <td>{{ $offer->total_price }}</td>
                                        <td>
                                            @if (!empty($offer->scope))
                                                @foreach ($offer->scope as $scopeItem)
                                                    <strong>{{ $scopeItem['name'] }}</strong><br>
                                                    @if (!empty($scopeItem['descriptions']))
                                                        <ul>
                                                            @foreach ($scopeItem['descriptions'] as $desc)
                                                                <li>{{ $desc }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                @endforeach
                                            @else
                                                {{ __('Staffing_Company/offers/index.handover_scope_dash') }}
                                            @endif
                                        </td>
                                        <td>
                                            <a title="{{ __('Staffing_Company/offers/index.icon_pdf') }}"
                                                href="{{ route('offers.downloadPDF', $offer->id) }}">
                                                <i style="cursor: pointer;" class="fas fa-file-pdf"></i>
                                            </a>

                                            <form method="POST" action="{{ route('offers.destroy', $offer->id) }}"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    title="{{ __('Staffing_Company/offers/index.icon_delete') }}"
                                                    style="border:none; background:none; cursor:pointer; color:red;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                            <!-- Gmail Send -->
                                            <a title="{{ __('Staffing_Company/offers/index.icon_email') }}"
                                                href="https://mail.google.com/mail/?view=cm&fs=1&to=info@easycleanup.nl&su=Your+Subject&body="
                                                target="_blank">
                                                <i style="color: green; cursor: pointer;" class="fas fa-envelope"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Retail Offers -->
                    <div class="tab-pane fade" id="retail" role="tabpanel">
                        <h2>{{ __('Staffing_Company/offers/index.retail_heading') }}</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Staffing_Company/offers/index.retail_table_id') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.retail_table_service_type') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.retail_table_project') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.retail_table_reference') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.retail_table_construction_price') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.retail_table_traffic_price') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.retail_table_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($retailOffers as $retail)
                                    <tr>
                                        <td>{{ $retail->id }}</td>
                                        <td>{{ $retail->service_type }}</td>
                                        <td>{{ $retail->project->name ?? '-' }}</td>
                                        <td>{{ $retail->our_reference }}</td>
                                        <td>{{ $retail->construction_price }}</td>
                                        <td>{{ $retail->traffic_controllers_price }}</td>
                                        <td>
                                            <a title="{{ __('Staffing_Company/offers/index.icon_pdf') }}"
                                                href="{{ route('retail-offers.downloadPDF', $retail->id) }}">
                                                <i style="cursor: pointer;" class="fas fa-file-pdf"></i>
                                            </a>

                                            <form method="POST"
                                                action="{{ route('retail-offers.destroy', $retail->id) }}"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    title="{{ __('Staffing_Company/offers/index.icon_delete') }}"
                                                    style="border:none; background:none; cursor:pointer; color:red;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                            <!-- Gmail Send -->
                                            <a title="{{ __('Staffing_Company/offers/index.icon_email') }}"
                                                href="https://mail.google.com/mail/?view=cm&fs=1&to=info@easycleanup.nl&su=Your+Subject&body="
                                                target="_blank">
                                                <i style="color: green; cursor: pointer;" class="fas fa-envelope"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Site Maintenance -->
                    <div class="tab-pane fade" id="site" role="tabpanel">
                        <h2>{{ __('Staffing_Company/offers/index.site_heading') }}</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Staffing_Company/offers/index.site_table_id') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.site_table_title') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.site_table_project') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.site_table_reference') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.site_table_price_hour') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.site_table_price_time') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.site_table_details') }}</th>
                                    <th>{{ __('Staffing_Company/offers/index.site_table_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siteMaintances as $site)
                                    <tr>
                                        <td>{{ $site->id }}</td>
                                        <td>{{ $site->title }}</td>
                                        <td>{{ $site->project->name ?? '-' }}</td>
                                        <td>{{ $site->our_reference }}</td>
                                        <td>{{ $site->price_hour }}</td>
                                        <td>{{ $site->price_time }}</td>
                                        <td>
                                            @if (!empty($site->scope))
                                                @foreach ($site->scope as $scopeItem)
                                                    <strong>{{ $scopeItem['name'] }}</strong><br>
                                                    @if (!empty($scopeItem['descriptions']))
                                                        <ul>
                                                            @foreach ($scopeItem['descriptions'] as $desc)
                                                                <li>{{ $desc }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                @endforeach
                                            @else
                                                {{ __('Staffing_Company/offers/index.handover_scope_dash') }}
                                            @endif
                                        </td>
                                        <td>
                                            <a title="{{ __('Staffing_Company/offers/index.icon_pdf') }}"
                                                href="{{ route('site-offers.downloadPDF', $site->id) }}">
                                                <i style="cursor: pointer;" class="fas fa-file-pdf"></i>
                                            </a>

                                            <form method="POST" action="{{ route('site-offers.destroy', $site->id) }}"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    title="{{ __('Staffing_Company/offers/index.icon_delete') }}"
                                                    style="border:none; background:none; cursor:pointer; color:red;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                            <a title="{{ __('Staffing_Company/offers/index.icon_email') }}"
                                                href="https://mail.google.com/mail/?view=cm&fs=1&to=info@easycleanup.nl&su=Your+Subject&body="
                                                target="_blank">
                                                <i style="color: green; cursor: pointer;" class="fas fa-envelope"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- End Tab Content -->
            </div>
        </div>
    </div>

    @include('StaffingCompany.partials._footer')
</body>

</html>
