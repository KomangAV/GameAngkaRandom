<!DOCTYPE html>
<html>
<head>
    <title>{{ __('messages.title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .correct-animation {
            animation: correctAnimation 1s ease-out forwards;
        }

        @keyframes correctAnimation {
            0% {
                transform: scale(1);
                background-color: #ffffff;
            }
            50% {
                transform: scale(1.1);
                background-color: #4caf50;
            }
            100% {
                transform: scale(1);
                background-color: #ffffff;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div id="game-container" class="bg-white p-8 rounded shadow-md w-1/3">
        <h1 class="text-2xl mb-4">{{ __('messages.title') }}</h1>

        @if (session('message'))
            <div id="message" class="mb-4 text-green-500">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-4">
            <span>Nyawa: </span>
            @for ($i = 0; $i < floor($lives); $i++)
                <span class="text-red-500">♥</span>
            @endfor
            @if ($lives - floor($lives) > 0)
                <span class="text-red-500">♡</span>
            @endif
        </div>

        @if ($hints)
            <div class="mb-4 text-blue-500">
                @foreach ($hints as $hint)
                    <div>{{ $hint }}</div>
                @endforeach
            </div>
        @endif

        @if ($range_hint)
            <div class="mb-4 text-blue-500">
                {{ $range_hint }}
            </div>
        @endif

        <form id="guess-form" action="{{ route('guess') }}" method="POST" class="mb-4">
            @csrf
            <div class="mb-4">
                <label for="guess" class="block text-sm font-medium text-gray-700">{{ __('messages.enter_guess') }}</label>
                <input type="number" name="guess" id="guess" class="mt-1 p-2 border border-gray-300 rounded w-full" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">{{ __('messages.submit') }}</button>
        </form>

        <form id="hint-form" action="{{ route('hint') }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">{{ __('messages.hint') }} ({{ $hint_count }} hints left)</button>
        </form>

        <form action="{{ route('reset') }}" method="GET">
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">{{ __('messages.reset') }}</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('message') && session('message') == __('messages.congratulations'))
                document.getElementById('game-container').classList.add('correct-animation');
            @endif
        });
    </script>
</body>
</html>
