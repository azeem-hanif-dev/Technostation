<!DOCTYPE html>
<html lang="en">

@include('StaffingCompany.partials._header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('StaffingCompany.partials._navBar')

    @include('StaffingCompany.partials._sideBar')
    <div class="content-wrapper">
        <div style="margin: 10px;">
            @foreach($projects as $project)
                <div>
                    <h5>{{ $project['department']['name'] }} > {{ $project['name'] }} > {{ $project['address'] }}</h5>

                    <div class="form-row card">
                        <table class="table table-bordered sort">
                            <thead>
                            <tr>
                                <th>{{ $translations['staff'] }}</th>
                                <th>{{ $translations['mon'] }}</th>
                                <th>{{ $translations['tue'] }}</th>
                                <th>{{ $translations['wed'] }}</th>
                                <th>{{ $translations['thu'] }}</th>
                                <th>{{ $translations['fri'] }}</th>
                                <th>{{ $translations['sat'] }}</th>
                                <th>{{ $translations['sun'] }}</th>
                                <th>+</th>
                                <th>{{ $translations['customer'] }}</th>
                                <th>{{ $translations['cost'] }}</th>
                                <th style="width: 10px;">{{ $translations['directing'] }}</th>
                                <th>{{ $translations['comments'] }}</th>
                                <th>{{ $translations['action'] }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($project->weekStates()->where('week_no', $week_no)->get() as $index => $week_state)
                                @foreach($week_state->weekCards as $week_card)
                                    <tr>
                                        <td style="width: 200px">
                                            <select class="form-control" name="">
                                                @foreach($personnels as $personnel)
                                                    <option value="{{ $personnel['id'] }}" {{ $week_card->personnel_id == $personnel['id'] ? 'selected' : '' }}>
                                                        {{ $personnel['first_name'] . ' ' . $personnel['last_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input class="form-control txt" type="number" style="padding:2px;" name="" value="{{ $week_card['hours_1'] }}"></td>
                                        <td><input class="form-control txt" type="number" style="padding:2px;" name="" value="{{ $week_card['hours_2'] }}"></td>
                                        <td><input class="form-control txt" type="number" style="padding:2px;" name="" value="{{ $week_card['hours_3'] }}"></td>
                                        <td><input class="form-control txt" type="number" style="padding:2px;" name="" value="{{ $week_card['hours_4'] }}"></td>
                                        <td><input class="form-control txt" type="number" style="padding:2px;" name="" value="{{ $week_card['hours_5'] }}"></td>
                                        <td><input class="form-control txt" type="number" style="padding:2px;" name="" value="{{ $week_card['hours_6'] }}"></td>
                                        <td><input class="form-control txt" type="number" style="padding:2px;" name="" value="{{ $week_card['hours_7'] }}"></td>
                                        <td>
                                            <label>{{ $week_card['total_hours'] }}</label>
                                        </td>
                                        <td><input type="text" id="rate" class="form-control" style="padding:2px;" name="" value="{{ $week_card['rate'] }}"></td>
                                        <td><input type="number" id="rate_cost" class="form-control" style="padding:2px;" name="" value="{{ $week_card['cost'] }}"></td>
                                        <td><input type="checkbox" name="" {{ $week_card['directing'] ? 'checked' : '' }}></td>
                                        <td><input type="text" class="form-control" name="" value="{{ $week_card['wage'] }}"></td>
                                        <td>

                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@include('StaffingCompany.partials._footer')
</body>
</html>
