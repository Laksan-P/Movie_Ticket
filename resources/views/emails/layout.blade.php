<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MovieBuff' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f6f6f6;font-family:Arial,Helvetica,sans-serif;color:#020617;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f6f6f6;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background-color:#01161e;padding:24px 32px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:bold;">MovieBuff</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            {{ $slot }}
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f8fafc;padding:20px 32px;text-align:center;border-top:1px solid #e5e7eb;">
                            <p style="margin:0;font-size:12px;color:#64748b;">&copy; {{ date('Y') }} MovieBuff. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
