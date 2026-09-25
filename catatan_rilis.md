# Tema Lentera v2603.1.0

Kompatibel dengan OpenSID v2603.0.0+

## Breaking Changes dari v2506/v2409:
- Template: `template.php` → `template.blade.php`
- Lokasi: `desa/themes/lentera/` (masih didukung) atau `storage/app/themes/lentera/`
- Semua view menggunakan Laravel Blade syntax (`@include`, `@yield`, `{{ }}`)
- Commons: `.php` → `.blade.php`
- Widget/Partial: `.php` → `.blade.php`
- Controller memanggil: `view('theme::partials.lapak.index')` bukan `set_template()`

## Fitur Lentera:
- Header modern dengan gambar latar, jam real-time, hitung mundur acara
- Widget aparatur desa carousel responsif
- Menu pintasan layanan 12 kotak
- Statistik nagari (laki/perempuan/total)
- Transparansi anggaran dengan donut ring
- Widget login layanan mandiri
- Grid artikel 4 kolom dengan animasi 3D
- Bottom navigation mobile
- Tombol chat WhatsApp terapung
- Sistem lisensi (kode aktivasi dari desakami.my.id)
- Dark/light mode support
- Responsive semua ukuran layar
