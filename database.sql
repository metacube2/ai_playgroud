PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'kunde',
    city TEXT,
    verified INTEGER NOT NULL DEFAULT 0,
    verification_token TEXT,
    created_at TEXT
);

CREATE TABLE IF NOT EXISTS bands (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    name TEXT NOT NULL,
    city TEXT,
    genre TEXT,
    price INTEGER DEFAULT 0,
    description TEXT,
    status TEXT NOT NULL DEFAULT 'prüfung',
    style_tags TEXT,
    video_url TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS band_media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    band_id INTEGER NOT NULL,
    type TEXT NOT NULL,
    url TEXT NOT NULL,
    FOREIGN KEY(band_id) REFERENCES bands(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS band_availability (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    band_id INTEGER NOT NULL,
    event_date TEXT NOT NULL,
    status TEXT NOT NULL,
    FOREIGN KEY(band_id) REFERENCES bands(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    band_id INTEGER NOT NULL,
    user_id INTEGER,
    event_date TEXT,
    location TEXT,
    budget INTEGER,
    event_type TEXT,
    message TEXT,
    status TEXT NOT NULL DEFAULT 'neu',
    created_at TEXT,
    FOREIGN KEY(band_id) REFERENCES bands(id) ON DELETE CASCADE,
    FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS reviews (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    band_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    rating INTEGER NOT NULL,
    comment TEXT,
    status TEXT NOT NULL DEFAULT 'wartend',
    created_at TEXT,
    FOREIGN KEY(band_id) REFERENCES bands(id) ON DELETE CASCADE,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS settings (
    key TEXT PRIMARY KEY,
    value TEXT NOT NULL
);
