<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Kelulusan</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            text-align: center;
            color: #2c3e50;
            background-color: #fff;
            padding: 40px;
            margin: 0;
            height: 100%;
            box-sizing: border-box;
        }
        .border-outer {
            border: 6px double #2c3e50;
            padding: 30px;
            height: 87%;
        }
        .border-inner {
            border: 1px solid #7f8c8d;
            padding: 40px;
            height: 90%;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .logo {
            height: 80px;
            width: auto;
        }
        .header-title {
            font-size: 38px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #2c3e50;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 16px;
            color: #7f8c8d;
            margin-top: 5px;
            letter-spacing: 1px;
        }
        .cert-title {
            font-size: 20px;
            margin-top: 30px;
            color: #34495e;
            font-style: italic;
        }
        .recipient {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            margin: 15px auto;
            border-bottom: 2px solid #2c3e50;
            display: inline-block;
            padding-bottom: 5px;
            min-width: 300px;
        }
        .cert-desc {
            font-size: 16px;
            margin: 15px auto;
            line-height: 1.6;
            max-width: 650px;
            color: #555;
        }
        .course {
            font-size: 26px;
            font-weight: bold;
            color: #16a085;
            margin: 10px 0;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }
        .date {
            font-size: 14px;
            color: #555;
        }
        .organizer {
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
            color: #2c3e50;
        }
        .code {
            font-size: 11px;
            color: #95a5a6;
            margin-top: 15px;
        }
        .qr-container {
            text-align: center;
        }
        .qr-img {
            border: 1px solid #bdc3c7;
            padding: 3px;
        }
        .qr-text {
            font-size: 9px;
            color: #7f8c8d;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="border-outer">
        <div class="border-inner">
            <table class="header-table">
                <tr>
                    <td style="width: 15%; text-align: left; vertical-align: middle;">
                        <img class="logo" src="{{ public_path('images/logo-skb.gif') }}" alt="Logo SKB">
                    </td>
                    <td style="width: 85%; text-align: left; vertical-align: middle; padding-left: 20px;">
                        <div class="header-title">Sertifikat Kelulusan</div>
                        <div class="header-subtitle">Sanggar Kegiatan Belajar (SKB) Kabupaten Banjarnegara</div>
                    </td>
                </tr>
            </table>

            <div class="cert-title">Dengan ini menerangkan bahwa:</div>
            
            <div class="recipient">{{ $user->name }}</div>
            
            <div class="cert-desc">
                telah menyelesaikan dengan baik dan dinyatakan lulus seluruh materi pembelajaran pada kelas:
            </div>
            
            <div class="course">{{ $course->title }}</div>
            
            <div class="cert-desc" style="margin-top: 5px;">
                Pembelajaran ini diselenggarakan secara daring oleh platform {{ $organizerName }}.
            </div>

            <table class="footer-table">
                <tr>
                    <td style="width: 60%; text-align: left; vertical-align: bottom;">
                        <div class="date">Banjarnegara, {{ $certificate->created_at->translatedFormat('d F Y') }}</div>
                        <div class="organizer">{{ $organizerName }}</div>
                        <div class="code">No. Sertifikat: {{ $certificate->certificate_code }}</div>
                    </td>
                    <td style="width: 40%; text-align: right; vertical-align: bottom;">
                        <table style="display: inline-block; border-collapse: collapse;">
                            <tr>
                                <td class="qr-container">
                                    <img class="qr-img" src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code" height="100" width="100">
                                    <div class="qr-text">Pindai untuk verifikasi</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
