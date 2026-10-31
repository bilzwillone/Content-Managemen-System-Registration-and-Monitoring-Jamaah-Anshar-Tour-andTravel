
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">

    <div class="flex min-h-screen items-center justify-center">
        <div class="w-full max-w-lg rounded-xl bg-white p-8 shadow-md">

            <h1 class="mb-2 text-2xl font-bold text-gray-800">
                {{ $title }}
            </h1>

            <p class="mb-6 text-gray-600">
                Selamat datang di Sistem Monitoring Jamaah Umroh Ansar Travel.
            </p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="rounded-lg bg-red-600 px-5 py-2.5 text-white hover:bg-red-700"
                >
                    Logout
                </button>
            </form>

        </div>
    </div>

</body>
</html>
