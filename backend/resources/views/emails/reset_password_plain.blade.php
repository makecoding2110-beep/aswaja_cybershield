ASWAJA CYBERSHIELD - PEMULIHAN KATA SANDI ADMINISTRATOR

Yth. {{ $userName ?? 'Administrator' }},

Kami menerima permintaan untuk mengatur ulang kata sandi akun administrator Anda ({{ $userEmail }}).

Silakan buka tautan berikut di peramban web Anda untuk memasang kata sandi baru:
{{ $resetUrl }}

CATATAN PENTING:
- Tautan ini hanya berlaku selama {{ $expiresIn ?? 60 }} menit sejak permintaan diajukan.
- Waktu Permintaan: {{ $requestedAt ?? 'Baru saja' }}
- Jika Anda tidak merasa melakukan permintaan ini, abaikan pesan ini. Kata sandi akun Anda tetap aman.

---
© {{ date('Y') }} Aswaja CyberShield - Literasi & Keamanan Siber Aswaja
Email ini dikirim secara otomatis oleh sistem keamanan internal. Mohon tidak membalas email ini.
