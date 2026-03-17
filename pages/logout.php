<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['confirmLogout'])) {
    session_unset();
    session_destroy();
    header('Location: ./?page=login');
    exit();
}
?>

<style>
    .logout-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .logout-box {
        background: #fff;
        width: 420px;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
    }

    .logout-icon {
        width: 80px;
        height: 80px;
        border: 4px solid #cfd8dc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: #6c757d;
        margin: auto;
    }

    .logout-title {
        margin: 20px 0;
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .logout-title span {
        color: #dc3545;
    }

    .logout-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
    }
</style>

<div class="logout-overlay">
    <div class="logout-box shadow">

        <!-- Icon -->
        <div class="logout-icon">?</div>

        <!-- Title -->
        <div class="logout-title">
            <span>LOG OUT</span> ?
        </div>

        <!-- Buttons -->
        <div class="logout-actions">
            <form method="post">
                <button type="submit" name="confirmLogout" class="btn btn-danger px-4">
                    Yes
                </button>
            </form>

            <a href="./?page=dashboard" class="btn btn-secondary px-4">
                Cancel
            </a>
        </div>

    </div>
</div>