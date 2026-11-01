<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseContentSeeder extends Seeder
{
    /**
     * Seed comprehensive UMKM courses and lessons.
     */
    public function run(): void
    {
        // 1. Pastikan 4 Kategori Resmi Tersedia
        $categoriesData = [
            [
                'name' => 'Manajemen Keuangan',
                'description' => 'Pelatihan pengelolaan arus kas, pembukuan sederhana, dan perencanaan modal usaha untuk UMKM.',
            ],
            [
                'name' => 'Pemasaran Digital',
                'description' => 'Strategi promosi produk di media sosial, marketplace, dan optimasi konten digital.',
            ],
            [
                'name' => 'Pelayanan Pelanggan',
                'description' => 'Teknik komunikasi efektif, penanganan keluhan, dan membangun loyalitas pelanggan.',
            ],
            [
                'name' => 'Pengembangan Produk',
                'description' => 'Inovasi produk, desain kemasan menarik, serta standarisasi mutu dan legalitas produk.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['name']] = Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                ]
            );
        }

        // 2. Daftar 6 Kursus Komprehensif beserta Materi Pembelajaran
        $coursesData = [
            // Course 1: Pemasaran Digital (12 Materi)
            [
                'category' => 'Pemasaran Digital',
                'title' => 'Strategi Pemasaran Digital Praktis untuk Melejitkan Omzet UMKM',
                'slug' => 'strategi-pemasaran-digital-praktis-umkm',
                'description' => 'Panduan lengkap dari dasar hingga praktik periklanan digital, pembuatan konten kreatif media sosial, pemanfaatan WhatsApp Business, dan strategi optimasi Google Bisnisku untuk pelaku usaha mikro.',
                'is_published' => true,
                'lessons' => [
                    [
                        'order' => 1,
                        'title' => 'Pengenalan Ekosistem Digital Marketing untuk UMKM',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'content' => 'Memahami peta ekosistem pemasaran digital di Indonesia, perubahan perilaku belanja konsumen dari offline ke online, serta pemilihan kanal promosi yang paling sesuai dengan jenis usaha UMKM Anda.',
                    ],
                    [
                        'order' => 2,
                        'title' => 'Menentukan Target Konsumen dan Value Proposition Usaha',
                        'video_url' => 'https://www.youtube.com/embed/ysz5S6PUM-U',
                        'content' => 'Cara menyusun profil pembeli ideal (buyer persona) berdasarkan demografi dan minat, serta merumuskan keunikan nilai produk (value proposition) agar unggul dari kompetitor.',
                    ],
                    [
                        'order' => 3,
                        'title' => 'Membangun Identitas Visual & Branding Sederhana',
                        'video_url' => 'https://www.youtube.com/embed/jNQXAC9IVRw',
                        'content' => 'Panduan memilih palet warna merek, tipografi, logo sederhana, dan gaya komunikasi (tone of voice) yang konsisten agar toko dan produk UMKM mudah diingat pelanggan.',
                    ],
                    [
                        'order' => 4,
                        'title' => 'Optimalisasi Profil WhatsApp Business & Katalog Produk',
                        'video_url' => 'https://www.youtube.com/embed/aqz-KE-bpKQ',
                        'content' => 'Langkah-langkah mengatur profil bisnis resmi di WhatsApp Business, menyusun katalog foto dan harga produk, serta mengaktifkan fitur pesan otomatis (greeting & quick replies).',
                    ],
                    [
                        'order' => 5,
                        'title' => 'Manajemen Konten Media Sosial (Instagram & TikTok)',
                        'video_url' => 'https://www.youtube.com/embed/M7lc1UVf-VE',
                        'content' => 'Menyusun pilar konten edukasi, hiburan, dan promosi, serta membuat kalender konten mingguan untuk Instagram Reels dan TikTok agar akun bisnis tumbuh secara organik.',
                    ],
                    [
                        'order' => 6,
                        'title' => 'Copywriting Memikat untuk Deskripsi Produk',
                        'video_url' => 'https://www.youtube.com/embed/ScMzIvxBSi4',
                        'content' => 'Praktik menulis headline, caption promosi, dan deskripsi produk menggunakan formula AIDA (Attention, Interest, Desire, Action) serta PAS (Problem, Agitate, Solution).',
                    ],
                    [
                        'order' => 7,
                        'title' => 'Fotografi Produk Menggunakan Smartphone',
                        'video_url' => 'https://www.youtube.com/embed/LXb3EKWsInQ',
                        'content' => 'Teknik pencahayaan alami (natural light), pemilihan latar belakang hemat biaya, komposisi sudut pengambilan gambar, dan penyuntingan foto produk di ponsel.',
                    ],
                    [
                        'order' => 8,
                        'title' => 'Pemanfaatan Fitur Google Bisnisku (Google My Business)',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'content' => 'Mendaftarkan titik lokasi usaha di Google Maps melalui Google Business Profile, mengunggah foto tempat usaha, dan mengumpulkan ulasan bintang 5 dari pelanggan lokal.',
                    ],
                    [
                        'order' => 9,
                        'title' => 'Memulai Iklan Berbayar Pemula (Meta Ads / Instagram Ads)',
                        'video_url' => 'https://www.youtube.com/embed/ysz5S6PUM-U',
                        'content' => 'Pengenalan fitur promosi berbayar di Instagram dan Facebook dengan anggaran mulai dari Rp20.000 per hari untuk menjangkau calon pembeli di radius wilayah tertentu.',
                    ],
                    [
                        'order' => 10,
                        'title' => 'Mengukur Kinerja Promosi (Engagement & Conversion)',
                        'video_url' => 'https://www.youtube.com/embed/jNQXAC9IVRw',
                        'content' => 'Cara membaca metrik analitik sederhana seperti jangkauan (reach), interaksi (engagement), jumlah klik link WhatsApp, dan tingkat konversi penjualan.',
                    ],
                    [
                        'order' => 11,
                        'title' => 'Strategi Retensi & Menjaga Loyalitas Pelanggan',
                        'video_url' => 'https://www.youtube.com/embed/aqz-KE-bpKQ',
                        'content' => 'Mengelola database nomor pelanggan untuk program pembelian berulang (repeat order), pemberian voucher khusus pelanggan setia, dan broadcast promosi yang tidak mengganggu.',
                    ],
                    [
                        'order' => 12,
                        'title' => 'Rencana Aksi 30 Hari Go-Digital UMKM',
                        'video_url' => 'https://www.youtube.com/embed/M7lc1UVf-VE',
                        'content' => 'Menyusun lembar kerja (action plan) harian selama 30 hari pertama transformasi digital UMKM agar eksekusi pemasaran berjalan disiplin dan terukur.',
                    ],
                ],
            ],

            // Course 2: Manajemen Keuangan (4 Materi)
            [
                'category' => 'Manajemen Keuangan',
                'title' => 'Pencatatan Keuangan dan Pemisahan Kas Pribadi vs Usaha',
                'slug' => 'pencatatan-keuangan-pemisahan-kas-umkm',
                'description' => 'Cara praktis mengelola arus kas (cashflow), mencatat pembukuan sederhana, dan memisahkan keuangan rumah tangga dengan keuangan bisnis.',
                'is_published' => true,
                'lessons' => [
                    [
                        'order' => 1,
                        'title' => 'Masalah Utama Keuangan UMKM: Kas Campur Aduk',
                        'video_url' => 'https://www.youtube.com/embed/M7lc1UVf-VE',
                        'content' => 'Mengidentifikasi risiko pencampuran uang belanja keluarga dengan uang modal usaha serta cara menetapkan gaji rutin bagi pemilik UMKM.',
                    ],
                    [
                        'order' => 2,
                        'title' => 'Membuat Buku Kas Masuk dan Kas Keluar Sederhana',
                        'video_url' => 'https://www.youtube.com/embed/ScMzIvxBSi4',
                        'content' => 'Panduan mencatat setiap transaksi pemasukan penjualan dan pengeluaran operasional harian secara tertib beserta penyimpanan bukti nota.',
                    ],
                    [
                        'order' => 3,
                        'title' => 'Menghitung Harga Pokok Penjualan (HPP) & Margin Keuntungan',
                        'video_url' => 'https://www.youtube.com/embed/LXb3EKWsInQ',
                        'content' => 'Rumus praktis menghitung biaya bahan baku langsung, biaya tenaga kerja, dan overhead agar harga jual produk tidak merugi.',
                    ],
                    [
                        'order' => 4,
                        'title' => 'Menggunakan Aplikasi Pembukuan Digital Gratis',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'content' => 'Tutorial memanfaatkan aplikasi pencatatan keuangan UMKM di smartphone untuk menghasilkan laporan laba rugi bulanan secara otomatis.',
                    ],
                ],
            ],

            // Course 3: Pelayanan Pelanggan (3 Materi)
            [
                'category' => 'Pelayanan Pelanggan',
                'title' => 'SOP Pelayanan Pelanggan Prima & Penanganan Komplain Konsumen',
                'slug' => 'sop-pelayanan-pelanggan-prima-umkm',
                'description' => 'Standar operasional prosedur ramah pelanggan, teknik membalas chat dengan cepat dan persuasif, serta mitigasi ulasan negatif.',
                'is_published' => true,
                'lessons' => [
                    [
                        'order' => 1,
                        'title' => 'Prinsip Dasar Customer Service Ramah & Cepat',
                        'video_url' => 'https://www.youtube.com/embed/ysz5S6PUM-U',
                        'content' => 'Membangun standar sikap pelayanan 3S (Senyum, Sapa, Solutif) baik pada toko fisik maupun saat melayani pesan pelanggan secara online.',
                    ],
                    [
                        'order' => 2,
                        'title' => 'Template Kalimat Balasan Chat untuk Menutup Penjualan (Closing)',
                        'video_url' => 'https://www.youtube.com/embed/jNQXAC9IVRw',
                        'content' => 'Kumpulan contoh skrip balasan chat WhatsApp dan marketplace mulai dari menjawab stok, memberikan rekomendasi, hingga follow-up calon pembeli.',
                    ],
                    [
                        'order' => 3,
                        'title' => 'Trik Menangani Pelanggan Kecewa dan Komplain Barang Rusak',
                        'video_url' => 'https://www.youtube.com/embed/aqz-KE-bpKQ',
                        'content' => 'Langkah empati dan prosedur penyelesaian komplain konsumen secara profesional agar reputasi toko tetap terjaga dan pelanggan kembali percaya.',
                    ],
                ],
            ],

            // Course 4: Pengembangan Produk (4 Materi)
            [
                'category' => 'Pengembangan Produk',
                'title' => 'Inovasi Kemasan dan Sertifikasi Halal/PIRT untuk Produk Makanan',
                'slug' => 'inovasi-kemasan-sertifikasi-halal-pirt',
                'description' => 'Langkah-langkah memperbarui desain kemasan agar tahan lama dan menarik, serta alur pendaftaran izin edar legal PIRT dan sertifikasi Halal.',
                'is_published' => true,
                'lessons' => [
                    [
                        'order' => 1,
                        'title' => 'Fungsi Proteksi & Daya Tarik Desain Kemasan Makanan',
                        'video_url' => 'https://www.youtube.com/embed/M7lc1UVf-VE',
                        'content' => 'Memahami peran kemasan dalam menjaga ketahanan produk kuliner sekaligus meningkatkan nilai jual (value) di mata konsumen modern.',
                    ],
                    [
                        'order' => 2,
                        'title' => 'Memilih Material Kemasan Sesuai Karakteristik Produk',
                        'video_url' => 'https://www.youtube.com/embed/ScMzIvxBSi4',
                        'content' => 'Mengenal jenis kemasan standing pouch, aluminium foil, plastik vakum, dan karton food-grade beserta standar pelabelan wajib.',
                    ],
                    [
                        'order' => 3,
                        'title' => 'Syarat dan Alur Pengajuan Izin Edar P-IRT',
                        'video_url' => 'https://www.youtube.com/embed/LXb3EKWsInQ',
                        'content' => 'Panduan menyiapkan dokumen SPP-IRT melalui sistem OSS dan Dinas Kesehatan setempat untuk produk pangan olahan skala rumah tangga.',
                    ],
                    [
                        'order' => 4,
                        'title' => 'Alur Sertifikasi Halal Gratis (SEHATI) bagi UMKM',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'content' => 'Cara mendaftar program Sertifikasi Halal Gratis melalui skema pernyataan pelaku usaha (self-declare) di portal SIHALAL BPJPH.',
                    ],
                ],
            ],

            // Course 5: Manajemen Keuangan (3 Materi)
            [
                'category' => 'Manajemen Keuangan',
                'title' => 'Perencanaan Modal Usaha dan Akses Pembiayaan Perbankan/KUR',
                'slug' => 'perencanaan-modal-usaha-akses-kur',
                'description' => 'Pelatihan menyusun kelayakan usaha untuk mengakses pinjaman modal lunak seperti Kredit Usaha Rakyat (KUR) tanpa risiko gagal bayar.',
                'is_published' => true,
                'lessons' => [
                    [
                        'order' => 1,
                        'title' => 'Menghitung Kebutuhan Modal Kerja Riil',
                        'video_url' => 'https://www.youtube.com/embed/ysz5S6PUM-U',
                        'content' => 'Membedakan kebutuhan modal investasi alat (capex) dan modal kerja perputaran bahan baku (opex) sebelum mengajukan pembiayaan.',
                    ],
                    [
                        'order' => 2,
                        'title' => 'Syarat Dokumen & Kriteria Usaha Layak Dibiayai Bank',
                        'video_url' => 'https://www.youtube.com/embed/jNQXAC9IVRw',
                        'content' => 'Memahami prinsip penilaian kredit 5C oleh perbankan, kelengkapan NIB/SKU, serta riwayat SLIK OJK yang bersih.',
                    ],
                    [
                        'order' => 3,
                        'title' => 'Tips Pengelolaan Utang Usaha agar Tetap Sehat',
                        'video_url' => 'https://www.youtube.com/embed/aqz-KE-bpKQ',
                        'content' => 'Menjaga rasio cicilan bulanan terhadap laba bersih serta memastikan dana pinjaman digunakan 100% untuk kegiatan produktif.',
                    ],
                ],
            ],

            // Course 6: Pemasaran Digital (4 Materi)
            [
                'category' => 'Pemasaran Digital',
                'title' => 'Teknik Jualan Laris di Marketplace (Shopee & Tokopedia)',
                'slug' => 'teknik-jualan-laris-marketplace-umkm',
                'description' => 'Optimasi kata kunci judul produk, dekorasi toko online, pemanfaatan voucher diskon, dan trik memenangkan algoritma pencarian marketplace.',
                'is_published' => true,
                'lessons' => [
                    [
                        'order' => 1,
                        'title' => 'Riset Kata Kunci Populer untuk Judul Produk',
                        'video_url' => 'https://www.youtube.com/embed/M7lc1UVf-VE',
                        'content' => 'Teknik mencari kata kunci pencarian tertinggi di Shopee dan Tokopedia agar produk UMKM muncul di halaman pertama hasil pencarian.',
                    ],
                    [
                        'order' => 2,
                        'title' => 'Menata Dekorasi Toko agar Terlihat Profesional dan Tepercaya',
                        'video_url' => 'https://www.youtube.com/embed/ScMzIvxBSi4',
                        'content' => 'Menyusun banner toko, etalase kategori produk, dan foto sampul berstandar tinggi yang meningkatkan rasio klik pengunjung.',
                    ],
                    [
                        'order' => 3,
                        'title' => 'Mengatur Flash Sale, Kombo Hemat, dan Promo Toko',
                        'video_url' => 'https://www.youtube.com/embed/LXb3EKWsInQ',
                        'content' => 'Strategi memanfaatkan fitur promosi internal marketplace seperti Voucher Ikuti Toko, Paket Diskon, dan Gratis Ongkir tanpa menggerus margin.',
                    ],
                    [
                        'order' => 4,
                        'title' => 'Menjaga Reputasi Toko dan Nilai Performa Penjual',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'content' => 'Mengelola kecepatan pengiriman pesanan, tingkat kepuasan chat, dan cara meraih predikat Star Seller / Power Merchant.',
                    ],
                ],
            ],
        ];

        foreach ($coursesData as $courseItem) {
            $category = $categories[$courseItem['category']];

            $course = Course::updateOrCreate(
                ['slug' => $courseItem['slug']],
                [
                    'category_id' => $category->id,
                    'title' => $courseItem['title'],
                    'description' => $courseItem['description'],
                    'thumbnail' => null,
                    'is_published' => $courseItem['is_published'],
                ]
            );

            foreach ($courseItem['lessons'] as $lessonItem) {
                Lesson::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'order' => $lessonItem['order'],
                    ],
                    [
                        'title' => $lessonItem['title'],
                        'video_url' => $lessonItem['video_url'],
                        'content' => $lessonItem['content'],
                    ]
                );
            }
        }
    }
}
