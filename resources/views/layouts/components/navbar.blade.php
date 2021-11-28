  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
      </ul>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
          <!-- Messages Dropdown Menu -->
          <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                  <i class="fas fa-user"></i>
                  <span>{{ auth()->user()->name }}</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                  <a href="#" class="dropdown-item"><i class="fas fa-user"></i> My Profile</a>

                    <!-- Only For Seller Start -->
                    @if( auth()->user()->role_id == 4 )
                    <a href="{{ route('seller.change.password.page',auth()->user()->id) }}" class="dropdown-item">
                      <i class="fas fa-key"></i>
                      Change Password
                    </a>
                    @endif
                    <!-- Only For Seller End -->

                  <!-- Change Password for Admin Start -->
                  @if(auth('web')->user()->role_id == 9)
                      <a href="{{ route('change_user_password',auth('web')->user()->id) }}" class="dropdown-item">
                          <i class="fas fa-key"></i>
                          Change Password
                      </a>
                  @endif

                  <!-- Change Password for Admin End -->

                  <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
                      <i class="fas fa-sign-out-alt"></i>
                      Logout
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          {{ csrf_field() }}
                      </form>
                  </a>
              </div>
          </li>
      </ul>
  </nav>
  <!-- /.navbar -->
