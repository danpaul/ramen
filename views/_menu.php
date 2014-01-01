<nav class="top-bar" data-topbar>

	<ul class="title-area">
		<li class="name">
			<h1><a href="<?php echo $GLOBALS['config']['site_root_url']; ?>"><?php echo $GLOBALS['config']['settings']['site_name']; ?></a></h1> 
		</li>
		<!-- <li class="toggle-topbar menu-icon">
			<a href="#"><span>Menu</span></a>
		</li> -->
	</ul>

	<section class="top-bar-section"> <!-- Right Nav Section -->
		<!-- <ul class="left">
			<li class="has-dropdown"><a href="#">Connect</a>
				<ul class="dropdown">
					<li>blog</li>
				</ul>
		</ul> -->

		<ul class="right">
			<li class="active"><a href="<?php echo $GLOBALS['config']['site_root_url']. '/user/login-register'; ?>">Login/register</a></li>
			<li class="has-dropdown"> <a href="#">Right Button with Dropdown</a>
				<ul class="dropdown">
					<li><a href="#">First link in dropdown</a></li>
				</ul>
			</li>
		</ul>

	</section>

</nav>
