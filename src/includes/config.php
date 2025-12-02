<?php
const SITE_NAME = 'GetYourBand';
const DB_PATH = __DIR__ . '/../storage/database.sqlite';
const SUPPORT_EMAIL = 'support@getyourband.ch';
const BASE_URL = '';
const COOKIE_NAME = 'gyb_consent';

if (!is_dir(__DIR__ . '/../storage')) {
    mkdir(__DIR__ . '/../storage', 0775, true);
}
