<?php
$identity = $this->request->getAttribute('identity');
$sessionUser = $this->request->getSession()->read('Auth.User');

$isLoggedIn = false;
$userName = '';
$userRole = '';

if ($identity) {
    $isLoggedIn = true;
    $userName = $identity->name ?? 'User';
    $userRole = $identity->role ?? 'user';
} elseif (!empty($sessionUser)) {
    $isLoggedIn = true;
    $userName = $sessionUser['name'] ?? 'User';
    $userRole = $sessionUser['role'] ?? 'user';
}

$isAdmin = strtolower($userRole) === 'admin';
?>

<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>UiTEMU Demo System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6fb;
        }

        /* ===== AUTH PAGES ===== */
        .auth-page {
            min-height: 100vh;
            background: #f4f6fb;
        }

        /* ===== MOBILE MENU ===== */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            font-size: 22px;
            background: #111827;
            color: white;
            padding: 10px;
            border-radius: 10px;
            z-index: 999;
            cursor: pointer;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            background: #0f172a;
            color: white;
            padding: 20px;
            transition: 0.3s;
            z-index: 99;
        }

        .brand {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
            color: #3b82f6;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: 0.3s;
        }

        .nav-link:hover {
            background: #1e293b;
            color: white;
        }

        .nav-link.active {
            background: #3b82f6;
            color: white;
        }

        /* ===== MAIN ===== */
        .main {
            margin-left: 260px;
            padding: 25px;
            width: calc(100% - 260px);
            transition: width 0.2s ease, margin 0.2s ease;
        }

        /* Pretty primary button used by many pages */
        .add-btn {
            background: linear-gradient(135deg,#06b6d4 0%,#3b82f6 100%);
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(59,130,246,0.12);
            border: none;
            text-decoration: none;
            display: inline-block;
            transition: transform .12s ease, box-shadow .12s ease, opacity .12s ease;
        }

        .add-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(59,130,246,0.18);
            opacity: 0.98;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: white;
            padding: 15px 20px;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.04);
        }

        /* ===== SEARCH LIVE ===== */
        .search-box {
            width: 300px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .search-box:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        /* ===== USER INFO ===== */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ensure user-info stays at the right of the topbar */
        .topbar .user-info { margin-left: auto; }

        .badge-ui {
            background: red;
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .logout-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .logout-link:hover {
            text-decoration: underline;
        }

        /* ===== CARDS ===== */
        .card-ui {
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        .card-ui:hover {
            transform: translateY(-5px);
        }

        /* ===== IMAGE GRID ===== */
        .img-grid img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 12px;
        }

        /* ===== MOBILE ===== */
        /* Tablet and below: make main full width and keep sidebar hidden until toggled */
        @media(max-width:1024px){
            .sidebar {
                left: -260px;
            }

            .sidebar.show {
                left: 0;
            }

            .main {
                margin-left: 0;
                width: 100%;
                padding-top: 75px;
            }

            .menu-toggle {
                display: block;
            }

            .topbar {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .search-box {
                width: 100%;
            }
        }

        /* Small phones: make primary buttons full width */
        @media(max-width:768px){
            .add-btn { width: 100%; text-align: center; padding: 12px 16px; }
        }
    </style>
</head>

<body>

<?php if (!$isLoggedIn): ?>

    <!-- LOGIN / REGISTER PAGE WITHOUT SIDEBAR -->
    <div class="auth-page">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </div>

<?php else: ?>

    <!-- MOBILE BUTTON -->
    <div class="menu-toggle" onclick="toggleMenu()">
        <i class="fa fa-bars"></i>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">

        <div class="brand">UiTEMU</div>

        <?= $this->Html->link(
            '<i class="fa fa-home"></i> Dashboard',
            ['controller' => 'Dashboard', 'action' => 'index'],
            ['class' => 'nav-link', 'escape' => false]
        ) ?>

        <?= $this->Html->link(
            '<i class="fa fa-layer-group"></i> Items',
            ['controller' => 'Items', 'action' => 'index'],
            ['class' => 'nav-link', 'escape' => false]
        ) ?>

        <?= $this->Html->link(
            '<i class="fa fa-check"></i> Claims',
            ['controller' => 'Claims', 'action' => 'index'],
            ['class' => 'nav-link', 'escape' => false]
        ) ?>

        <?= $this->Html->link(
            '<i class="fa fa-award"></i> Certificates',
            ['controller' => 'Certificates', 'action' => 'index'],
            ['class' => 'nav-link', 'escape' => false]
        ) ?>

        <?php if ($isAdmin): ?>
            <?= $this->Html->link(
                '<i class="fa fa-users"></i> Users',
                ['controller' => 'Users', 'action' => 'index'],
                ['class' => 'nav-link', 'escape' => false]
            ) ?>
        <?php endif; ?>

    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">

            <?php if ($this->request->getParam('controller') === 'Items'): ?>
            <div style="display:flex; gap:8px; align-items:center;">
                <input class="search-box" id="liveSearch" placeholder="Search items...">
                <select id="searchScope" style="padding:10px; border-radius:10px; border:1px solid #ddd;">
                    <option value="all">All Items</option>
                    <option value="lost">Lost Items</option>
                    <option value="found">Found Items</option>
                </select>
            </div>
            <?php endif; ?>

            <div class="user-info">
                <b><?= h($userName) ?></b>

                <span class="badge-ui">
                    <?= h(strtoupper($userRole)) ?>
                </span>

                <?= $this->Html->link(
                    'Logout',
                    ['controller' => 'Users', 'action' => 'logout'],
                    ['class' => 'logout-link']
                ) ?>
            </div>

        </div>

        <?= $this->Flash->render() ?>

        <?= $this->fetch('content') ?>

    </div>

<?php endif; ?>

<!-- SCRIPTS -->
<script>
function toggleMenu(){
    document.getElementById('sidebar').classList.toggle('show');
}

/* LIVE SEARCH FILTER */
// live search is handled per-page where applicable
</script>

</body>
</html>