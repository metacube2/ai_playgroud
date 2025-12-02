<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Band;

class HomeController extends Controller
{
    public function index(): void
    {
        $bandModel = new Band();

        // Get top-rated bands
        $featuredBands = $bandModel->query(
            "SELECT * FROM bands
             WHERE is_approved = 1 AND is_active = 1
             ORDER BY average_rating DESC, total_reviews DESC
             LIMIT 6"
        );

        $this->view('home', [
            'featuredBands' => $featuredBands,
        ]);
    }
}
