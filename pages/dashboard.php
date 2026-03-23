<?php
$user = LoggedInUser();
$is_admin = function_exists('isAdmin') ? (bool)isAdmin() : false;
$is_user = function_exists('isUser') ? (bool)isUser() : false;

$kpi = [
    'users' => $is_admin && function_exists('countUsers') ? countUsers('User') : null,
    'products' => function_exists('countProducts') ? countProducts() : 0,
    'categories' => $is_admin && function_exists('countCategories') ? countCategories() : null,
    'stock_qty' => $is_admin && function_exists('getTotalStockQty') ? getTotalStockQty() : null,
    'my_cart' => ($is_user && $user && function_exists('getPendingCartProductCountForUser')) ? getPendingCartProductCountForUser((int)$user->id_user) : null,
];

$recent_products = function_exists('getRecentProducts') ? getRecentProducts($is_admin ? 8 : 6) : null;
$low_stock = ($is_admin && function_exists('getProductStockSummary')) ? getProductStockSummary(10) : null;

$chart = [
    'overview' => null,
    'lowStock' => null,
];

if ($is_admin) {
    $chart['overview'] = [
        'labels' => ['Users', 'Products', 'Categories'],
        'values' => [(int)($kpi['users'] ?? 0), (int)($kpi['products'] ?? 0), (int)($kpi['categories'] ?? 0)],
    ];

    $ls_labels = [];
    $ls_values = [];
    if ($low_stock) {
        // clone rows into arrays for both table + chart without rewinding result pointer
        while ($row = $low_stock->fetch_object()) {
            $ls_labels[] = (string)$row->name;
            $ls_values[] = (int)$row->total_qty;
        }
        // re-create a simple array for table rendering
        $low_stock_rows = [];
        for ($i = 0; $i < count($ls_labels); $i++) {
            $low_stock_rows[] = (object)[
                'name' => $ls_labels[$i],
                'total_qty' => $ls_values[$i],
            ];
        }
        $low_stock = null; // prevent accidental reuse as mysqli result
    } else {
        $low_stock_rows = [];
    }

    $chart['lowStock'] = [
        'labels' => $ls_labels,
        'values' => $ls_values,
    ];
} else {
    $low_stock_rows = [];
}
?>

