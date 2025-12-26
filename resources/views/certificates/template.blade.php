<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            text-align: center;
            border: 10px solid #787878;
            padding: 50px;
        }
        .kop {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 10px;
        }
        .kop img.logo {
            height: 80px;
        }
        .header {
            font-size: 50px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .subheader {
            font-size: 25px;
            margin-bottom: 40px;
        }
        .recipient {
            font-size: 40px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px solid #333;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .course {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .date {
            font-size: 18px;
            margin-top: 50px;
        }
        .code {
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }
        .organizer {
            margin-top: 40px;
            font-size: 20px;
            font-weight: bold;
        }
        .qr {
            margin-top: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="kop">
        <img class="logo" src="{{ public_path('images/black.png') }}" alt="SKB Banjarnegara">
        <div>
            <div class="header">Certificate of Completion</div>
            <div class="subheader">This is to certify that</div>
        </div>
    </div>
    
    <div class="recipient">{{ $user->name }}</div>
    

    
    <div class="course">{{ $course->title }}</div>
    
    <div class="organizer">{{ $organizerName }}</div>

    <div class="date">Date: {{ $certificate->created_at->format('F d, Y') }}</div>
    
    <div class="code">Certificate ID: {{ $certificate->certificate_code }}</div>
    
    <div class="qr">
        <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Profile" height="160" width="160">
        <div>Scan untuk melihat profil siswa</div>
    </div>
</body>
</html>
