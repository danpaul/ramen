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
			<li class="has-dropdown">
				<a href="<?php echo $GLOBALS['config']['site_root_url']. '/admin/products'; ?>">Products</a>
				<ul class="dropdown">
					<li>
						<a href="<?php echo $GLOBALS['config']['site_root_url']. '/admin/add-product'; ?>">Add product</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="<?php echo $GLOBALS['config']['site_root_url']. '/admin/taxonomies'; ?>">Taxonomies</a>
			</li>
			<li class="active">
				<a href="<?php echo $GLOBALS['config']['site_root_url']. '/user/logout'; ?>">Logout</a>
			</li>
		</ul>

	</section>

</nav>
