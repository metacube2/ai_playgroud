<?php ob_start(); ?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-500 via-accent-500 to-primary-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold mb-6 text-balance">
            Finde die perfekte Band für dein Event
        </h1>
        <p class="text-xl md:text-2xl mb-8 text-primary-50 max-w-3xl mx-auto text-balance">
            Professionelle Live-Bands in der ganzen Schweiz. Einfach buchen, perfekt performen.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/bands" class="btn bg-white text-primary-600 hover:bg-gray-100 text-lg px-8 py-3">
                Bands entdecken
            </a>
            <a href="/register" class="btn bg-primary-700 text-white hover:bg-primary-800 text-lg px-8 py-3">
                Als Band registrieren
            </a>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-50 rounded-2xl shadow-lg p-8" x-data="searchBands">
            <h2 class="text-3xl font-display font-bold text-center mb-8">Suche deine Band</h2>

            <form @submit.prevent="search" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input
                    type="text"
                    x-model="query"
                    placeholder="Band, Genre, Stil..."
                    class="input-field"
                >
                <input
                    type="text"
                    x-model="filters.location"
                    placeholder="Ort oder PLZ"
                    class="input-field"
                >
                <select x-model="filters.genre" class="input-field">
                    <option value="">Alle Genres</option>
                    <option value="Rock">Rock</option>
                    <option value="Pop">Pop</option>
                    <option value="Jazz">Jazz</option>
                    <option value="Blues">Blues</option>
                    <option value="Funk">Funk</option>
                    <option value="Cover">Cover</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    Suchen
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Featured Bands -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-display font-bold text-center mb-12">Top bewertete Bands</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($featuredBands ?? [] as $band): ?>
                <div class="card group hover:scale-105 transition-transform">
                    <div class="aspect-video bg-gray-200 rounded-lg mb-4 overflow-hidden">
                        <?php if ($band['cover_image']): ?>
                            <img src="<?= $band['cover_image'] ?>" alt="<?= $band['name'] ?>" class="w-full h-full object-cover">
                        <?php endif; ?>
                    </div>
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($band['name']) ?></h3>
                        <span class="badge badge-yellow"><?= htmlspecialchars($band['genre']) ?></span>
                    </div>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?= htmlspecialchars($band['description']) ?></p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-yellow-500 mr-1">⭐</span>
                            <span class="font-semibold"><?= number_format($band['average_rating'], 1) ?></span>
                            <span class="text-gray-500 text-sm ml-1">(<?= $band['total_reviews'] ?>)</span>
                        </div>
                        <a href="/bands/<?= $band['slug'] ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                            Details →
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How it Works -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-display font-bold text-center mb-12">So funktioniert's</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🔍</span>
                </div>
                <h3 class="text-xl font-bold mb-2">1. Suchen</h3>
                <p class="text-gray-600">Finde die perfekte Band für dein Event mit unseren Suchfiltern.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">📧</span>
                </div>
                <h3 class="text-xl font-bold mb-2">2. Anfragen</h3>
                <p class="text-gray-600">Sende eine unverbindliche Anfrage mit deinen Event-Details.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🎉</span>
                </div>
                <h3 class="text-xl font-bold mb-2">3. Buchen</h3>
                <p class="text-gray-600">Bestätige die Buchung und freue dich auf ein unvergessliches Event!</p>
            </div>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php $title = 'Home'; ?>
<?php include __DIR__ . '/layouts/app.php'; ?>
