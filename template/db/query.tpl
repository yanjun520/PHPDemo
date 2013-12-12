<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Net World</title>
</head>
<body>

{%foreach from=$user_list key=key item=show%}
<h1>{%$show.user_name%}</h1>
<h3>{%$show.email%}</h3>
{%/foreach%}

</body>
</html>
