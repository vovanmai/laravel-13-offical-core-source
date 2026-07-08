<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tài khoản của bạn đã được tạo</title>
</head>
<body>
    <p>Xin chào {{ $name }},</p>

    <p>Tài khoản của bạn đã được tạo trên hệ thống với thông tin đăng nhập sau:</p>

    <ul>
        <li>Email: {{ $email }}</li>
        <li>Mật khẩu: {{ $password }}</li>
    </ul>

    <p>Vì lý do bảo mật, vui lòng đổi mật khẩu ngay trong lần đăng nhập đầu tiên.</p>
</body>
</html>
