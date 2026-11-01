<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

---

# PROJECT SPECIFIC RULES: UMKM COURSE HUB

## 1. Project Overview & Scope
Aplikasi ini adalah Platform Kursus Online Keterampilan UMKM berbasis Web Monolith menggunakan Laravel 11, Blade, Tailwind CSS, dan MySQL. 
Tujuan sistem adalah menyediakan pelatihan praktis (keuangan, pemasaran digital, pelayanan pelanggan, inovasi produk) bagi pelaku UMKM.

## 2. Core Architectural Principles
- **Framework & Architecture:** Ikuti standar idiomatis Laravel 11 (MVC architecture).
- **Frontend Stack:** Gunakan Laravel Blade templates dan Tailwind CSS via Vite. Jangan gunakan SPA frameworks (Vue/React/Inertia).
- **Routing & Controllers:** Pisahkan rute Publik/Peserta dan rute Admin di `routes/web.php`.
- **Database Access:** Gunakan Eloquent ORM secara konsisten. Definisikan mass assignment `$fillable` dan return type relasi secara eksplisit.
- **Security & Validation:**
  - Wajib menggunakan Form Request classes atau `$request->validate()`.
  - Hindari XSS dengan sintaks Blade `{{ }}` secara default.
  - Gunakan Middleware untuk proteksi role (`role: admin` vs `role: peserta`).

## 3. Video Handling Rule
Materi video hanya menggunakan URL embed (YouTube/Vimeo). Dilarang membuat mekanisme upload file video lokal atau multipart video storage untuk menjaga performa server tetap ringan.

## 4. Agent Operational Rules
1. **Incremental Execution:** Kerjakan satu modul per waktu sesuai instruksi prompt.
2. **Context Awareness:** Selalu rujuk `TECH_SPEC.md` untuk skema tabel, relasi, dan nama kolom.
3. **Safety First:** Jangan jalankan perintah destruktif tanpa konfirmasi eksplisit.

## 5. UI/UX & Design System Guidelines
- **Design Philosophy:** Clean, professional, and accessible for Indonesian MSMEs (UMKM). Avoid flashy AI aesthetic templates or multi-color purple gradients.
- **Official Color Palette ("Arctic Reflection"):**
  - **Primary (`#243C4C`):** Used for solid primary buttons, active filter pills, active pagination items, active navigation indicators, and card header backgrounds.
  - **Secondary / Accent (`#5289AD`):** Used for interactive text links, icons, button hovers, and focus rings.
  - **Muted Slate (`#698696`):** Used for subtitle text, inactive states, and secondary icons.
  - **Cool Border (`#ACBCBF`):** Used for subtle card borders, dividers, and input outlines.
  - **Surface (`#F4FCFB`):** Used for page backgrounds and soft container surfaces.
- **Badge Rules:**
  - Category Badge: Frosted glass style (`bg-white/85 text-[#243C4C] backdrop-blur-xs border border-white/30`).
  - Active/In-Progress Badge: Soft cool pill (`bg-[#EBF3F6] text-[#243C4C] border border-[#CBDDE6]`).
  - Completed Badge: Soft emerald/sage pill (`bg-[#F0FDF4] text-[#166534] border border-[#BBF7D0]`).
