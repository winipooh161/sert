

<div class="certificate-container">
    <iframe id="certificate-frame" src="{{ route('template.preview', [
        'template' => $certificate->template,
        'recipient_name' => $certificate->recipient_name,
        'amount' => number_format($certificate->amount, 0, '.', ' ') . ' ₽',
        'valid_from' => $certificate->valid_from->format('d.m.Y'),
        'valid_until' => $certificate->valid_until->format('d.m.Y'),
        'message' => $certificate->message ?? '',
        'certificate_number' => $certificate->certificate_number,
        'company_name' => $certificate->user->company ?? config('app.name')
    ]) }}" frameborder="0" width="100%" style="min-height: 600px;"></iframe>
</div>

<style>
    .certificate-container {
    height: 100vh;
}

iframe#certificate-frame {
    height: 100vh;
}
</style>