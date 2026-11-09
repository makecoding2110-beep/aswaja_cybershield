<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reset Kata Sandi - Aswaja CyberShield</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; line-height: 1.6; font-size: 15px;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f6f8; padding: 30px 15px;">
    <tr>
      <td align="center">
        <!-- Main Container Card -->
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 580px; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05); overflow: hidden;">
          
          <!-- Top Accent Bar -->
          <tr>
            <td style="height: 5px; background: linear-gradient(90deg, #059669 0%, #10b981 100%);"></td>
          </tr>

          <!-- Header / Brand Section -->
          <tr>
            <td style="padding: 28px 32px 20px 32px; border-bottom: 1px solid #f1f5f9;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td style="vertical-align: middle;">
                    <div style="font-size: 18px; font-weight: 800; color: #0f172a; letter-spacing: -0.3px;">
                      ASWAJA <span style="color: #059669;">CYBERSHIELD</span>
                    </div>
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">
                      Literasi &amp; Keamanan Siber &bull; Akses Administrator
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Body Content -->
          <tr>
            <td style="padding: 28px 32px;">
              <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #0f172a; letter-spacing: -0.2px;">
                Permintaan Reset Kata Sandi
              </h2>

              <p style="margin: 0 0 14px 0; color: #334155; font-size: 14.5px;">
                Yth. <strong>{{ $userName ?? 'Administrator' }}</strong>,
              </p>

              <p style="margin: 0 0 20px 0; color: #475569; font-size: 14px; line-height: 1.6;">
                Kami menerima permintaan untuk mengatur ulang kata sandi akun administrator Anda (<strong style="color: #0f172a;">{{ $userEmail }}</strong>). Silakan klik tombol di bawah ini untuk membuat kata sandi baru:
              </p>

              <!-- CTA Button -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0 28px 0;">
                <tr>
                  <td align="center" style="border-radius: 10px; background-color: #059669;">
                    <a href="{{ $resetUrl }}" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 14px 30px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 10px; background-color: #059669; letter-spacing: 0.2px;">
                      Atur Ulang Kata Sandi &rarr;
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Notice Box -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 24px;">
                <tr>
                  <td style="padding: 14px 16px; font-size: 12.5px; color: #64748b; line-height: 1.55;">
                    <strong style="color: #334155;">Penting:</strong> Tautan ini aman dan hanya berlaku selama <strong>{{ $expiresIn ?? 60 }} menit</strong> sejak email ini dikirim (Waktu permintaan: {{ $requestedAt ?? 'Baru saja' }}). Jika kedaluwarsa, Anda dapat mengajukan permintaan baru melalui halaman masuk.
                  </td>
                </tr>
              </table>

              <!-- Fallback Direct Link -->
              <p style="margin: 0 0 8px 0; font-size: 12px; color: #64748b;">
                Jika tombol di atas tidak dapat diklik, salin dan tempel alamat tautan verifikasi berikut langsung ke bilah alamat peramban Anda:
              </p>
              <p style="margin: 0; font-size: 12px; font-family: monospace; background-color: #f1f5f9; padding: 10px 12px; border-radius: 8px; word-break: break-all; border: 1px solid #e2e8f0;">
                <a href="{{ $resetUrl }}" target="_blank" rel="noopener noreferrer" style="color: #059669; text-decoration: underline; word-break: break-all;">{{ $resetUrl }}</a>
              </p>

              <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 28px 0 20px 0;">

              <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                Jika Anda tidak pernah meminta pengaturan ulang kata sandi ini, silakan abaikan email ini. Akun dan kata sandi Anda tetap terlindungi dan tidak mengalami perubahan apa pun.
              </p>
            </td>
          </tr>

          <!-- Footer Section -->
          <tr>
            <td style="padding: 20px 32px 24px 32px; background-color: #f8fafc; border-top: 1px solid #f1f5f9; font-size: 11.5px; color: #94a3b8; line-height: 1.5;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td>
                    &copy; {{ date('Y') }} <strong>Aswaja CyberShield</strong> &bull; Literasi &amp; Keamanan Siber Aswaja.<br>
                    Email ini dikirim secara otomatis oleh sistem keamanan internal. Mohon tidak membalas email ini.
                  </td>
                </tr>
              </table>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
