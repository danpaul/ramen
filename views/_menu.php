<nav class="top-bar" data-topbar>

	<ul class="title-area">
		<li class="name">
			<h1>
				<a href="<?php echo $GLOBALS['config']['site_root_url']; ?>"><?php echo $GLOBALS['config']['settings']['site_name']; ?></a>
			</h1> 
		</li>

	</ul>

	<section class="top-bar-section">

		<ul class="right">
			<li class="active">
				<?php if( isset($_SESSION['user']['logged_in']) && $_SESSION['user']['logged_in'] === TRUE ) { ?>
					<a href="<?php echo $GLOBALS['config']['site_root_url']. '/user/logout'; ?>">Logout</a>
				<?php } else { ?>
					<a href="<?php echo $GLOBALS['config']['site_root_url']. '/user/login-register'; ?>">Login/register</a>
				<?php } ?>
			</li>
			<li class="has-dropdown"> <a href="#">Right Button with Dropdown</a>
				<ul class="dropdown">
					<li><a href="#">First link in dropdown</a></li>
				</ul>
			</li>
		</ul>

	</section>

</nav>
