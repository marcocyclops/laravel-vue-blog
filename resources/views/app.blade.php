<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ env('APP_NAME', 'My Blog') }}</title>
		@vite
	</head>
	<body class="container mx-auto px-4 antialiased">
		@inertia
	</body>
</html>
