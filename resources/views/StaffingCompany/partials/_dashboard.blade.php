@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

<div class="wrapper">

    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    {{-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
                        </ol>
                    </div><!-- /.col --> --}}
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $data['assignedPersonnel'] }}</h3>
                                <div style="display: flex;justify-content: space-between;">
                                    <p>Personnels</p>
                                    <span style="position:absolute;top:30%;left:60%;">Online :
                                        <b>{{ $data['loggedInCount'] }}</b></span>
                                    <span style="position:absolute;top:45%;left:60%;">Offline :
                                        <b>{{ $data['notLoggedInCount'] }}</b></span>
                                </div>
                            </div>
                            <div class="icon">
                                <i class="ion person"></i>
                            </div>
                            <a href="{{ route('personnels.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $data['activeProjects'] }}</h3>
                                <p>Week {{ $data['week'] }}

                                    Projects({{ $data['projects'] }})</p>

                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('staffing_projects.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $data['customers'] }}</h3>
                                <p>Customers</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('customers.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $total }}</h3>
                                <p style="margin: 0; line-height: 1.27;">
                                    <small>Time Approved: {{ $approved }}</small>
                                    <small>Time Unapproved: {{ $notApproved }}</small>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('week-state.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    {{-- <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $data['agencies'] }}</h3>

                                <p>Agencies</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('employment-agencies.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div> --}}
                    <!-- ./col -->
                </div>
                <h3>Container</h3>
                <!-- container module  -->
                @php
                    $status = 'Staffing_Company/Order_Container/status';
                    $totalCount = array_sum([
                        @$statusCount['open'] ?? 0,
                        @$statusCount['in progress'] ?? 0,
                        @$statusCount['pick up'] ?? 0,
                        @$statusCount['picked'] ?? 0,
                        @$statusCount['canceled'] ?? 0,
                        @$statusCount['close'] ?? 0,
                        @$statusCount['send to supplier'] ?? 0,
                    ]);
                @endphp

                <div class="row justify-content-start">
                    <div class="col-lg-6 col-md-8 col-12">
                        <div class="small-box bg-secondary text-white">
                            <div class="inner">
                                <h3>{{ $data['totalCount'] }}</h3>
                                <div class="row">
                                    <!-- Left side (first 4 links) -->
                                    <div class="col-6 d-flex flex-column" style="gap: 6px; font-size: 14px;">
                                        <a href="{{ route('order_containers.index', ['status' => 'open']) }}"
                                            class="text-white">
                                            🟦 Open ({{ $data['statusCount']['open'] ?? 0 }})
                                        </a>
                                        <a href="{{ route('order_containers.index', ['status' => 'in progress']) }}"
                                            class="text-white">
                                            🟨 In Progress ({{ $data['statusCount']['in progress'] ?? 0 }})
                                        </a>
                                        <a href="{{ route('order_containers.index', ['status' => 'pick up']) }}"
                                            class="text-white">
                                            🟦 Pick Up ({{ $data['statusCount']['pick up'] ?? 0 }})
                                        </a>
                                        <a href="{{ route('order_containers.index', ['status' => 'picked']) }}"
                                            class="text-white">
                                            🟩 Picked ({{ $data['statusCount']['picked'] ?? 0 }})
                                        </a>
                                    </div>

                                    <!-- Right side (remaining 3 links) -->
                                    <div class="col-6 d-flex flex-column" style="gap: 6px; font-size: 14px;">
                                        <a href="{{ route('order_containers.index', ['status' => 'canceled']) }}"
                                            class="text-white">
                                            🟥 Canceled ({{ $data['statusCount']['canceled'] ?? 0 }})
                                        </a>
                                        <a href="{{ route('order_containers.index', ['status' => 'close']) }}"
                                            class="text-white">
                                            ⬛ Closed ({{ $data['statusCount']['close'] ?? 0 }})
                                        </a>
                                        <a href="{{ route('order_containers.index', ['status' => 'send to supplier']) }}"
                                            class="text-white">
                                            🟧 Sent to Supplier ({{ $data['statusCount']['send to supplier'] ?? 0 }})
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="icon">
                                <i class="fas fa-truck"></i>
                            </div>

                            <a href="{{ route('order_containers.index') }}" class="small-box-footer text-white">
                                View All Orders <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>





                <!-- container module -->
                <!-- /.row -->
                <!-- Main row -->
                <div class="row justify-content-between">
                    <!-- Left col -->
                    <style>
                        .scrollable-tbody {
                            display: block;
                            max-height: 352px;
                            overflow-y: auto;
                        }

                        .scrollable-tbody tr {
                            display: table;
                            width: 100%;
                            table-layout: fixed;
                        }

                        .scrollable-thead,
                        .scrollable-tbody {
                            width: 100%;
                        }

                        .scrollable-thead {
                            display: table;
                            table-layout: fixed;
                        }
                    </style>
                    @if ($openOrders && count($openOrders) > 0)

                        <section class="col-lg-7 connectedSortable">

                            <h2>Tickets </h2>
                            <div style="height: 400px; overflow-y: auto;">

                                @foreach ($openOrders as $orderContainer)
                                    <div class="card text-white"
                                        style="background: linear-gradient(135deg, #084c61, #177e89); border: none; border-radius: 12px; margin-bottom: 20px;">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-1">
                                                    {{ $orderContainer->project->name ?? 'Project Name' }}
                                                    - {{ $orderContainer->project->project_number ?? 'Project Code' }}
                                                    <span
                                                        class="badge
                                                                     @if ($orderContainer->status == 'open') bg-primary
                                                                     @elseif($orderContainer->status == 'close') bg-secondary
                                                                     @elseif($orderContainer->status == 'send to supplier') bg-warning text-dark
                                                                     @elseif($orderContainer->status == 'pick up') bg-info
                                                                     @elseif($orderContainer->status == 'picked') bg-success
                                                                     @elseif($orderContainer->status == 'canceled') bg-danger
                                                                     @elseif($orderContainer->status == 'in progress') bg-warning text-dark
                                                                     @else bg-dark @endif
                            ">
                                                        {{ ucfirst($orderContainer->status ?? 'Open') }}

                                                    </span>
                                                </h5>

                                                <p class="card-text mb-0" style="font-size: 14px;">
                                                    Order No. #: BN-{{ $orderContainer->id ?? '' }}
                                                </p>

                                                <p class="card-text mb-0" style="font-size: 14px;">
                                                    Execution Date: {{ $orderContainer->execution_date ?? 'N/A' }}
                                                </p>

                                                <p class="card-text mb-0" style="font-size: 14px;">
                                                    Order Date: {{ $orderContainer->order_date_time ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('order-container.view', $orderContainer->id) }}"
                                                    class="btn btn-success mr-2"
                                                    style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('order-waste-container.edit', $orderContainer->id) }}"
                                                    class="btn btn-light"
                                                    style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </section>
                        <!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->

                        <section class="col-lg-5 connectedSortable">
                            <canvas id="myChart" width="400" height="400"></canvas>

                        </section>
                    @endif
                    <!-- right col -->
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- /.control-sidebar -->
</div>

@push('script')
    <script>
        const labels = @json($labels);
        const values = @json($values);
        const ctx = document.getElementById('myChart').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Status Distribution',
                    data: values,
                    backgroundColor: [
                        '#38c172', '#ffed4a', '#3490dc', '#e3342f', '#6c5ce7', '#fd79a8', '#00cec9'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });


        @if (session('sweet_error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('sweet_error') }}'
            });
        @endif
    </script>
@endpush
