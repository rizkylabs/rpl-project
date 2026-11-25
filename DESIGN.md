# EduSync design direction

## Arah visual
Sistem monitoring akademik untuk guru, siswa, orang tua, pihak sekolah, dan admin. Modern, premium, tenang. Referensi: glassmorphism dan neumorphism ringan dengan tipografi sebagai fokus. Palet monochrome; tidak ada gradient, dot pattern, badge dekoratif, maupun ornamen pengisi ruang.

Dial desain: energy 2 / rhythm 3 / motion 2.

## Sistem visual
- Manrope (400–800), fallback system sans-serif. Judul lapang, isi mudah dibaca.
- Light: canvas #eeefef, panel #ffffff, teks #181818, teks sekunder #595959.
- Dark: canvas #171717, panel #222222, teks #f4f4f4, teks sekunder #b9b9b9.
- Radius panel 16px; kontrol 12px. Glass pada navbar, buttonbar dan form login untuk menegaskan layer. Panel data solid untuk keterbacaan. Shadow ringan, bukan glow.
- Login split-screen dengan pesan akademik dan form nyata; desktop navbar horizontal floating tanpa sidebar tetap; mobile buttonbar fixed dengan safe area. Menu admin mobile menggunakan drawer bawaan saat diminta.
- Bahasa Indonesia untuk deskripsi, informasi akademik, dan pesan validasi. Istilah UI umum tetap berbahasa Inggris (Dashboard, Login, Logout, Profile, Theme, Light, Dark, System, Processing..., Loading..., Filter, Settings). Preferensi tema lokal memakai key Filament yang sama.
- GSAP untuk gerak masuk pendek dan scroll reveal berulang pada dashboard custom: section memudar dari opacity 0 ke 1 dengan pergeseran 16px saat masuk area reveal (8–92% viewport), dan berbalik saat keluar dari kedua arah. Layout tetap utuh; halaman tanpa scroll tetap terlihat. Lenis untuk smooth wheel pada pointer presisi. Hormati prefers-reduced-motion dan jaga fokus keyboard tetap terlihat. Login memakai ikon mata/eye-off dengan label aksesibel dan status toggle, tanpa teks visual.
- Satu dashboard custom berdasarkan role yang telah diverifikasi server. Admin menggunakan Filament dengan top navigation dan gaya selaras. Pertahankan rute lama sebagai redirect berizin.
- Tampilkan data nyata; fitur yang belum ada tidak dibuat menjadi menu atau angka dummy.

## Audit anti-slop
Referensi: https://github.com/miqdadbadjuber/anti-slop/blob/main/antislop.md
Gunakan core rulebook sebagai filter selama desain. Jangan menyamakan audit statis dengan pengujian visual. Semua elemen harus mempunyai fungsi; desain harus diuji di kedua tema, perangkat kecil, keyboard, dan keadaan error. Lihat laporan patch untuk status pengujian.

## Navigasi, dropdown dan loading
- Menu hanya untuk rute/fitur yang tersedia. Dropdown portal memakai komponen disclosure yang mendukung keyboard, Escape dan click outside. Dropdown Filament memakai komponen framework dengan styling selaras; gunakan select non-native Filament untuk field baru.
- Satu indikator per interaksi. Form SSR (login, logout, submit) memakai feedback pada tombol, bukan skeleton global. Navigasi halaman memakai skeleton inline di area konten setelah 120 ms untuk koneksi lambat/data saver, atau 320 ms untuk koneksi normal/tidak diketahui. Livewire memakai indikator native pada komponen terkait. Field/table akademik belum ada; gunakan skeleton per komponen ketika fitur tersedia. Tidak ada shimmer gradient atau waktu tunggu buatan. Reduced motion dan data saver memakai skeleton statis. Offline dan request form di atas 30 detik diberi informasi lokal, tanpa retry otomatis. Sebelum HTML pertama diterima, aplikasi belum dapat menampilkan skeleton.

## Profile
- Satu halaman `/profile` untuk seluruh role yang memiliki akses portal. Ringkasan akun dan dua form terpisah untuk personal information serta change password; role ditampilkan sebagai informasi read-only. Gunakan komponen eye yang sama dengan login, feedback tombol per form, dan navbar/buttonbar yang mengikuti route aktif.
- Identitas berasal dari akun autentikasi; tidak ada profil pengguna lain, data akademik rekaan, atau upload avatar sebelum model data dan izin khusus tersedia. Email merupakan identitas login. Validasi dan konfirmasi password saat ini dilakukan di server.

## Organisasi aset frontend
- `resources/css/app.css` adalah entry Vite untuk Tailwind dan import CSS. Token dan dasar UI di `base.css`, komponen reusable di `components.css`, shell di `layout.css`, dan navigasi di `navigation.css`.
- Styling halaman diletakkan pada file sesuai nama halaman: `login.css`, `dashboard.css`, `profile.css`. Untuk halaman baru gunakan nama fitur yang sama dan import melalui `app.css`; jangan menduplikasi aturan global.
- `resources/js/app.js` menginisialisasi komponen bersama dan modul berdasarkan `data-page` pada layout (diatur via section `page` pada Blade). Modul `login.js`, `dashboard.js`, `profile.js` merangkai perilaku halaman dari `components/`. Fitur baru mengikuti konvensi ini. Komponen bersama mencakup theme, dropdown, password-toggle, motion.
- Aset CSS/JS terpisah di source, tetap dibundel lewat entry Vite existing. Aset publik `public/css/edusync/filament.css`, `loading.css`, dan `public/js/edusync/loading.js` tetap terpisah agar lifecycle Filament dan indikator form tidak berubah.
