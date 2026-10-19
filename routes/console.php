<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:send-test {email}', function (string $email) {
    $this->info("Sending test email to {$email}...");
    try {
        Mail::raw("Halo! Ini adalah email uji coba dari SORA (Radar HC).\n\nKonfigurasi SMTP berhasil terhubung dan dapat mengirimkan email.", function ($message) use ($email) {
            $message->to($email)->subject('Test Email - SORA Radar HC');
        });
        $this->info("Email successfully sent to {$email}!");
    } catch (Throwable $e) {
        $this->error('Failed to send email: '.$e->getMessage());
    }
})->purpose('Send a test email to verify SMTP configuration');
