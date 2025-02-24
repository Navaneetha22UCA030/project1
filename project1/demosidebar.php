<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
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

        <!-- Updated Dropdown for Order Management -->
        <div class="nav-item">
            <a class="nav-link text-white collapsed" href="#orderManagement" data-bs-toggle="collapse" aria-expanded="false" aria-controls="orderManagement">
                <i class="bi bi-shop text-secondary"></i> &nbsp; Order Management &nbsp; <i class="bi bi-arrow-down-circle"></i></a>
            <div class="collapse" id="orderManagement" data-bs-parent="#sidenavAccordion">
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