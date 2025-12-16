<style>
    /* Fixed sidenav, full height */
    /*.sidenav {*/
    /*    height: 100%;*/
    /*    width: 200px;*/
    /*    position: fixed;*/
    /*    z-index: 1;*/
    /*    top: 0;*/
    /*    left: 0;*/
    /*    background-color: #111;*/
    /*    overflow-x: hidden;*/
    /*    padding-top: 20px;*/
    /*}*/

    /* Style the sidenav links and the dropdown button */
    .dropdown-btn {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        /*font-size: 20px;*/
        color: #337AB7;
        display: block;
        border: none;
        background: none;
        width:100%;
        text-align: left;
        cursor: pointer;
        outline: none;
    }
    .dropdown-container > ul > li > a{
        text-decoration: none;
    }

    /* On mouse-over */
    /*.sidenav a:hover, .dropdown-btn:hover {*/
    /*    color: #f1f1f1;*/
    /*}*/

    /* Main content */
    .main {
        margin-left: 200px; /* Same as the width of the sidenav */
        font-size: 20px; /* Increased text to enable scrolling */
        padding: 0px 10px;
    }

    /* Add an active class to the active dropdown button */
    /*.active {*/
    /*    background-color: green;*/
    /*    color: white;*/
    /*}*/

    /* Dropdown container (hidden by default). Optional: add a lighter background color and some left padding to change the design of the dropdown content */
    .dropdown-container {
        display: none;
        background-color: ;
        padding-left: 8px;
    }

    /* Optional: Style the caret down icon */
    .fa-caret-down {
        float: right;
        padding-right: 8px;
    }
</style>
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            {{-- <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="@lang('Company_Admin/dashboard.Search')...">
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
                </div>
                <!-- /input-group -->
            </li> --}}
            <li>
                <a href="{{route('home')}}"><i class="fa fa-dashboard fa-fw"></i> @lang('Company_Admin/dashboard.Dashboard')</a>
            </li>

            <li>
                <a href="{{route('com_admin.profile')}}"><i class="fa fa-user"></i> @lang('Company_Admin/dashboard.Profile')</a>
            </li>

            <li>
                <a href="{{route('floor.index')}}"><i class="fa fa-building"></i> @lang('common.Floors Management')</a>
            </li>

            <li>
                <a href="{{route('area.index')}}"><i class="glyphicon glyphicon-globe"></i>   @lang('common.Areas Management')</a>
            </li>

            <li>
                <a href="{{route('customer.index')}}"><i class="fa fa-users"></i> @lang('common.Customers Management')  </a>
            </li>

            <li>
                <a href="{{route('employ_agency.index')}}"><i class="glyphicon glyphicon-briefcase"></i> @lang('common.Employment Agencies Management')</a>
            </li>

            <li>
                <a href="{{route('project.index')}}"><i class="fa fa-industry"></i> @lang('common.Projects Management')</a>
            </li>


            <li>
                <a href="{{route('projectcostestimate.index')}}"><i class="fa fa-cc" aria-hidden="true"></i> @lang('common.Projects Cost Estimate')</a>
            </li>

        <!-- <li>
              <a href="{{route('quotesIndex')}}"><i class="fa fa-shopping-cart"></i> @lang('common.Project') @lang('common.Material') @lang('common.Quotes')</a>
            </li> -->

        <!-- <li>
                <a href="{{route('area.index')}}"><i class="fa fa-university"></i> @lang('common.Area')</a>
            </li> -->

            <li>
                <a href="{{route('staff.index')}}"><i class="fa fa-user"></i> @lang('common.Staff Management')</a>
            </li>

            <li>

                <div class="dropdown-btn">   <i class="fa fa-file"></i>   @lang('common.Reports Management')
                    <i class="fa fa-caret-down"></i>
                </div>
                <div class="dropdown-container">
                    <ul>
                        <li> <a href="{{ route('inspection.index') }}">@lang('common.Antal inspec')</a></li>
                        <li> <a href="{{route('workerReport.index')}}">@lang('common.Worker report') </a></li>

                    </ul>


                </div>

            </li>

{{--            <li>--}}
{{--                <a href="{{route('workerReport.index')}}"><i class="fa fa-user"></i> Worker Reports</a>--}}
{{--            </li>--}}

        <!-- <li>
                <a href="{{route('material.index')}}"><i class="fa fa-shopping-basket"></i> @lang('common.Materials')</a>
            </li> -->

        <!-- <li>
                <a href="{{route('supplier.index')}}"><i class="fa fa-truck"></i> @lang('common.Suppliers')</a>
            </li> -->

        <!-- <li>
                <a href="#"><i class="fa fa-graduation-cap"></i> @lang('common.Accounts')</a>
            </li> -->

        <!-- <li>
                <a href="#"><i class="fa fa-credit-card"></i> @lang('Company_Admin/dashboard.Salaries') @lang('Company_Admin/dashboard.Management')</a>
            </li> -->

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
<script>
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>
