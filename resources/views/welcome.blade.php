<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel=stylesheet>

    <link rel=stylesheet href="{{ mix('css/app.css') }}" />

    <title>{{env('APP_NAME')}}</title><style>body{font-family:'Nunito',sans-serif}</style></head>
<body>
    <div id=app class=wrapper>
		<header id=appHeader class=app-header>
			<app-header></app-header>
		</header>
		<main id=appMain class=app-main>
			<router-view></router-view>
		</main>
    </div> <script src="{{ mix('js/app.js') }}"></script> </body>
</html>
