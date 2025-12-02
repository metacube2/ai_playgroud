<!DOCTYPE html>
<html lang="de" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? 'GetYourBand' ?> - Bandvermittlung Schweiz</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="/dist/css/app.css">

    <!-- Alpine.js -->
    <script defer src="/dist/js/app.js"></script>
</head>
<body class="h-full">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-display font-bold text-primary-600">
                        🎸 GetYourBand
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-primary-600 transition">Home</a>
                    <a href="/bands" class="text-gray-700 hover:text-primary-600 transition">Bands</a>

                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="/profile" class="text-gray-700 hover:text-primary-600 transition">Profil</a>
                        <form action="/logout" method="POST" class="inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-secondary">Logout</button>
                        </form>
                    <?php else: ?>
                        <a href="/login" class="text-gray-700 hover:text-primary-600 transition">Login</a>
                        <a href="/register" class="btn btn-primary">Registrieren</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <?= $_SESSION['success'] ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-display font-bold text-primary-400 mb-4">GetYourBand</h3>
                    <p class="text-gray-400">Die Plattform für professionelle Bandvermittlung in der Schweiz.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Links</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="/bands" class="text-gray-400 hover:text-white transition">Bands</a></li>
                        <li><a href="/register" class="text-gray-400 hover:text-white transition">Als Band registrieren</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Rechtliches</h4>
                    <ul class="space-y-2">
                        <li><a href="/impressum" class="text-gray-400 hover:text-white transition">Impressum</a></li>
                        <li><a href="/datenschutz" class="text-gray-400 hover:text-white transition">Datenschutz</a></li>
                        <li><a href="/agb" class="text-gray-400 hover:text-white transition">AGB</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> GetYourBand. Alle Rechte vorbehalten.</p>
            </div>
        </div>
    </footer>
</body>
</html>
