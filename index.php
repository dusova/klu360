<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kırklareli Üniversitesi | 360° Sanal Kampüs Deneyimi</title>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="./assets/js/animations.js"></script>
    <link rel="stylesheet" href="./assets/css/responsive-mobile.css">
    
    <style>
        :root {
            --primary: #0c4da2;
            --primary-dark: #093879;
            --primary-light: #e0eaff;
            --secondary: #e94057;
            --secondary-light: #ffdfe4;
            --accent: #ffc845;
            --dark: #121212;
            --light: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-500: #adb5bd;
            --gray-700: #495057;
            --gray-900: #212529;
            --body-bg: #f5f7fa;
            --text-color: #2d3748;
            --border-radius-sm: 8px;
            --border-radius: 16px;
            --border-radius-lg: 24px;
            --shadow-sm: 0 3px 10px rgba(0, 0, 0, 0.08);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 20px 50px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-color);
            overflow-x: hidden;
            position: relative;
        }

        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(12, 77, 162, 0.9), rgba(9, 56, 121, 0.85)), url('assets/images/campus-aerial.jpg') center/cover no-repeat;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--light);
            overflow: hidden;
        }

        .hero-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1NiIgaGVpZ2h0PSIxMDAiPgo8cmVjdCB3aWR0aD0iNTYiIGhlaWdodD0iMTAwIiBmaWxsPSIjMDAwMCI+PC9yZWN0Pgo8cGF0aCBkPSJNMjggNjZMMCA1MEwwIDc2TDI4IDUwTDU2IDc2TDU2IDUwTDI4IDY2WiIgZmlsbD0iI2ZmZmYiPjwvcGF0aD4KPC9zdmc+');
            opacity: 0.03;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            padding: 0 15px;
        }

        .university-logo {
            width: 160px;
            height: 160px;
            background-color: white;
            border-radius: 50%;
            padding: 15px;
            margin: 0 auto 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .university-logo img {
            max-width: 100%;
            height: auto;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .explore-btn {
            background-color: var(--secondary);
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 15px 40px;
            border-radius: 50px;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: var(--transition);
            box-shadow: 0 10px 25px rgba(233, 64, 87, 0.35);
        }

        .explore-btn i {
            margin-left: 10px;
            font-size: 1.25rem;
            transition: var(--transition);
        }

        .explore-btn:hover {
            background-color: #d83146;
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(233, 64, 87, 0.5);
        }

        .explore-btn:hover i {
            transform: translateX(5px);
        }

        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.8;
            cursor: pointer;
            transition: var(--transition);
        }

        .scroll-indicator:hover {
            opacity: 1;
        }

        .scroll-indicator i {
            font-size: 2rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-15px);
            }
            60% {
                transform: translateY(-7px);
            }
        }

        .campus-showcase {
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 20px;
            display: inline-block;
        }

        .section-title h2:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--accent);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        .section-title p {
            font-size: 1.2rem;
            color: var(--gray-700);
            max-width: 700px;
            margin: 0 auto;
        }

        .bg-dots {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(var(--gray-300) 1px, transparent 1px);
            background-size: 20px 20px;
            top: 0;
            left: 0;
            opacity: 0.3;
            z-index: -1;
        }

        .campus-card {
            background-color: var(--light);
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: var(--transition);
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            height: 100%;
            position: relative;
            border: none;
        }

        .campus-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .campus-image {
            position: relative;
            width: 100%;
            height: 240px;
            overflow: hidden;
        }

        .campus-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s;
        }

        .campus-card:hover .campus-image img {
            transform: scale(1.1);
        }

        .campus-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: var(--accent);
            color: var(--dark);
            border-radius: 50px;
            padding: 5px 15px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .coming-soon-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.65);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .coming-soon-tag {
            background-color: var(--accent);
            color: var(--dark);
            padding: 8px 24px;
            font-weight: 700;
            border-radius: 50px;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            font-size: 1rem;
        }

        .campus-content {
            padding: 25px;
        }

        .campus-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .campus-desc {
            color: var(--gray-700);
            margin-bottom: 20px;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .campus-feature {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            background-color: var(--primary-light);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .feature-text {
            font-size: 0.9rem;
            color: var(--gray-700);
        }

        .tour-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            margin-top: 20px;
            transition: var(--transition);
            border: none;
            text-decoration: none;
        }

        .tour-button i {
            margin-left: 8px;
            transition: var(--transition);
        }

        .tour-button:hover {
            background-color: var(--primary-dark);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(12, 77, 162, 0.2);
        }

        .tour-button:hover i {
            transform: translateX(5px);
        }

        .tour-button.disabled {
            background-color: var(--gray-500);
            cursor: not-allowed;
            opacity: 0.7;
        }

        .tour-button.disabled:hover {
            transform: none;
            box-shadow: none;
        }

        .features-section {
            padding: 100px 0;
            background-color: var(--primary-light);
            position: relative;
            overflow: hidden;
        }

        .features-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCI+CjxwYXRoIGQ9Ik0wIDMwdjMwaDYwVjBIMHYzMHoiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzBjNGRhMiIgc3Ryb2tlLXdpZHRoPSIuNSIgc3Ryb2tlLW9wYWNpdHk9Ii4xIi8+CjxwYXRoIGQ9Ik0zMCAwdjYwTTYwIDMwSDBNMCAwbDYwIDYwTTYwIDBMMCAzMCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMGM0ZGEyIiBzdHJva2Utd2lkdGg9Ii41IiBzdHJva2Utb3BhY2l0eT0iLjEiLz4KPC9zdmc+');
            opacity: 0.5;
            z-index: 0;
        }

        .feature-card {
            background-color: var(--light);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--shadow);
            height: 100%;
            transition: var(--transition);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background-color: var(--primary);
            transition: var(--transition);
        }

        .feature-card:hover::before {
            width: 8px;
        }

        .feature-icon-large {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .feature-card:hover .feature-icon-large {
            transform: scale(1.1);
            color: var(--secondary);
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .feature-description {
            color: var(--gray-700);
            font-size: 0.95rem;
            line-height: 1.7;
        }

        .creators-section {
            padding: 100px 0;
            background-color: var(--light);
            position: relative;
            overflow: hidden;
        }

        .creators-bg {
            position: absolute;
            top: 0;
            right: 0;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, var(--primary-light) 0%, rgba(224, 234, 255, 0) 70%);
            opacity: 0.7;
            z-index: 0;
            transform: translate(30%, -30%);
        }

        .creator-card {
            background-color: var(--light);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            position: relative;
            z-index: 1;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .creator-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .creator-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
            border: 5px solid var(--primary-light);
            transition: var(--transition);
            position: relative;
        }

        .creator-card:hover .creator-avatar {
            border-color: var(--primary);
        }

        .creator-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .creator-card:hover .creator-avatar img {
            transform: scale(1.1);
        }

        .creator-name {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--primary);
        }

        .creator-title {
            font-size: 0.95rem;
            color: var(--gray-700);
            margin-bottom: 20px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--gray-200);
            color: var(--gray-700);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .social-icon:hover {
            background-color: var(--primary);
            color: var(--light);
            transform: translateY(-5px);
        }

        .footer {
            background-color: var(--primary-dark);
            color: var(--light);
            padding: 80px 0 30px;
            position: relative;
            overflow: hidden;
        }

        .footer-pattern {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCI+CjxyZWN0IHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIgZmlsbD0ibm9uZSIvPgo8Y2lyY2xlIGN4PSIzIiBjeT0iNCIgcj0iMSIgZmlsbD0id2hpdGUiIG9wYWNpdHk9IjAuMSIvPgo8Y2lyY2xlIGN4PSIzIiBjeT0iMjUiIHI9IjEiIGZpbGw9IndoaXRlIiBvcGFjaXR5PSIwLjEiLz4KPGNpcmNsZSBjeD0iMjUiIGN5PSI0IiByPSIxIiBmaWxsPSJ3aGl0ZSIgb3BhY2l0eT0iMC4xIi8+CjxjaXJjbGUgY3g9IjI1IiBjeT0iMjUiIHI9IjEiIGZpbGw9IndoaXRlIiBvcGFjaXR5PSIwLjEiLz4KPGNpcmNsZSBjeD0iNDciIGN5PSI0IiByPSIxIiBmaWxsPSJ3aGl0ZSIgb3BhY2l0eT0iMC4xIi8+CjxjaXJjbGUgY3g9IjQ3IiBjeT0iMjUiIHI9IjEiIGZpbGw9IndoaXRlIiBvcGFjaXR5PSIwLjEiLz4KPC9zdmc+');
            opacity: 0.2;
            z-index: 0;
        }

        .footer-logo {
            margin-bottom: 20px;
        }

        .footer-logo img {
            height: 50px;
            width: auto;
        }

        .footer-about {
            margin-bottom: 30px;
            max-width: 400px;
        }

        .footer-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 40px;
            height: 3px;
            background-color: var(--accent);
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: var(--gray-300);
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
        }

        .footer-links a i {
            margin-right: 8px;
            font-size: 0.8rem;
        }

        .footer-links a:hover {
            color: var(--light);
            transform: translateX(5px);
        }

        .contact-info {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .contact-info li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .contact-icon {
            color: var(--accent);
            margin-right: 15px;
            font-size: 1.2rem;
            margin-top: 3px;
        }

        .contact-text {
            color: var(--gray-300);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .copyright {
            text-align: center;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--gray-500);
            font-size: 0.9rem;
        }

        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--primary);
            color: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: var(--transition);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background-color: var(--secondary);
            transform: translateY(-5px);
        }

        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s, visibility 0.5s;
        }

        .loader {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 3px solid var(--primary-light);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            margin-top: 20px;
            font-weight: 600;
            color: var(--primary);
            letter-spacing: 0.5px;
        }

        @media (max-width: 1200px) {
            .hero-title {
                font-size: 3rem;
            }
        }

        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.25rem;
            }

            .section-title h2 {
                font-size: 2.2rem;
            }

            .creators-bg {
                width: 300px;
                height: 300px;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                min-height: 90vh;
            }

            .university-logo {
                width: 140px;
                height: 140px;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .explore-btn {
                padding: 12px 30px;
                font-size: 1rem;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }

            .section-title p {
                font-size: 1rem;
            }

            .campus-showcase, .features-section, .creators-section {
                padding: 70px 0;
            }
            
            .footer {
                padding: 60px 0 30px;
            }
        }

        @media (max-width: 576px) {
            .university-logo {
                width: 120px;
                height: 120px;
            }

            .hero-title {
                font-size: 1.9rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .explore-btn {
                padding: 10px 25px;
                font-size: 0.95rem;
            }

            .section-title h2 {
                font-size: 1.6rem;
            }

            .feature-title {
                font-size: 1.3rem;
            }

            .scroll-indicator {
                bottom: 20px;
            }

            .back-to-top {
                width: 40px;
                height: 40px;
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
</head>
<body>
    <div id="preloader">
        <div class="loader"></div>
        <p class="loading-text">Kırklareli Üniversitesi Sanal Tur yükleniyor...</p>
    </div>

    <section class="hero-section" id="hero">
        <div class="hero-pattern"></div>
        <div class="hero-content">
            <div class="university-logo" data-aos="zoom-in">
                <img src="assets/images/logo.png" alt="Kırklareli Üniversitesi Logo">
            </div>
            <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100">Kırklareli Üniversitesi</h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">360° sanal kampüs turu ile üniversitemizi keşfedin. Tüm kampüslerimizi, fakültelerimizi ve tesislerimizi sanal olarak gezin ve yakından tanıyın.</p>
            <a href="#campus-section" class="explore-btn" data-aos="fade-up" data-aos-delay="300">
                Turu Başlat <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
        <div class="scroll-indicator" id="scroll-down">
            <p>Aşağı Kaydır</p>
            <i class="bi bi-chevron-down"></i>
        </div>
    </section>

    
    <section class="campus-showcase" id="campus-section">
        <div class="bg-dots"></div>
        <div class="container">
            <div class="section-title">
                <h2 data-aos="fade-up">Kampüslerimiz</h2>
                <p data-aos="fade-up" data-aos-delay="100">Üniversitemizin farklı kampüslerini sanal ortamda deneyimleyin ve eğitim olanaklarımızı daha yakından tanıyın.</p>
            </div>

            <div class="row">
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="campus-card">
                        <div class="campus-image">
                            <img src="assets/images/campuses/teknik-bilimler.jpg" alt="Teknik Bilimler MYO Kampüsü">
                            <div class="campus-badge">360° TUR</div>
                        </div>
                        <div class="campus-content">
                            <h3 class="campus-title">Teknik Bilimler MYO</h3>
                            <p class="campus-desc">Teknik Bilimler Meslek Yüksekokulu, öğrencilerimize sektörün ihtiyaçlarına yönelik uygulamalı eğitim sunmaktadır.</p>
                            
                            <div class="campus-feature">
                                <div class="feature-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="feature-text">
                                    Modern eğitim binaları ve laboratuvarlar
                                </div>
                            </div>
                            
                            <div class="campus-feature">
                                <div class="feature-icon">
                                    <i class="bi bi-laptop"></i>
                                </div>
                                <div class="feature-text">
                                    Son teknoloji bilgisayar laboratuvarları
                                </div>
                            </div>
                            
                            <div class="campus-feature">
                                <div class="feature-icon">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="feature-text">
                                    Sosyal aktivite alanları ve spor tesisleri
                                </div>
                            </div>
                            
                            <a href="tour.php?campus=teknik-bilimler" class="tour-button">
                                Sanal Turu Başlat <i class="bi bi-play-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>

                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="campus-card">
                        <div class="campus-image">
                            <img src="assets/images/campuses/kayali.jpg" alt="Kayalı Kampüsü">
                            <div class="coming-soon-overlay">
                                <div class="coming-soon-tag">YAKINDA</div>
                            </div>
                        </div>
                        <div class="campus-content">
                            <h3 class="campus-title">Kayalı Kampüsü</h3>
                            <p class="campus-desc">Ana kampüsümüz olan Kayalı, geniş yeşil alanları ve modern eğitim imkanlarıyla öğrencilerimize konforlu bir ortam sunmaktadır.</p>
                            
                            <div class="campus-feature">
                                <div class="feature-icon">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div class="feature-text">
                                    Merkez kütüphane ve çalışma alanları
                                </div>
                            </div>
                            
                            <div class="campus-feature">
                                <div class="feature-icon">
                                    <i class="bi bi-hospital"></i>
                                </div>
                                <div class="feature-text">
                                    Fakülte binaları ve araştırma merkezleri
                                </div>
                            </div>
                            
                            <div class="campus-feature">
                                <div class="feature-icon">
                                    <i class="bi bi-cup-hot"></i>
                                </div>
                                <div class="feature-text">
                                    Sosyal tesisler ve yemekhaneler
                                </div>
                            </div>
                            
                            <button class="tour-button disabled">
                                Sanal Tur Yakında <i class="bi bi-clock"></i>
                            </button>
                        </div>
                    </div>
                </div>

                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="campus-card">
                        <div class="campus-image">
                            <img src="assets/images/campuses/luleburgaz.jpg" alt="Lüleburgaz Kampüsü">
                            <div class="coming-soon-overlay">
                                <div class="coming-soon-tag">YAKINDA</div>
                            </div>
                        </div>
                        <div class="campus-content">
                            <h3 class="campus-title">Lüleburgaz Kampüsü</h3>
                            <p class="campus-desc">Lüleburgaz kampüsümüz, endüstriyel işbirliklerine yakınlığı ile öğrencilerimize uygulama imkanları sunmaktadır.</p>
                            
                            <div class="campus-feature">
                                <div class="feature-icon">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <div class="feature-text">
                                    Teknoloji ve inovasyon merkezi
                                </div>
                            </div>
                            
                            <div class="campus-feature">
                                <div class="feature-icon">
                                    <i class="bi bi-lightning"></i>
                                </div>
                                <div class="feature-text">
                                    Enerji ve çevre araştırma laboratuvarları
                                </div>
                            </div>
                            
                            <div class="campus-feature">
                                <div class="feature-icon">
                                    <i class="bi bi-broadcast-pin"></i>
                                </div>
                                <div class="feature-text">
                                    Endüstri iş birliği merkezleri
                                </div>
                            </div>
                            
                            <button class="tour-button disabled">
                                Sanal Tur Yakında <i class="bi bi-clock"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="features-section">
        <div class="features-pattern"></div>
        <div class="container">
            <div class="section-title">
                <h2 data-aos="fade-up">Sanal Tur Özellikleri</h2>
                <p data-aos="fade-up" data-aos-delay="100">Gelişmiş sanal tur deneyimimizle kampüslerimizi keşfetmenin avantajlarını keşfedin.</p>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon-large">
                            <i class="bi bi-badge-3d"></i>
                        </div>
                        <h3 class="feature-title">360° Görüntüleme</h3>
                        <p class="feature-description">Her açıdan kampüslerimizi görüntüleyebilir, tüm binaları ve mekanları detaylı inceleyebilirsiniz.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon-large">
                            <i class="bi bi-map"></i>
                        </div>
                        <h3 class="feature-title">İnteraktif Haritalar</h3>
                        <p class="feature-description">Kampüs haritaları ile istediğiniz noktaya hızlıca ulaşabilir, bölümler arası gezinebilirsiniz.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon-large">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <h3 class="feature-title">Bilgilendirme Noktaları</h3>
                        <p class="feature-description">Önemli noktalar hakkında detaylı bilgilere erişebilir, mekanların özelliklerini öğrenebilirsiniz.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon-large">
                            <i class="bi bi-fullscreen"></i>
                        </div>
                        <h3 class="feature-title">Tam Ekran Deneyimi</h3>
                        <p class="feature-description">Tam ekran modunda daha sürükleyici bir deneyim yaşayarak kampüslerimizi gerçekçi şekilde gezebilirsiniz.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon-large">
                            <i class="bi bi-share"></i>
                        </div>
                        <h3 class="feature-title">Paylaşım Özellikleri</h3>
                        <p class="feature-description">Beğendiğiniz mekanları sosyal medyada paylaşabilir, arkadaşlarınızla deneyiminizi paylaşabilirsiniz.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon-large">
                            <i class="bi bi-device-hdd"></i>
                        </div>
                        <h3 class="feature-title">Çoklu Cihaz Desteği</h3>
                        <p class="feature-description">Bilgisayar, tablet ve mobil cihazlarınızdan sorunsuz şekilde turu deneyimleyebilirsiniz.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="creators-section">
        <div class="creators-bg"></div>
        <div class="container">
            <div class="section-title">
                <h2 data-aos="fade-up">Yapımcılar</h2>
                <p data-aos="fade-up" data-aos-delay="100">Sanal tur projemizin arkasındaki yetenekli ekibimizle tanışın.</p>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="creator-card">
                        <div class="creator-avatar">
                            <img src="assets/images/creators/mdusova.webp" alt="Geliştirici">
                        </div>
                        <h3 class="creator-name">Mustafa Arda Düşova</h3>
                        <p class="creator-title">Proje Yöneticisi, Ön-Arka Yüz Geliştirici</p>
                        <div class="social-links">
                            <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="creator-card">
                        <div class="creator-avatar">
                            <img src="assets/images/creators/fatihcbn.jpg" alt="Tasarımcı">
                        </div>
                        <h3 class="creator-name">Fatih Çoban</h3>
                        <p class="creator-title">Panoramik Fotoğrafçı, UI/UX Tasarımcı</p>
                        <div class="social-links">
                            <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="creator-card">
                        <div class="creator-avatar">
                            <img src="assets/images/creators/eylulkay.jpeg" alt="Fotoğrafçı">
                        </div>
                        <h3 class="creator-name">Eylül Kay</h3>
                        <p class="creator-title">Proje Sözcüsü, Sosyal Medya Yönetimi, Görsel Tasarım</p>
                        <div class="social-links">
                            <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="creator-card">
                        <div class="creator-avatar">
                            <img src="assets/images/creators/nadirsubasi.jpg" alt="Danışman">
                        </div>
                        <h3 class="creator-name">Dr. Öğr. Üyesi Nadir Subaşı</h3>
                        <p class="creator-title">Proje Danışmanı</p>
                        <div class="social-links">
                            <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <footer class="footer">
        <div class="footer-pattern"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-logo">
                        <img src="assets/images/logo-white.png" alt="Kırklareli Üniversitesi">
                    </div>
                    <div class="footer-about">
                        <p>Kırklareli Üniversitesi, öğrencilerine kaliteli eğitim ve araştırma imkanları sunan, bölge kalkınmasına katkıda bulunan, ulusal ve uluslararası düzeyde tanınan bir yüksek öğretim kurumudur.</p>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h4 class="footer-title">Hızlı Erişim</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Ana Sayfa</a></li>
                        <li><a href="#campus-section"><i class="bi bi-chevron-right"></i> Kampüsler</a></li>
                        <li><a href="https://www.klu.edu.tr" target="_blank"><i class="bi bi-chevron-right"></i> Üniversite Sitesi</a></li>
                        <li><a href="https://obs.klu.edu.tr" target="_blank"><i class="bi bi-chevron-right"></i> Öğrenci Bilgi Sistemi</a></li>
                        <li><a href="https://www.klu.edu.tr/duyurular" target="_blank"><i class="bi bi-chevron-right"></i> Duyurular</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-title">Bölümlerimiz</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Mühendislik Fakültesi</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> İktisadi ve İdari Bilimler</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Teknik Bilimler MYO</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Sağlık Bilimleri Fakültesi</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Fen Edebiyat Fakültesi</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-title">İletişim</h4>
                    <ul class="contact-info">
                        <li>
                            <div class="contact-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="contact-text">
                                Kırklareli Üniversitesi Rektörlüğü<br>
                                Kayalı Kampüsü, Merkez/Kırklareli
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div class="contact-text">
                                +90 288 212 96 79 
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div class="contact-text">
                            kirklarelirektorluk@klu.edu.tr
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="copyright">
                <p>&copy; 2025 Kırklareli Üniversitesi. Tüm Hakları Saklıdır.</p>
            </div>
        </div>
    </footer>

    
    <div class="back-to-top" id="backToTop">
        <i class="bi bi-arrow-up"></i>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
        });

        $(window).on('load', function() {
            setTimeout(function() {
                $('#preloader').fadeOut(500);
            }, 800);
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });

        document.getElementById('scroll-down').addEventListener('click', function() {
            const campusSection = document.getElementById('campus-section');
            window.scrollTo({
                top: campusSection.offsetTop,
                behavior: 'smooth'
            });
        });

        window.addEventListener('scroll', function() {
            const backToTopBtn = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('visible');
            } else {
                backToTopBtn.classList.remove('visible');
            }
        });

        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>