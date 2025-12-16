<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand js-scroll-trigger" href="#page-top">@lang('outsideLogin.acs')</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      Menu
      <i class="fas fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav text-uppercase ml-auto">

        <li class="nav-item">
          <a class="nav-link js-scroll-trigger" href="#" data-toggle="modal" data-target="#myModal" style="color: #fed136">@lang('outsideLogin.Video')</a>
        </li>

        <li class="nav-item">
          <a class="nav-link js-scroll-trigger" href="#services">@lang('outsideLogin.Solutions')</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link js-scroll-trigger" href="#portfolio">@lang('outsideLogin.Portfolio')</a>
        </li> -->
        <!-- <li class="nav-item">
          <a class="nav-link js-scroll-trigger" href="#about">@lang('outsideLogin.About')</a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link js-scroll-trigger" href="#price">@lang('outsideLogin.Price')</a>
        </li>
        <li class="nav-item">
          <a class="nav-link js-scroll-trigger" href="#contact">@lang('outsideLogin.Contact')</a>
        </li>


        @if (Route::has('login'))
                @auth
                  <li class="nav-item">
                      <a class="nav-link" href="{{ url('/home') }}">Home</a>
                  </li>
                @else
                    <li class="nav-item">
                      <a class="nav-link" href="{{ route('login') }}">@lang('outsideLogin.Login')</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{ route('register') }}">@lang('outsideLogin.Register')</a>
                    </li>
                @endauth
                <select class="form-control" name="languageSelected" id="languageSwitcher" onchange="languageChoosed()">
                  <option value="en" {{ (App::getLocale() == "en") ? 'selected' : '' }}>English</option>
                  <option value="nl" {{ (App::getLocale() == "nl") ? 'selected' : '' }} >Dutch</option>
                </select>
                <!-- <select class="form-control" name="languageSelected" id="languageSwitcher" onchange="languageChoosed()">
                  <option value="en" {{ (Session::get('locale') == "en") ? 'selected' : '' }}>English</option>
                  <option value="nl" {{ (Session::get('locale') == "nl") ? 'selected' : '' }} >Dutch</option>
                </select> -->
        @endif
      </ul>
    </div>
  </div>
</nav>
