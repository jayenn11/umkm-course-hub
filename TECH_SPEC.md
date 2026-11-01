# TECH_SPEC: Platform Kursus Online Keterampilan UMKM

## 1. Stack & Architecture
- Framework: Laravel 11 / PHP 8.2+
- Architecture: Monolithic Model-View-Controller (MVC)
- Database: MySQL
- Frontend: Laravel Blade + Tailwind CSS (Vite)
- Auth: Laravel Breeze (Blade stack)
- Video: Embed Player (YouTube/Vimeo)

## 2. Roles & Permissions
- 'admin': Mengakses dashboard metrik, mengelola kategori, mengelola kursus dan materi.
- 'peserta': Mengeksplorasi katalog, mendaftar kursus (enroll), menonton materi, dan mencatat progres.

## 3. Database Schema Blueprint
1. users: id, name, email, password, role ('admin', 'peserta'), nama_usaha, timestamps
2. categories: id, name, slug, description, timestamps
3. courses: id, category_id (FK), title, slug, description, thumbnail (nullable), is_published (boolean default false), timestamps
4. lessons: id, course_id (FK), title, video_url (nullable), content (text, nullable), order (integer), timestamps
5. enrollments: id, user_id (FK), course_id (FK), enrolled_at, status ('active', 'completed'), timestamps (Unique: user_id + course_id)
6. lesson_completions: id, user_id (FK), lesson_id (FK), completed_at (Unique: user_id + lesson_id)
