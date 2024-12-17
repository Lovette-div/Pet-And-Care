<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php">Pet Care & Adoption</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_pets.php">Manage Pets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_adoptions.php">Manage Adoptions</a>
                        </li>
                    <?php elseif ($_SESSION['role'] == 'user'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">My Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pet_listing.php">Browse Pets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pet_care_tips.php">Pet Care Tips</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
