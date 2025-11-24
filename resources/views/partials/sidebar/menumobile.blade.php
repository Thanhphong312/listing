
<div class="menu-mobile menu-activated-on-click color-scheme-dark">
    <div class="mm-logo-buttons-w">
      <a class="mm-logo" href="/"><img src="{{ url('assets/img/logo.jpg') }}" style="width: 90px;
    display: inline-block;">
      </a>
      <div class="mm-buttons">
        <div class="mobile-menu-trigger">
          <div class="os-icon os-icon-hamburger-menu-1"></div>
        </div>
      </div>
    </div>
    <div class="menu-and-user">
      <div class="logged-user-w">
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
      <!--------------------
      START - Mobile Menu List
      -------------------->
      <ul class="main-menu">
        <li class="sub-menu">
          <a href="/">
            <div class="icon-w">
              <div class="os-icon os-icon-layout"></div>
            </div>
            <span>Dashboard</span></a>
          <div class="sub-menu-w">

            <div class="sub-menu-i">
            </div>
          </div>
        </li>
        <li class="sub-menu">
          <a href="/orders">
            <div class="icon-w">
              <div class="fas fa-list"></div>
            </div>
            <span>Orders</span></a>
        </li>
        <li class=" sub-menu">
          <a href="/products">
            <div class="icon-w">
              <div class="fas fa-tshirt"></div>
            </div>
            <span>Products</span></a>
        </li>
        <li class="sub-menu">
          <a href="/stores">
            <div class="icon-w">
              <div class="fas fa-warehouse"></div>
            </div>
            <span>Stores</span></a>
        </li>

        <li class=" sub-menu">
          <a href="/reports">
            <div class="icon-w">
              <div class="fas fa-calculator"></div>
            </div>
            <span>Reports</span></a>
        </li>
        <!-- <li class=" sub-menu">
          <a href="/trackings">
            <div class="icon-w">
              <div class="fas fa-map"></div>
            </div>
            <span>Trackings</span></a>
        </li> -->
        <li class=" sub-menu">
          <a href="/tickets">
            <div class="icon-w">
              <div class="fas fa-wrench"></div>
            </div>
            <span>Tickets</span></a>
        </li>
        <li class=" has-sub-menu">
          <a href="#">
            <div class="icon-w">
              <div class="fas fa-dollar-sign"></div>
            </div>
            <span>Wallets</span></a>
              <ul class="sub-menu">
                <li>
                  <a href="/wallet/pending">Pedding funds</a>
                </li>
                <li>
                  <a href="/wallet/refund">Refunds</a>
                </li>
              </ul>
        </li>
        <li class=" sub-menu">
          <a href="/timeline">
            <div class="icon-w">
              <div class="fas fa-history"></div>
            </div>
            <span>Historys</span></a>
        </li>
        <li class=" has-sub-menu">
          <a href="#">
            <div class="icon-w">
              <div class="os-icon os-icon-life-buoy"></div>
            </div>
            <span>Setting</span></a>
              <ul class="sub-menu">
                <li>
                  <a href="/roles">Roles</a>
                </li>
                <li>
                  <a href="/permissions">Permissions</a>
                </li>
                <li>
                  <a href="/users">Users <strong class="badge badge-danger"></strong></a>
                </li>
                <li>
                  <a href="/settings">General <strong class="badge badge-danger"></strong></a>
                </li>
                <li>
                  <a href="/settings/auth">Auth & Registration</a>
                </li>
                <!-- <li>
                  <a href="/settings/notifications">Notifications</a>
                </li> -->
                <li>
                  <a href="/announcements">Aunnouncements</a>
                </li>
                <li>
                  <a href="/activity">Activity logs</a>
                </li>
              </ul>
        </li>
        <li class="sub-menu">
          <a href="/profile">
            <div class="icon-w">
              <div class="os-icon os-icon-user-male-circle2"></div>
            </div>
            <span>Profile Details</span></a>
        </li>
        <li class=" sub-menu">
          <a href="/logout">
            <div class="icon-w">
              <div class="os-icon os-icon-signs-11"></div>
            </div>
            <span>Logout</span></a>
        </li>
    </div>
  </div>
