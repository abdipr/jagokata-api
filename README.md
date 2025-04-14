<p align="center"><img src="https://jagokata.com/images/logo-kata2.png" alt="JagoKata"></p>
<h1 align="center">JagoKata REST API</h1>
<p align="center">API RESTful untuk mengambil data kutipan, peribahasa, dan informasi tokoh dari situs <a href="https://jagokata.com" target="_blank">JagoKata</a>. API ini menyediakan berbagai endpoint untuk mengakses kutipan populer, kutipan acak, pencarian kutipan, dan banyak lagi.</p>

## ✨ Dukungan

Jika kamu menyukai project ini, silakan berikan bintang di repository ini, terima kasih ⭐<br>
Kamu juga bisa mendukung saya dengan cara:<br>
<a href="https://trakteer.id/abdipr" target="_blank"><img id="wse-buttons-preview" src="https://cdn.trakteer.id/images/embed/trbtn-red-1.png?date=18-11-2023" height="40" style="border: 0px; height: 40px;" alt="Trakteer Saya"></a>
<a href="https://saweria.co/abdipr" target="_blank"><img height="42" src="https://files.catbox.moe/fwpsve.png"></a>

## Daftar Isi

- [Memulai](#-memulai)
    - [Pengenalan](#pengenalan)
    - [Persyaratan](#persyaratan)
    - [Instalasi](#instalasi)
- [Referensi](#%EF%B8%8F-referensi)
    - [Endpoints](#endpoints)
    - [Parameter Request](#parameter-request)
    - [Parameter Response](#parameter-response)
- [Penanganan Error](#-penanganan-error)
- [Contoh](#-contoh)
    - [Contoh 1: Quotes Populer](#contoh-1-mendapatkan-quotes-populer)
    - [Contoh 2: Cari Quotes](#contoh-2-mencari-quotes)
    - [Contoh 3: Quotes Berdasarkan Tokoh](#contoh-3-mencari-quotes-berdasarkan-tokoh)
- [Kontribusi](#-kontribusi)
- [Lisensi](#%EF%B8%8F-lisensi)
- [Disclaimer](#%EF%B8%8F-disclaimer)

## 🚀 Memulai

### Pengenalan

JagoKata REST API adalah API yang memberikan akses terstruktur ke data kutipan, peribahasa, dan informasi tokoh dari situs JagoKata. API ini cocok untuk aplikasi yang memerlukan data kutipan dan peribahasa secara dinamis tanpa harus langsung mengakses situs JagoKata.

### Persyaratan

- PHP 7.4 atau lebih baru
- [simple_html_dom.php](https://simplehtmldom.sourceforge.io/) untuk parsing HTML
- Akses internet untuk scraping situs JagoKata

### Instalasi

1. Clone repository ini ke server:
    ```bash
    git clone https://github.com/abdipr/jagokata-api.git
    cd jagokata-api
    ```

2. Download dan sertakan `simple_html_dom.php` di direktori project.

3. Atur server kamu untuk menyajikan file PHP (misal: Apache atau Nginx).

4. Atau, kamu bisa langsung deploy ke Vercel<br>
[![Deploy with Vercel](https://vercel.com/button)](https://vercel.com/new/clone?repository-url=https%3A%2F%2Fgithub.com%2Fabdipr%2Fjagokata-api%2F&redirect-url=https%3A%2F%2Fgithub.com%2Fabdipr%2Fjagokata-api%2F)
  Jangan lupa untuk ubah runtime menjadi `Node.js 18.x`

## ❇️ Referensi

### Endpoints
Base URL: https://jagokata-api.vercel.app

| Endpoint                | Deskripsi                                     | Parameter |
| :---------------------- | :-------------------------------------------- | :-------: |
| `GET /popular`          | Mendapatkan quotes populer                    |   `page`  |
| `GET /acak`             | Mendapatkan quotes secara acak                |   `page`  |
| `GET /search`           | Mencari quotes                                |`q`, `page`|
| `GET /tokoh`            | Mencari quotes dari huruf inisial             | `huruf`   |
| `GET /author`           | Mendapatkan semua quotes dari seorang author  |`name`, `page`|
| `GET /peribahasa`       | Mendapatkan peribahasa dari kata              |  `kata`   |
| `GET /peribahasa-acak`  | Mendapatkan 10 peribahasa secara acak         |           |

### Parameter Request
| Parameter | Deskripsi                            |
| :-------: | :----------------------------------- |
|    `q`    | Query pencarian                      |
|  `huruf`  | Inisial nama tokoh                   |
|  `name`   | Nama tokoh lengkap                   |
|  `kata`   | Kata kunci untuk peribahasa          |
|  `page`   | Indikator halaman (opsional)         |

### Parameter Response
| Parameter     | Deskripsi                            |
| :------------ | :----------------------------------- |
| `id`          | ID unik dari quotes                  |
| `author`      | Nama tokoh yang mengucapkan quotes   |
| `text`        | Teks dari quotes atau peribahasa     |
| `category`    | Kategori dari tokoh                  |
| `source`      | Sumber kutipan (jika tersedia)       |

## 💥 Penanganan Error

Semua error mengembalikan objek JSON dengan kode `status` dan `message` yang menjelaskan masalah.

- **404 Error**:
    - Ketika halaman tidak ditemukan atau parameter query hilang.
    ```json
    {
      "status": "404",
      "author": "abdiputranar",
      "message": "Page not found"
    }
    ```

## 🌐 Contoh

### Contoh 1: Mendapatkan Quotes Populer

Ambil quotes populer tanpa parameter:
```http
GET https://jagokata-api.vercel.app/popular
```

### Contoh 2: Mencari Quotes

Cari kutipan dengan kata kunci tertentu:
```http
GET https://jagokata-api.vercel.app/search?q=kehidupan
```

### Contoh 3: Mencari Quotes Berdasarkan Tokoh

Ambil semua quotes dari tokoh dengan nama inisial tertentu:
```http
GET https://jagokata-api.vercel.app/tokoh?huruf=A
```

### Catatan
- **Parameter `q`**: Digunakan untuk pencarian quotes.
- **Parameter `huruf`**: Digunakan untuk menemukan tokoh berdasarkan inisial.
- **Parameter `name`**: Nama lengkap tokoh untuk mengambil semua quotes mereka.
- **Parameter `kata`**: Kata kunci untuk mencari peribahasa.

## 🌱 Kontribusi

Kontribusi diperbolehkan! Untuk berkontribusi:

1. Fork repository ini.
2. Buat branch fitur baru: `git checkout -b fitur-baru`.
3. Commit perubahanmu: `git commit -m 'Menambahkan fitur'`.
4. Push ke branch: `git push origin fitur-baru`.
5. Buat pull request.

## ⚖️ Lisensi

Project ini dilisensikan di bawah `MIT License`. Lihat file [LICENSE](https://github.com/abdipr/jagokata-api/blob/main/LICENSE) untuk informasi lebih lanjut.

## ⚠️ Disclaimer

Data yang disediakan oleh API ini diperoleh dari situs [JagoKata](https://jagokata.com) melalui proses web scraping. Developer yang menggunakan API ini harus mengikuti peraturan yang berlaku dengan mencantumkan project ini atau pemilik resmi dalam project mereka dan dilarang menyalahgunakan API ini untuk keuntungan pribadi.


[⬆️ Kembali ke Atas](#jagokata-rest-api)
