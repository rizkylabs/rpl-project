# audit navigasi dan loading EduSync

Referensi arah desain: DESIGN.md dan https://github.com/miqdadbadjuber/anti-slop/blob/main/antislop.md. Pemeriksaan statis terfokus pada perubahan ini; bukan sertifikasi seluruh aturan atau hasil render visual.

| pemeriksaan | status |
| --- | --- |
| palette, radius, font dan arah visual existing dipertahankan | PASS (source) |
| tidak ada gradient, dot pattern, badge dekoratif baru | PASS (source) |
| tidak ada menu atau statistik akademik fiktif | PASS (source) |
| dashboard role dan kontrol akses existing tidak berubah | PASS (source) |
| navbar desktop, bottom navigation mobile dan safe area | PASS (source; visual pending) |
| disclosure keyboard, Escape, click outside | PASS (source; browser pending) |
| skeleton berbasis request, tanpa delay buatan, reduced motion | PASS (source; simulasi JS terpisah) |
| kesesuaian kontras visual, reflow mobile, focus order | PENDING (browser) |
| rendering Filament/Livewire, build Vite, installer PowerShell | PENDING (runtime lokal) |

Batasan: SSR tidak dapat menampilkan skeleton sebelum HTML pertama diterima. Skeleton ringkas tidak memblokir interaksi dan tidak menutup seluruh halaman. Admin menggunakan top navigation Filament dan bottom bar dengan akses drawer native melalui tombol Menu. Komponen dropdown native framework yang sudah custom tidak diganti agar integrasi Livewire tetap utuh. Field select/filter akademik belum ada dalam source dan belum dibuat. Network Information API bersifat opsional; fallback menggunakan durasi request. Tidak ada perubahan database, route, atau dependency.

## Revisi motion dan login
- PASS (source): tiga section dasbor custom memakai data-reveal; GSAP menggunakan progress awal serta play/reverse pada masuk/keluar kedua arah. Tidak ada elemen atau tinggi halaman dummy.
- PASS (source): toggle password memakai SVG eye/eye-off, aria-label, aria-pressed, dan tombol non-submit.
- PENDING: pengujian rendering, scroll dua arah dengan GSAP/Lenis nyata, reduced motion, keyboard, mode tema, Vite, dan PowerShell pada project lokal. Filament tidak diubah.

## Revisi bahasa dan indikator loading
- PASS (source): istilah UI yang tersedia menggunakan label Inggris; terjemahan Filament ditimpa melalui lang/vendor, tanpa modifikasi vendor. Deskripsi dan validasi tetap Indonesia.
- PASS (source): submit native memakai satu indikator tombol; event submit tidak memicu skeleton navigasi. Livewire memakai feedback native. Skeleton navigasi inline menggantikan panel floating.
- PENDING: runtime browser, integrasi Filament/Livewire, Vite dan PowerShell. Belum ada tabel akademik untuk diuji; skeleton awal sebelum HTML diterima tidak tersedia pada SSR.

## Profile
- Pemeriksaan source: layout menggunakan token tema, navigasi existing, komponen eye reusable, dan satu indikator submit per form. Tidak ada gradient, dot, atau badge dekoratif baru.
- Role/permission memakai resolver yang sama dengan dashboard. Semua perubahan terbatas ke akun aktif; nama/email dan password punya validasi terpisah, password saat ini wajib, dan password baru di-hash oleh model existing.
- Feature test ditambahkan untuk akses lima role, penolakan guest/permission, isolasi akun, validasi dan penggantian password. Eksekusi test, rendering browser, Vite build, integrasi Filament dan PowerShell perlu verifikasi di lingkungan lokal.

## Modularisasi aset
- Pemeriksaan statis: seluruh selector, deklarasi, dan konteks media CSS lama dipertahankan; aturan gabungan yang digunakan lintas komponen boleh dipisah. Perilaku tema, dropdown, password toggle, GSAP dan Lenis dipindah menjadi modul reusable. Modul halaman dipanggil satu kali lewat data-page; loader publik tidak diubah.
- Tidak ada perubahan backend, database, dependency, atau konfigurasi Vite. Syntax JS, referensi modul, dan manifest diverifikasi. Build Vite, tampilan browser, dan PowerShell perlu diuji di project lokal; pemeriksaan statis bukan verifikasi visual.
