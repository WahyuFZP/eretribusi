<div class="navbar bg-base-100 shadow-sm border-b border-base-200">
  <!-- Left Section -->
  <div class="navbar-start">
    <!-- Sidebar Toggle Button (Mobile/Tablet) -->
    <label for="sidebar-drawer" class="btn btn-square btn-ghost lg:hidden">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </label>
    
    <!-- Breadcrumb/Page Title -->
    <div class="hidden lg:flex items-center space-x-2 ml-4">
      <h1 class="text-lg font-semibold text-base-content">
        @yield('page-title', __('Dashboard'))
      </h1>
    </div>
  </div>

  <!-- Center Section -->
  <div class="navbar-center lg:hidden">
    <!-- Mobile Logo/Title -->
    <span class="text-lg font-semibold">{{ config('app.name', 'e-Retribusi') }}</span>
  </div>

  <!-- Right Section -->
  <div class="navbar-end">
    <!-- Search Bar -->
    <div class="hidden md:flex mr-4">
      <div class="form-control">
        <input type="text" placeholder="{{ __('Search...') }}" class="input input-bordered input-sm w-64" />
      </div>
    </div>

    <!-- Notifications -->
    <div class="dropdown dropdown-end mr-2">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
        <div class="indicator">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 -5V9a5.002 5.002 0 0 0-10 0v3l-5 5h5m0 0v1a3 3 0 0 0 6 0v-1m-6 0h6"></path>
          </svg>
          <span class="badge badge-xs badge-primary indicator-item"></span>
        </div>
      </div>
      <div tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-80">
        <div class="menu-title">
          <span>{{ __('Notifications') }}</span>
        </div>
        <li><a>{{ __('No new notifications') }}</a></li>
      </div>
    </div>

    <!-- User Profile Dropdown -->
    <div class="dropdown dropdown-end">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
        <div class="w-10 rounded-full">
          @if(Auth::user()->avatar ?? false)
            <img alt="{{ Auth::user()->name }}" src="{{ Auth::user()->avatar }}" />
          @else
            <!-- Default avatar jika tidak ada foto -->
            <div class="bg-primary text-primary-content rounded-full w-10 h-10 flex items-center justify-center">
              <span class="text-sm font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
          @endif
        </div>
      </div>
      <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
        <!-- User Info -->
        <li class="menu-title">
          <span>{{ Auth::user()->name }}</span>
        </li>
        <li class="text-xs text-base-content/60 px-4 pb-2">{{ Auth::user()->email }}</li>
        
        <!-- Quick Actions -->
        <li>
          <a href="{{ route('profile.edit') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            {{ __('Edit Profile') }}
          </a>
        </li>
        <li>
          <a href="#">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            {{ __('Account Settings') }}
          </a>
        </li>
        
        <div class="divider my-1"></div>
        
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left flex items-center text-error">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              {{ __('Log Out') }}
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>
