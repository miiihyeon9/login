<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form id="myForm" action="{{route('mails.mail.post')}}" method="POST">
    @csrf
        <input type="text" name="input_value">
        <button type="button" onclick="submitForm()">전송</button>
    </form>
    <script>
    function submitForm() {
        var form = document.getElementById('myForm');
        form.submit();
    }
</script>
</body>
</html>