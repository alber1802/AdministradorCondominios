<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IntelliTower - Luxury Living Redefined</title>

    <!-- TailwindCSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Agrega esto en el <head> de tu HTML -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Custom Styles Simplificados -->
    <link rel="stylesheet" href="{{ asset('css/custom-styles-simple.css') }}">
</head>

<body class="bg-black text-white overflow-x-hidden">

    <!-- Navigation -->
    @include('paginaPrincipal.partials.navigation')

    <!-- Hero Section -->
    @include('paginaPrincipal.partials.hero-section')

    <!-- Apartments Section -->
    @include('paginaPrincipal.partials.apartments-section-simple')
   
    <!-- Common Areas Section -->
    @include('paginaPrincipal.partials.common-areas-section-simple')

    <!-- Smart Living Section -->
    {{-- @include('paginaPrincipal.partials.smart-living-section-simple') --}}

    @include('paginaPrincipal.partials.smart-living-section')

    <!-- Neighborhood Section -->
    @include('paginaPrincipal.partials.neighborhood-section-simple')

    @include('paginaPrincipal.partials.neighborhood-section')

    <!-- Footer -->
    @include('paginaPrincipal.partials.footer')

    <!-- JavaScript Simplificado -->
    <script src="{{ asset('js/main-simple.js') }}"></script>
</body>

</html>
