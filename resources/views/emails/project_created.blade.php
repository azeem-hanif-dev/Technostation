<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Project Created</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { max-width: 200px; }
        .links { margin-bottom: 20px; text-align: center; }
        .links a { color: #007bff; text-decoration: none; margin: 0 10px; }
        .content { background-color: #f8f9fa; padding: 20px; border-radius: 5px; }
        h1 { color: #28a745; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://www.digitalstationapp.com/public/images/new-logo.jpeg" alt="Company Logo" class="logo">
        </div>

        {{-- <div class="links">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/projects') }}">Projects</a>
            <a href="{{ url('/contact') }}">Contact Us</a>
        </div> --}}

        <div class="content">
            <h1>New Project Created</h1>
            <p>A new project has been successfully created with the following details:</p>

            <table>
                <tr><th>Field</th><th>Details</th></tr>
                    <tr><td>Project Name</td><td>{{ $project->name }}</td></tr>
                    <tr><td>Customer</td><td>{{ $project->customer->name ?? 'Not specified' }}</td></tr>
                    <tr><td>Department</td><td>{{ $project->department->name ?? 'Not specified' }}</td></tr>
                    <tr><td>Performer</td><td>{{ $project->projectPerformer->first_name ?? '' }} {{ $project->projectPerformer->last_name ?? '' }}</td></tr>
                    <tr><td>Start Date</td><td>{{ $project->start_date ?? 'Not set' }}</td></tr>
                    <tr><td>End Date</td><td>{{ $project->end_date ?? 'Not set' }}</td></tr>
                    <tr><td>Project Manager</td><td>
                        @if($project->project_manager == 1)
                            {{ 'Jacqueline' }}
                        @elseif($project->project_manager == 2)
                            {{ 'Shakeel' }}
                        @endif
                    </td></tr>
                    <tr><td>Description</td><td>{{ $project->description ?: 'No description provided' }}</td></tr>
                    <tr><td>Fixed Price</td><td>{{ $project->fixed_price ?? 'Not specified' }}</td></tr>
                    <tr><td>ECU Project No</td><td>{{ $project->edu_project_no ?: 'Not provided' }}</td></tr>
                    <tr><td>Client Project No</td><td>{{ $project->client_project_no ?: 'Not provided' }}</td></tr>
                    <tr><td>Address</td><td>{{ $project->address ?: 'Not provided' }}</td></tr>
                    <tr><td>Post Code</td><td>{{ $project->post_code ?: 'Not provided' }}</td></tr>
                    {{-- <tr><td>City</td><td>{{ $project->city ?: 'Not specified' }}</td></tr> --}}
                    <tr><td>Location</td><td>
                        @if($project->long && $project->lat)
                            <a href="https://maps.google.com/?q={{ $project->lat }},{{ $project->long }}">Google Map Link</a>
                        @else
                            Not specified
                        @endif
                    </td></tr>
                    <tr><td>Weekly Statement</td><td>{{ $project->weekly_statement ? 'Yes' : 'No' }}</td></tr>
                    <tr><td>Price Agreement</td><td>{{ $project->price_agreement ?: 'Not specified' }}</td></tr>
                    <tr><td>No. of Times Per Week</td><td>{{ $project->no_of_times_per_week ?? 'Not specified' }}</td></tr>
                    <tr><td>Unit</td><td>{{ $project->unit ?: 'Not specified' }}</td></tr>
                    <tr><td>No. of Chain</td><td>{{ $project->no_of_chain ?? 'Not specified' }}</td></tr>
                    <tr><td>Price</td><td>{{ $project->price ?? 'Not specified' }}</td></tr>
                    <tr><td>Purchase Price</td><td>{{ $project->purchase_price ?? 'Not specified' }}</td></tr>
                    <tr><td>Approval</td><td>{{ $project->approval ?? 'Not specified' }}</td></tr>
                    <tr><td>Notes</td><td>{{ $project->notes ?: 'No notes' }}</td></tr>
                    <tr><td>More Notes</td><td>{{ $project->more_notes ?: 'No additional notes' }}</td></tr>
                    <tr><td>Active</td><td>{{ $project->active ? 'Yes' : 'No' }}</td></tr>
            </table>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }}<a href="https://www.technostation.org">Digital Clean Solution</a>. All rights reserved.
{{--            &copy; {{ date('Y') }}<a href="https://www.digitalstationapp.com">Digital Clean Solution</a>. All rights reserved.--}}

        </div>
    </div>
</body>
</html>
