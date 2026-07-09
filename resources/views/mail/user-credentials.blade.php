<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tài khoản của bạn đã được tạo</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:Arial, Helvetica, sans-serif; color:#333333;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden;">
                    <tr>
                        <td style="background-color:#004b93; padding:20px 32px;">
                            <span style="color:#ffffff; font-size:18px; font-weight:bold;">{{ config('app.name') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p>Xin chào {{ $name }},</p>

                            <p>Tài khoản của bạn đã được tạo trên hệ thống với thông tin đăng nhập sau:</p>

                            <ul>
                                <li>Email: {{ $email }}</li>
                                <li>Mật khẩu: {{ $password }}</li>
                            </ul>

                            <p style="text-align:center; margin:32px 0;">
                                <a href="{{ $loginUrl }}" style="background-color:#004b93; color:#ffffff; text-decoration:none; padding:12px 28px; border-radius:4px; display:inline-block; font-weight:bold;">Đăng nhập ngay</a>
                            </p>

                            <p>Vì lý do bảo mật, vui lòng đổi mật khẩu ngay trong lần đăng nhập đầu tiên.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f0f2f5; padding:20px 32px; text-align:center; font-size:12px; color:#8a8f98;">
                            <p style="margin:0 0 4px;">Email này được gửi tự động từ <strong>{{ config('app.name') }}</strong> — hệ thống quản lý dành cho EVN.</p>
                            <p style="margin:0;">Vui lòng không trả lời email này. Nếu bạn không yêu cầu tạo tài khoản, hãy liên hệ với bộ phận quản trị hệ thống.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
