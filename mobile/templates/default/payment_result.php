<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>支付异常</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
</head>
<body>
<div>
<h1>支付异常</h1>
<p><?php echo $output['msg']; ?></p>
<p>3秒钟后返回上一页…</p>
</div>
<script>
setTimeout(function() {
    history.go(-1);
}, 3000);
</script>
</body>
</html>
