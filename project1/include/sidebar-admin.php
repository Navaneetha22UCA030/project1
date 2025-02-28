<!-- <div id="layoutSidenav_nav" class="position-fixed start-0 top-0 vh-100 bg-dark text-white d-none d-md-block">
	<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
		<div class="sb-sidenav-menu">
			<div class="nav">
				<a class="nav-link text-white" href="dashboard-admin.php">
					<div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
					Dashboard
				</a>
				<a class="nav-link text-white" href="tools-manage-admin.php">
					<div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
					Manage Power Tools
				</a>
				<a class="nav-link text-white" href="tools-rental-admin.php">
					<div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
					Tools Rental in shop Admin
				</a>
				<a class="nav-link text-white" href="tool_rental_list_admin.php">
					<div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
					 Tools Rental For Online 
				</a>

				<!-- Updated Dropdown for Order Management 
				<div class="nav-item">
					<a class="nav-link text-white collapsed" href="orderManagement" data-bs-toggle="collapse" aria-expanded="false" aria-controls="orderManagement">
					<i class="bi bi-shop text-secondary"></i> &nbsp; Order Management  &nbsp; <i class="bi bi-arrow-down-circle"></i></a>
					<div class="collapse" id="orderManagement" data-bs-parent="sidenavAccordion">
						<div class="bg-dark py-2">
							<a class="nav-link text-white ps-4" href="order_details_admin.php">
								<i class="fas fa-calendar-alt me-2"></i>
							Order Detail's
							</a>
							<a class="nav-link text-white ps-4" href="pending_orders_admin.php">
								<i class="fas fa-clock me-2"></i>
								Pending Orders
							</a>
							<a class="nav-link text-white" href="delivered_orders_admin.php">&nbsp;&nbsp;
								<i class="fas fa-check-circle"></i>
								&nbsp;Delivered Orders
							</a>
						</div>
					</div>
				</div>

				<a class="nav-link text-white" href="category_admin.php">
					<div class="sb-nav-link-icon"><i class="bi bi-bag-fill"></i>
					</div>
					Create Category
				</a>

				<a class="nav-link text-white" href="insert_poroduct_admin.php">
					<div class="sb-nav-link-icon"><i class="bi bi-building-down"></i></div>
					Insert Product
				</a>

			</div>
		</div>
	</nav>
</div> -->



<div id="sidebar" class="pt-3">
	<div class="nav flex-column">
		<div class="h3 text-primary">
			<a class="nav-link active" href="dashboard-admin.php">
				Admin Panal
			</a>
		</div>
		<a class="nav-link active" href="dashboard-admin.php">
			<i class="fas fa-tachometer-alt me-2"></i>Dashboard
		</a>
		<a class="nav-link" href="tools-manage-admin.php">
			<i class="fas fa-tools me-2"></i>Manage Tools
		</a>
		<a class="nav-link" href="tools-rental-admin.php">
			<i class="fas fa-calendar-alt me-2"></i>Power Tool Rental Shop
		</a>

		<!-- Collapsible Order Management Section -->
		<div class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#orderManagement" role="button">
				<i class="bi bi-shop me-2"></i>Order Management
				<i class="bi bi-chevron-down float-end"></i>
			</a>
			<div class="collapse" id="orderManagement">
				<div class="nav flex-column ms-3">
					<a class="nav-link" href="order_details_admin.php"><i class="fas fa-list-alt me-2"></i>All Orders</a>
					<a class="nav-link" href="pending_orders_admin.php"><i class="fas fa-clock me-2"></i>Pending</a>
					<a class="nav-link" href="delivered_orders_admin.php"><i class="fas fa-check-circle me-2"></i>Delivered</a>
				</div>
			</div>
		</div>

		<a class="nav-link" href="category_admin.php">
			<i class="bi bi-bag-fill me-2"></i>Categories
		</a>
		<a class="nav-link" href="insert_poroduct_admin.php">
			<i class="bi bi-plus-circle me-2"></i>Add Product
		</a>

		<a class="nav-link" href="tool_rental_list_admin.php">
			<i class="fas fa-tools me-2"></i>Rentals List Online
		</a>
	</div>
</div>