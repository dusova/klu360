<?php
require_once '../includes/config.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erişim Engellendi | Kırklareli Üniversitesi 360° Sanal Tur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .error-container {
            max-width: 600px;
            width: 100%;
            padding: 15px;
            text-align: center;
        }
        
        .error-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background-color: white;
            padding: 30px;
        }
        
        .error-code {
            font-size: 100px;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
            margin-bottom: 20px;
        }
        
        .error-message {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 20px;
        }
        
        .error-description {
            color: #6c757d;
            margin-bottom: 30px;
        }
        
        .error-image {
            max-width: 250px;
            margin: 20px auto;
        }
        
        .btn-back {
            padding: 10px 25px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-code">403</div>
            <div class="error-message">Erişim Engellendi</div>
            <div class="error-description">
                Üzgünüz, bu sayfaya erişim izniniz bulunmuyor. 
                Giriş yapmadıysanız önce giriş yapmanız gerekebilir.
            </div>
            <div>
                <img src="../assets/images/403.svg" alt="403 Error" class="error-image">
            </div>
            <div>
                <a href="../login.php" class="btn btn-primary btn-back me-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Giriş Yap
                </a>
                <a href="../index.php" class="btn btn-outline-primary btn-back">
                    <i class="bi bi-house-fill me-2"></i>Ana Sayfa
                </a>
            </div>
        </div>
    </div>
</body>
</html>