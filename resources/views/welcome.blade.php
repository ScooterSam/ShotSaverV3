<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="icon" href="/img/icon.png">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Fonts -->
	<link rel="stylesheet" href="https://rsms.me/inter/inter.css">


	<!-- Styles -->
	<link rel="stylesheet" href="{{ mix('css/app.css') }}">

</head>
<body class="font-sans antialiased bg-gray-900">

<div class="home-header bg-gray-800 relative">
	<div class="absolute top-4 right-4">
		<div class="flex flex-row items-center space-x-4">
			<a class="text-gray-300 font-medium tracking-wide hover:text-gray-50 transition" href="{{route('login')}}">
				Login
			</a>
			<a class="text-gray-300 font-medium tracking-wide hover:text-gray-50 transition" href="{{route('register')}}">
				Register
			</a>
		</div>
	</div>
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
		<div class="max-w-3xl mx-auto text-center h-full flex flex-col items-center justify-center">
			<h1 class="text-shadow text-white text-4xl uppercase tracking-wide">
				<span class="blue text-blue-500">Shot</span>Saver
			</h1>
			<p class="mt-4 text-2xl font-thin text-white tracking-wide">
				Simple image sharing with api access!
			</p>
		</div>
	</div>
</div>
<div class="home-info relative">


	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
		<div class="max-w-3xl mx-auto text-center h-full py-8">


			<div class="grid grid-cols-3 gap-4">

				<div class="px-6 py-4 bg-smoke-100 rounded-lg shadow flex flex-col items-center space-y-2">
					<svg class="w-12 h-12 text-gray-300"
					     fill="none"
					     stroke="currentColor"
					     viewBox="0 0 24 24"
					     xmlns="http://www.w3.org/2000/svg">
						<path stroke-linecap="round"
						      stroke-linejoin="round"
						      stroke-width="2"
						      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
					</svg>

					<p class="text-blue-500 text-lg tracking-wide">
						Register
					</p>

				</div>
				<div class="px-6 py-4 bg-smoke-100 rounded-lg shadow flex flex-col items-center space-y-2">
					<svg class="w-12 h-12 text-gray-300"
					     fill="none"
					     stroke="currentColor"
					     viewBox="0 0 24 24"
					     xmlns="http://www.w3.org/2000/svg">
						<path stroke-linecap="round"
						      stroke-linejoin="round"
						      stroke-width="2"
						      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
					</svg>
					<p class="text-blue-500 text-lg tracking-wide">
						Upload your files
					</p>

				</div>
				<div class="px-6 py-4 bg-smoke-100 rounded-lg shadow flex flex-col items-center space-y-2">
					<svg class="w-12 h-12 text-gray-300"
					     fill="none"
					     stroke="currentColor"
					     viewBox="0 0 24 24"
					     xmlns="http://www.w3.org/2000/svg">
						<path stroke-linecap="round"
						      stroke-linejoin="round"
						      stroke-width="2"
						      d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
					</svg>
					<p class="text-blue-500 text-lg tracking-wide">
						Share
					</p>

				</div>

			</div>


		</div>
	</div>

</div>

</body>
</html>
