<?php

namespace App\DTO;

class ReportDTO
{
    public function __construct(
        public readonly string $url,
        public readonly string $reporter,
        public readonly string $category,
        public readonly string $description,
        public readonly ?string $evidencePath = null,
        public readonly string $status = 'Masuk',
        public readonly ?string $verificationNotes = null,
        public readonly ?string $handledBy = null
    ) {}

    public static function fromRequest(array $data, ?string $evidencePath = null): self
    {
        return new self(
            url: $data['url'] ?? '',
            reporter: $data['reporter'] ?? $data['nama'] ?? 'Anonim',
            category: $data['category'] ?? $data['kategori'] ?? 'Promosi Situs Judi Online / Slot',
            description: $data['description'] ?? $data['deskripsi'] ?? '',
            evidencePath: $evidencePath,
            status: $data['status'] ?? 'Masuk',
            verificationNotes: $data['verificationNotes'] ?? $data['verification_notes'] ?? 'Laporan baru diterima dari portal publik. Menunggu verifikasi awal.',
            handledBy: $data['handledBy'] ?? $data['assigned_admin'] ?? 'Belum Ditugaskan'
        );
    }
}

