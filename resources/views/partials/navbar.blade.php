<!-- $user = auth()->user();
$userBalance = $user->balance();
$orders = $user->serviceOrders;  -->



      <nav id="sidebar">
				<div class="custom-menu">
					<button type="button" id="sidebarCollapse" class="btn btn-primary">
	        </button>
        </div>
        @auth
	  		<div class="img bg-wrap text-center py-4" style="background-image: url({{ asset('img/bg_1.jpg')}});">
	  			<div class="user-logo">
	  				<div class="img" style="background-image: url('{{ asset('img/user/' . rand(0,102) . '.png') }}');"></div>
	  				<h3>{{ auth()->user()->name }}</h3>
	  			</div>
	  		</div>
        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="{{ route('user.dashboard') }}"><span class="fa fa-home mr-3"></span> Home</a>
          </li>
          <li>
              <a href="{{ route('user.services') }}"><i class="fa-brands fa-servicestack mr-3 notif"></i> Services</a>
          </li>
          <li>
              <a href="{{ route('user.downloads') }}"><i class="fa fa-download mr-3 notif"></i> Download </a>
          </li>
          <li>
              <a href="{{ route('user.deposite') }}"><i class="fa fa-dollar mr-3 notif"></i> Payment </a>
          </li>
          <li>
              <a href="{{ route('user.profile') }}"><i class="fa fa-user mr-3 notif"></i> Profile </a>
          </li>
          <li>
            <a href="{{ route('logout') }}"><span class="fa fa-sign-out mr-3"></span> LogOut</a>
          </li>
        </ul>
        @endauth

    	</nav>
