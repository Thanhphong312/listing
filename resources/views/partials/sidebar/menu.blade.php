<div
  class="menu-w color-scheme-light textModeToggle color-style-transparent menu-position-side menu-side-left menu-layout-compact sub-menu-style-over sub-menu-color-bright selected-menu-color-light menu-activated-on-hover menu-has-selected-link">
  <div class="logo-w" style="justify-content: center;">
    <a class="logo" href="/"><img src="{{ url('assets/img/logo.jpg') }}" style="
    width: 100;
    ">
    </a>
  </div>
  <div class="logged-user-w avatar-inline">
    <div class="logged-user-i">
      <div class="avatar-w">
        <img src="{{ auth()->user()->present()->avatar }}">
      </div>
      <div class="logged-user-info-w">
        <div class="logged-user-name">
          <a href="{{ route('profile') }}">{{ auth()->user()->present()->nameOrEmail }}</a>
        </div>
        <div class="logged-user-role">
          {{ auth()->user()->role->name}}
        </div>

      </div>
      <div class="logged-user-toggler-arrow">
        <div class="os-icon os-icon-chevron-down"></div>
      </div>
      <div class="logged-user-menu color-style-bright">
        <div class="logged-user-avatar-info">
          <div class="avatar-w">
            <img src="{{ auth()->user()->present()->avatar }}">
          </div>
          <div class="logged-user-info-w">
            <div class="logged-user-name">
              <a href="{{ route('profile') }}">{{ auth()->user()->present()->nameOrEmail }}</a>
            </div>
            <div class="logged-user-role">
              {{ auth()->user()->present()->username }}
            </div>
          </div>
        </div>
        <div class="bg-icon">
          <i class="os-icon os-icon-wallet-loaded"></i>
        </div>
        <ul>
          <li>
            <a href="/profile"><i class="os-icon os-icon-user-male-circle2"></i><span>Profile Details</span></a>
          </li>
          <!-- <li>
            <a href="/wallet"><i class="os-icon os-icon-wallet-loaded"></i><span>@money(auth()->user()->wallet_balance)</span></a>
          </li> -->
          <!-- <li>
            <a href="#"><i class="os-icon os-icon-others-43"></i><span>Notifications</span></a>
          </li> -->
          <li>
            <a href="/logout"><i class="os-icon os-icon-signs-11"></i><span>Logout</span></a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="menu-actions">
    <!--------------------
      START - Messages Link in secondary top menu
      -------------------->

    <!--------------------
      END - Messages Link in secondary top menu
      --------------------><!--------------------
      START - Settings Link in secondary top menu
      -------------------->
    <!-- <div class="top-icon top-settings os-dropdown-trigger os-dropdown-position-right"> -->
    <!-- <i class="os-icon os-icon-ui-46"></i>
      <div class="os-dropdown">
        <div class="icon-w">
          <i class="os-icon os-icon-ui-46"></i>
        </div>
        <ul>
          <li>
            <a href="/profile"><i class="os-icon os-icon-ui-49"></i><span>Profile Settings</span></a>
          </li>
          <li>
            <a href=""><i class="os-icon os-icon-ui-15"></i><span>Cancel Account</span></a>
          </li>
        </ul>
      </div> -->
    <!-- </div> -->
    <!--------------------
      END - Settings Link in secondary top menu
      --------------------><!--------------------
      START - Messages Link in secondary top menu
      -------------------->
    <!-- <div class="messages-notifications os-dropdown-trigger os-dropdown-position-right">
      <i class="os-icon os-icon-zap"></i>
      <div class="new-messages-count">
        {{-- 4 --}}
      </div>
    </div> -->

  </div>
  <h1 class="menu-page-header">
    Page Header
  </h1>


  <ul class="main-menu">
    @if (app('impersonate')->isImpersonating())
    <li class="nav-item d-flex align-items-center visible-lg">
      <a href="{{ route('impersonate.leave') }}" class="btn text-danger">
      <i class="fas fa-user-secret mr-2"></i>
      @lang('Stop Impersonating')
      </a>
    </li>
  @endif
    @foreach (\Vanguard\Plugins\Vanguard::availablePlugins() as $plugin)
    @include('partials.sidebar.items', ['item' => $plugin->sidebar()])
  @endforeach
    <li class="selected d-sm-none d-md-block" style="cursor: pointer">
      <a class="arrow" onclick="onCollapase()">
        <div class="icon-w" id="btn-Collapase">
          <i class="os-icon os-icon-arrow-left4"></i>
        </div>
        <span>Collapse</span>
      </a>
    </li>
    <!-- <li>
      <a>
        <div class=" sub-menu floated-colors-btn">
          <div class="os-toggler-w" style="display: flex;">
            <div class="os-toggler-i">
              <i class="os-toggler-pill"></i>
            </div>
          </div>
          <span>Dark Color</span>
        </div>
      </a>
    </li> -->
  </ul>
  </nav>
</div>
