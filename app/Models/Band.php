<?php

namespace App\Models;

use App\Core\Model;

class Band extends Model
{
    protected string $table = 'bands';

    protected array $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'genre',
        'location',
        'postal_code',
        'price_min',
        'price_max',
        'member_count',
        'phone',
        'website',
        'facebook',
        'instagram',
        'youtube',
        'profile_image',
        'cover_image',
        'is_approved',
        'is_active',
    ];

    public function findBySlug(string $slug): ?array
    {
        return $this->first('slug', $slug);
    }

    public function search(array $filters): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 1 AND is_active = 1";
        $params = [];

        if (!empty($filters['genre'])) {
            $sql .= " AND genre = ?";
            $params[] = $filters['genre'];
        }

        if (!empty($filters['location'])) {
            $sql .= " AND (location LIKE ? OR postal_code LIKE ?)";
            $params[] = "%{$filters['location']}%";
            $params[] = "%{$filters['location']}%";
        }

        if (!empty($filters['price_max'])) {
            $sql .= " AND price_min <= ?";
            $params[] = $filters['price_max'];
        }

        if (!empty($filters['q'])) {
            $sql .= " AND MATCH(name, description, genre) AGAINST (? IN NATURAL LANGUAGE MODE)";
            $params[] = $filters['q'];
        }

        $sql .= " ORDER BY average_rating DESC, total_reviews DESC";

        return $this->query($sql, $params);
    }

    public function incrementViews(int $id): bool
    {
        return $this->execute(
            "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = ?",
            [$id]
        );
    }

    public function updateRating(int $bandId): void
    {
        $sql = "
            UPDATE bands
            SET average_rating = (
                SELECT AVG(rating)
                FROM reviews
                WHERE band_id = ? AND is_approved = 1
            ),
            total_reviews = (
                SELECT COUNT(*)
                FROM reviews
                WHERE band_id = ? AND is_approved = 1
            )
            WHERE id = ?
        ";

        $this->execute($sql, [$bandId, $bandId, $bandId]);
    }
}
