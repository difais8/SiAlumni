<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px; /* Atur ukuran logo */
            height: auto;
        }
        .content {
            color: #333333;
            line-height: 1.6;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            background-color: #49aca1; /* Warna tombol sesuai tema */
            color: #ffffff !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="logo-container">
            <img src="{{ asset('images/iconLogoSMP_em.png') }}" alt="Logo SiAlumni" class="logo">
        </div>

        <div class="content">
            <h3>Halo, {{ $notifiable->username }}!</h3>
            
            <p>Kami menerima permintaan untuk mereset password akun SiAlumni Anda.</p>
            <p>Silakan klik tombol di bawah ini untuk membuat password baru:</p>
            
            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Reset Password Saya</a>
            </div>

            <p>Link ini akan kedaluwarsa dalam 60 menit.</p>
            <p>Jika Anda tidak merasa meminta reset password, abaikan saja email ini.</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} SiAlumni.
        </div>
    </div>
</body>
</html>