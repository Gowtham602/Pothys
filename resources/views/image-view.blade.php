<!DOCTYPE html>
<html>
<head>
    <title>{{ $image->image_name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{
            margin:0;
            background:#000;
            text-align:center;
        }
        img{
            max-width:100%;
            height:auto;
        }
    </style>
</head>
<body>

    <img src="{{ $imageUrl }}" alt="{{ $image->image_name }}">

</body>
</html>