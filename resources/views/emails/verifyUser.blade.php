<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
 
<body>
<h2>Welcome to www.leepeuker.de</h2>
<p>Your successfully registered a new account! Please click on the link below to verify your email address</p>
<br/>
<a href="{{url('user/verify', $user->verification_token)}}">Verify Email</a>
</body>
 
</html>