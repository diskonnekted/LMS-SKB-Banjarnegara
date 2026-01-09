<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function verify(string $code)
    {
        $certificate = Certificate::with(['user', 'course'])->where('certificate_code', $code)->firstOrFail();

        return view('certificates.verify', [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'course' => $certificate->course,
        ]);
    }

    public function download(Course $course)
    {
        $user = Auth::user();

        // 1. Verify Course Completion
        // Get all lessons in the course
        $totalLessons = $course->modules->flatMap->lessons->count();
        $completedLessons = $user->completedLessons()
            ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
            ->count();

        if ($completedLessons < $totalLessons && $totalLessons > 0) {
            return redirect()->route('courses.show', $course)->with('error', 'You must complete all lessons to get the certificate.');
        }

        // 2. Get or Create Certificate
        $certificate = Certificate::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            ['certificate_code' => 'CERT-'.strtoupper(Str::random(10))]
        );

        // 3. Generate PDF
        $organizerName = Setting::where('key', 'organizer_name')->value('value') ?? 'SKB LMS';
        $verifyUrl = route('certificates.verify', ['code' => $certificate->certificate_code]);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data='.urlencode($verifyUrl);
        $qrResponse = Http::get($qrUrl);
        $qrBase64 = base64_encode($qrResponse->body());

        \Barryvdh\DomPDF\Facade\Pdf::setOptions(['isRemoteEnabled' => true]);

        $pdf = Pdf::loadView('certificates.template', compact('certificate', 'course', 'user', 'organizerName', 'qrBase64'));

        return $pdf->download('certificate-'.$course->slug.'.pdf');
    }
}
