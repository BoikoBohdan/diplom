<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZx7tvnbWL5thE_11q5h1XlHHhhUA968c&v=3.exp&libraries=geometry,drawing,places"></script>
    <title>EAT.CH</title>
</head>
<body>
    <div id="app"></div>

    <script src="{{ mix('js/index.js') }}"></script>
</body>
</html>