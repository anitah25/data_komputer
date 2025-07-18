<?php
session_start();

// Cek apakah user sudah login
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

// Proses login
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validasi input
    if(empty($username) || empty($password)) {
        $error = "Username dan password harus diisi";
    } else {
        // Pada implementasi sebenarnya, Anda harus mengecek dari database
        // Contoh sederhana untuk demo
        if($username === 'admin' && $password === 'admin123') {
            // Login berhasil
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = $username;
            $_SESSION['name'] = 'Administrator';
            $_SESSION['role'] = 'admin';
            
            // Redirect ke halaman dashboard
            header("Location: index.php");
            exit();
        } else {
            $error = "Username atau password salah";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Sistem Informasi Komputer ESDM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --accent-color: #3b82f6;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            background-image: linear-gradient(135deg, #f0f2f5 0%, #e9ecef 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        .login-wrapper {
            width: 100%;
            display: flex;
            max-width: 1100px;
            height: 600px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            overflow: hidden;
            background-color: white;
        }

        .login-image {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.9), rgba(59, 130, 246, 0.85)), 
                        url('https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80') center/cover no-repeat;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 50px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-image::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
            pointer-events: none;
        }

        .login-form {
            width: 50%;
            background-color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 1;
        }
        
        .login-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            z-index: -1;
        }

        .login-logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 50px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .login-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark-color);
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 16px;
            color: var(--secondary-color);
            margin-bottom: 30px;
            font-weight: 400;
        }

        .form-control {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            background-color: white;
        }

        .input-group-text {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .input-group .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .input-group-text {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
        }
        
        .btn-primary:hover:before {
            left: 100%;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .forgot-password {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-color);
        }

        .login-footer {
            margin-top: 30px;
            text-align: center;
            color: var(--secondary-color);
            font-size: 14px;
        }

        .alert {
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .animated-background {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            overflow: hidden;
        }

        .animated-background span {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: animate 25s linear infinite;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1), 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .animated-background span:nth-child(1) {
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }

        .animated-background span:nth-child(2) {
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
        }

        .animated-background span:nth-child(3) {
            left: 70%;
            width: 40px;
            height: 40px;
            animation-delay: 4s;
        }

        .animated-background span:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
        }

        .animated-background span:nth-child(5) {
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
        }

        .animated-background span:nth-child(6) {
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
        }

        .animated-background span:nth-child(7) {
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
        }

        .animated-background span:nth-child(8) {
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
        }

        .animated-background span:nth-child(9) {
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
        }

        .animated-background span:nth-child(10) {
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
        }

        @keyframes animate {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }

        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
                height: auto;
                margin: 20px;
                max-height: 90vh;
                overflow-y: auto;
            }

            .login-image, .login-form {
                width: 100%;
                padding: 30px;
            }

            .login-image {
                height: 250px;
                padding-top: 50px;
                padding-bottom: 50px;
            }
            
            body {
                align-items: flex-start;
                padding: 20px 0;
            }
        }

        @media (max-width: 576px) {
            .login-form, .login-image {
                padding: 20px;
            }

            .login-logo {
                margin-bottom: 30px;
            }

            .login-title {
                font-size: 24px;
            }

            .login-subtitle {
                font-size: 14px;
                margin-bottom: 20px;
            }
        }

        /* Password toggle button style */
        .password-toggle {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .delay-1 {
            animation-delay: 0.1s;
        }
        
        .delay-2 {
            animation-delay: 0.2s;
        }
        
        .delay-3 {
            animation-delay: 0.3s;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-image">
            <div class="animated-background">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="text-center">
                <div class="mb-4">
                    <i class="bi bi-pc-display" style="font-size: 4rem; filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));"></i>
                </div>
                <h2 class="mt-4 mb-3 fw-bold" style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">SI-KOMPUTER ESDM</h2>
                <p class="mb-0">Sistem Informasi Pengelolaan Data Komputer<br>Kementerian Energi dan Sumber Daya Mineral</p>
                <div class="mt-4">
                    <span class="badge bg-light text-primary p-2">Versi 1.0</span>
                </div>
            </div>
        </div>
        <div class="login-form">
            <div class="login-logo fade-in">
                <i class="bi bi-pc-display"></i> SI-KOMPUTER ESDM
            </div>
            
            <h1 class="login-title fade-in delay-1">Selamat Datang</h1>
            <p class="login-subtitle fade-in delay-1">Silakan login untuk mengakses panel admin</p>
            
            <?php if(!empty($error)): ?>
            <div class="alert alert-danger fade-in delay-1" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger fade-in delay-1" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            
            <?php if(isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
            <div class="alert alert-success fade-in delay-1" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Anda berhasil keluar dari sistem
            </div>
            <?php endif; ?>
            
            <form action="" method="POST" class="fade-in delay-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                </div>
                
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                    <span class="input-group-text password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4 fade-in delay-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="forgot-password">Lupa password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 fade-in delay-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
                </button>
                
                <div class="text-center mt-4 fade-in delay-3">
                    <a href="index.php" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Kembali ke halaman utama
                    </a>
                </div>
            </form>
            
            <div class="login-footer fade-in delay-3">
                <p>&copy; <?php echo date('Y'); ?> Dinas ESDM | SI-KOMPUTER ESDM v1.0</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
