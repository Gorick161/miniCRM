<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MiniCRM') }}</title>


    <script>
      (function () {
        try {
          var ls = localStorage.getItem('theme');
          var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
          if (ls === 'dark' || (!ls && prefersDark)) {
            document.documentElement.classList.add('dark');
          }
        } catch (e) {}
      })();
    </script>
    <style>
      html { background-color:#0f172a; }
      html.dark { background-color:#0f172a; }
      body { margin:0 }
    </style>

   @vite(['resources/css/auth.css', 'resources/js/app.js'])

</head>
<body class="min-h-screen antialiased">
    {{ $slot }}
</body>
</html>
