<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AswajaCyberShield password reset</title>
</head>
<body style="margin: 0; padding: 24px 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #111827; background-color: #ffffff; line-height: 1.6; font-size: 15px;">
  <div style="max-width: 560px; margin: 0 auto;">
    <!-- Logo / Main Header Text -->
    <div style="margin-bottom: 24px;">
      <table cellpadding="0" cellspacing="0" border="0" style="vertical-align: middle;">
        <tr>
          <td style="vertical-align: middle; padding-right: 10px;">
            <div style="width: 30px; height: 30px; line-height: 30px; background: #059669; color: #ffffff; text-align: center; border-radius: 8px; font-size: 16px;">🛡️</div>
          </td>
          <td style="vertical-align: middle;">
            <span style="font-size: 20px; font-weight: 700; color: #111827; letter-spacing: -0.3px;">Aswaja CyberShield</span>
          </td>
        </tr>
      </table>
    </div>

    <!-- Instruction Paragraph -->
    <p style="margin: 0 0 16px 0; color: #1f2937; font-size: 15px;">
      To reset your Aswaja CyberShield password, please click this link:
    </p>

    <!-- Interactive Blue Link -->
    <p style="margin: 0 0 32px 0;">
      <a href="{{ $resetUrl ?? ('https://aswajacybershield.com/auth/passwordReset/' . ($token ?? 'RST-placeholder-token')) }}" style="color: #1062fe; text-decoration: underline; word-break: break-all; font-size: 14.5px;">https://aswajacybershield.com/auth/passwordReset/{{ $token ?? 'RST-placeholder-token' }}</a>
    </p>

    <!-- Footer: Copyright and Help Center Link -->
    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280;">
      <span>&copy;2026 AswajaCyberShield</span>
      <span style="margin: 0 8px;">&bull;</span>
      <a href="https://aswajacybershield.com/help" style="color: #6b7280; text-decoration: underline;">Visit our help center</a>
    </div>
  </div>
</body>
</html>