<div class="container py-4 py-md-5 app-dashboard">
    <div class="dash-hero shadow-sm mb-4">
        <div class="dash-hero-inner">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                <div>
                    <div class="dash-hero-kicker">Dashboard</div>
                    <div class="dash-hero-title">
                        Welcome<?php echo $user ? ', ' . htmlspecialchars($user->user_label) : ''; ?>
                    </div>
                    <div class="dash-hero-subtitle">
                        <?php if ($is_admin) { ?>
                            Overview of users, products, categories, and stock.
                        <?php } else if ($is_user) { ?>
                            Quick access to your cart and products.
                        <?php } else { ?>
                            Please login to continue.
                        <?php } ?>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <?php if ($is_admin) { ?>
                        <span class="badge text-bg-dark">Admin</span>
                    <?php } else if ($is_user) { ?>
                        <span class="badge text-bg-primary">User</span>
                    <?php } ?>
                    <span class="badge text-bg-light text-muted"><?php echo date('D, M j Y'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <?php if ($is_admin) { ?>
        <div class="row g-3 g-md-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 dash-kpi dash-kpi--primary">
                    <div class="card-body">
                        <div class="dash-kpi-top">
                            <div class="dash-kpi-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M16 11a4 4 0 1 0-8 0 4 4 0 0 0 8 0Z" stroke="currentColor" stroke-width="2" />
                                    <path d="M4 20c1.8-3.5 5.1-5 8-5s6.2 1.5 8 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div>
                                <div class="dash-kpi-label">Users</div>
                                <div class="dash-kpi-value"><?php echo (int)$kpi['users']; ?></div>
                            </div>
                        </div>
                        <div class="dash-kpi-meta">
                            <span class="badge dash-badge">Total users</span>
                        </div>
                        <a class="btn btn-sm dash-kpi-btn" href="./?page=user/home">Manage users</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 dash-kpi dash-kpi--success">
                    <div class="card-body">
                        <div class="dash-kpi-top">
                            <div class="dash-kpi-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M7 7h10v14H7V7Z" stroke="currentColor" stroke-width="2" />
                                    <path d="M9 7V5a3 3 0 0 1 6 0v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    <path d="M9 12h6M9 16h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div>
                                <div class="dash-kpi-label">Products</div>
                                <div class="dash-kpi-value"><?php echo (int)$kpi['products']; ?></div>
                            </div>
                        </div>
                        <div class="dash-kpi-meta">
                            <span class="badge dash-badge">Total products</span>
                        </div>
                        <a class="btn btn-sm dash-kpi-btn" href="./?page=product/home">Manage products</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 dash-kpi dash-kpi--warning">
                    <div class="card-body">
                        <div class="dash-kpi-top">
                            <div class="dash-kpi-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M4 7a3 3 0 0 1 3-3h3v7H7a3 3 0 0 1-3-4Z" stroke="currentColor" stroke-width="2" />
                                    <path d="M14 4h3a3 3 0 0 1 3 3 3 3 0 0 1-3 3h-3V4Z" stroke="currentColor" stroke-width="2" />
                                    <path d="M10 11h4v9H10v-9Z" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </div>
                            <div>
                                <div class="dash-kpi-label">Categories</div>
                                <div class="dash-kpi-value"><?php echo (int)$kpi['categories']; ?></div>
                            </div>
                        </div>
                        <div class="dash-kpi-meta">
                            <span class="badge dash-badge">Total categories</span>
                        </div>
                        <a class="btn btn-sm dash-kpi-btn" href="./?page=category/home">Manage categories</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 dash-kpi dash-kpi--danger">
                    <div class="card-body">
                        <div class="dash-kpi-top">
                            <div class="dash-kpi-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M4 7h16v14H4V7Z" stroke="currentColor" stroke-width="2" />
                                    <path d="M8 7V5h8v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    <path d="M7 12h10M7 16h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div>
                                <div class="dash-kpi-label">Stock qty</div>
                                <div class="dash-kpi-value"><?php echo (int)$kpi['stock_qty']; ?></div>
                            </div>
                        </div>
                        <div class="dash-kpi-meta">
                            <span class="badge dash-badge">Total quantity</span>
                        </div>
                        <a class="btn btn-sm dash-kpi-btn" href="./?page=stock/home">Manage stock</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 g-md-4 mt-1">
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between dash-card-header">
                        <div>
                            <div class="fw-semibold">Overview chart</div>
                            <div class="text-muted small">Users vs Products vs Categories</div>
                        </div>
                        <span class="badge text-bg-light text-muted">Chart</span>
                    </div>
                    <div class="card-body">
                        <div class="dash-chart">
                            <canvas id="dashOverviewChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between dash-card-header">
                        <div>
                            <div class="fw-semibold">Low stock chart</div>
                            <div class="text-muted small">Lowest qty by product</div>
                        </div>
                        <a class="btn btn-sm btn-outline-secondary" href="./?page=stock/home">Stock list</a>
                    </div>
                    <div class="card-body">
                        <div class="dash-chart">
                            <canvas id="dashLowStockChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 g-md-4 mt-1">
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between dash-card-header">
                        <div>
                            <div class="fw-semibold">Low stock</div>
                            <div class="text-muted small">Products with lowest total qty</div>
                        </div>
                        <a class="btn btn-sm btn-outline-secondary" href="./?page=stock/home">Stock list</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th style="width:140px;">Qty</th>
                                        <th style="width:160px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($low_stock_rows)) { ?>
                                        <?php foreach ($low_stock_rows as $row) { ?>
                                            <tr>
                                                <td class="fw-semibold"><?php echo htmlspecialchars($row->name); ?></td>
                                                <td>
                                                    <?php
                                                    $qty = (int)$row->total_qty;
                                                    $badge = $qty <= 5 ? 'text-bg-danger' : ($qty <= 20 ? 'text-bg-warning' : 'text-bg-success');
                                                    ?>
                                                    <span class="badge <?php echo $badge; ?>"><?php echo $qty; ?></span>
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-outline-primary" href="./?page=stock/create">Add stock</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-muted">No products found.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between dash-card-header">
                        <div>
                            <div class="fw-semibold">Recent products</div>
                            <div class="text-muted small">Latest items added</div>
                        </div>
                        <a class="btn btn-sm btn-outline-secondary" href="./?page=product/home">All products</a>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if ($recent_products) { ?>
                            <?php while ($p = $recent_products->fetch_object()) { ?>
                                <a class="list-group-item list-group-item-action d-flex align-items-center justify-content-between"
                                    href="./?page=product/update&id=<?php echo (int)$p->id_product; ?>">
                                    <div class="me-3">
                                        <div class="fw-semibold"><?php echo htmlspecialchars($p->name); ?></div>
                                        <div class="text-muted small">$<?php echo htmlspecialchars((string)$p->price); ?></div>
                                    </div>
                                    <span class="text-muted small">#<?php echo (int)$p->id_product; ?></span>
                                </a>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="list-group-item text-muted">No products found.</div>
                        <?php } ?>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between dash-card-header">
                        <div>
                            <div class="fw-semibold">System flow</div>
                            <div class="text-muted small">How work moves through NPIC POS</div>
                        </div>
                        <span class="badge text-bg-light text-muted">Admin</span>
                    </div>
                    <div class="card-body">
                        <div class="text-muted small">
                            This dashboard includes charts above for quick insights (overview + low stock).
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function () {
                if (typeof window.Chart === "undefined") return;

                const overview = <?php echo json_encode($chart['overview'], JSON_UNESCAPED_SLASHES); ?>;
                const lowStock = <?php echo json_encode($chart['lowStock'], JSON_UNESCAPED_SLASHES); ?>;

                const baseOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: "bottom" },
                        tooltip: { enabled: true }
                    }
                };

                const overviewEl = document.getElementById("dashOverviewChart");
                if (overviewEl && overview) {
                    new Chart(overviewEl, {
                        type: "doughnut",
                        data: {
                            labels: overview.labels,
                            datasets: [{
                                data: overview.values,
                                backgroundColor: ["#0d6efd", "#198754", "#ffc107"],
                                borderColor: "rgba(255,255,255,.9)",
                                borderWidth: 2,
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            ...baseOptions,
                            cutout: "62%"
                        }
                    });
                }

                const lowStockEl = document.getElementById("dashLowStockChart");
                if (lowStockEl && lowStock) {
                    new Chart(lowStockEl, {
                        type: "bar",
                        data: {
                            labels: lowStock.labels,
                            datasets: [{
                                label: "Qty",
                                data: lowStock.values,
                                backgroundColor: "rgba(220, 53, 69, 0.25)",
                                borderColor: "rgba(220, 53, 69, 0.55)",
                                borderWidth: 1.2,
                                borderRadius: 10
                            }]
                        },
                        options: {
                            ...baseOptions,
                            plugins: { ...baseOptions.plugins, legend: { display: false } },
                            scales: {
                                x: { ticks: { maxRotation: 0, autoSkip: true } },
                                y: { beginAtZero: true, ticks: { precision: 0 } }
                            }
                        }
                    });
                }
            })();
        </script>

    <?php } else if ($is_user) { ?>
        <div class="row g-3 g-md-4">
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm dash-user-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div>
                                <div class="text-muted small">My cart (pending)</div>
                                <div class="display-6 fw-bold mb-0"><?php echo (int)$kpi['my_cart']; ?></div>
                                <div class="text-muted small mt-1">Items waiting in your cart</div>
                            </div>
                            <div class="d-grid gap-2">
                                <a class="btn btn-primary" href="./?page=cart/home">Open cart</a>
                                <a class="btn btn-outline-secondary" href="./">Shop products</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between dash-card-header">
                        <div>
                            <div class="fw-semibold">Recent products</div>
                            <div class="text-muted small">Browse new items</div>
                        </div>
                        <a class="btn btn-sm btn-outline-secondary" href="./?page=product/home">View all</a>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if ($recent_products) { ?>
                            <?php while ($p = $recent_products->fetch_object()) { ?>
                                <a class="list-group-item list-group-item-action d-flex align-items-center justify-content-between"
                                    href="./?page=cart/create&id=<?php echo (int)$p->id_product; ?>">
                                    <div class="me-3">
                                        <div class="fw-semibold"><?php echo htmlspecialchars($p->name); ?></div>
                                        <div class="text-muted small">$<?php echo htmlspecialchars((string)$p->price); ?></div>
                                    </div>
                                    <span class="btn btn-sm btn-outline-primary">Add</span>
                                </a>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="list-group-item text-muted">No products found.</div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="fw-semibold">Quick shortcuts</div>
                        <div class="text-muted small">Common actions</div>
                        <div class="d-grid gap-2 mt-3">
                            <a class="btn btn-light" href="./">Home</a>
                            <a class="btn btn-light" href="./?page=cart/home">My cart</a>
                            <a class="btn btn-light" href="./?page=logout">Logout</a>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between dash-card-header">
                        <div>
                            <div class="fw-semibold">Shopping flow</div>
                            <div class="text-muted small">Steps to buy products</div>
                        </div>
                        <span class="badge text-bg-primary">User</span>
                    </div>
                    <div class="card-body">
                        <div class="text-muted small">
                            Charts are currently shown for Admin dashboards. If you want user charts too (ex: cart history), tell me what data to plot.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">
            You must be logged in to view the dashboard.
        </div>
    <?php } ?>
</div>