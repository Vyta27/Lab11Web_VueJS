# Lab11Web_VueJS
# Praktikum 11 - Frontend dengan VueJS

### Nama : Navyta Budi Yulia
### NIM : 312410184
Kelas : I241B

## Langkah-Langkah Praktikum

## Langkah 1 — Persiapan Struktur Folder

Buat folder baru `lab8_vuejs` di dalam `C:\xampp\htdocs\` dengan struktur berikut:

```
lab8_vuejs/
├── index.html
└── assets/
    ├── css/
    │   └── style.css
    └── js/
        └── app.js
```

Folder `lab8_vuejs` adalah project frontend terpisah dari CodeIgniter. Tidak perlu menjalankan perintah khusus karena diakses langsung lewat Apache XAMPP.

## Langkah 2 — Konfigurasi CORS pada CodeIgniter 4

Karena VueJS (`localhost/lab8_vuejs`) dan API (`localhost/lab11_ci`) berada di domain yang berbeda, perlu dikonfigurasi CORS agar browser mengizinkan request lintas domain.

**File:** `app/Config/Cors.php`

Di sini kita mendefinisikan origin mana saja yang diizinkan mengakses API, method HTTP apa yang boleh digunakan, dan header apa yang boleh dikirim.

**File:** `app/Config/Filters.php`

Filter CORS diaktifkan di bagian `before` dan `after` pada `$globals` agar berjalan di setiap request.

## Langkah 3 — REST API Controller

**File:** `app/Controllers/Post.php`

Controller ini menyediakan endpoint API untuk operasi CRUD artikel menggunakan `ResourceController` bawaan CodeIgniter 4.

| Method | Fungsi |
|--------|--------|
| `index()` | Menampilkan semua artikel (GET /post) |
| `create()` | Menambah artikel baru (POST /post) |
| `show($id)` | Menampilkan satu artikel (GET /post/{id}) |
| `update($id)` | Mengubah artikel (POST /post/{id}/edit) |
| `delete($id)` | Menghapus artikel (DELETE /post/{id}) |


## Langkah 4 — Konfigurasi Route

**File:** `app/Config/Routes.php`

Ditambahkan dua route:
- `$routes->resource('post')` — membuat route CRUD otomatis
- `$routes->post('post/(:num)/edit', 'Post::update/$1')` — route khusus untuk update via POST agar tidak terkena blokir CORS pada method PUT

## Langkah 5 — File index.html

**File:** `lab8_vuejs/index.html`

File HTML utama yang memuat:
- Script VueJS 3 dan Axios dari CDN
- Elemen `<div id="app">` sebagai root Vue
- Tabel untuk menampilkan data artikel
- Modal form untuk tambah dan edit data

Direktif Vue yang digunakan:
- `v-if="showForm"` — menampilkan modal hanya saat diperlukan
- `v-for="(row, index) in artikel"` — loop untuk render setiap baris tabel
- `v-model="formData.judul"` — two-way binding input dengan data
- `@click`, `@submit.prevent` — event handler

## Langkah 6 — File app.js

**File:** `lab8_vuejs/assets/js/app.js`

File JavaScript yang berisi seluruh logika VueJS:

**Bagian `data()`** — menyimpan state aplikasi:
- `artikel` — array data artikel dari API
- `formData` — object data form (id, judul, isi, status)
- `showForm` — boolean untuk kontrol tampil/sembunyi modal

**Bagian `mounted()`** — memanggil `loadData()` otomatis saat halaman pertama kali dibuka.

**Bagian `methods()`:**
- `loadData()` — mengambil semua artikel dari API menggunakan Axios GET
- `tambah()` — membuka form modal dalam mode tambah
- `edit(data)` — membuka form modal dalam mode edit dengan data yang dipilih
- `hapus(index, id)` — mengirim request DELETE ke API lalu menghapus dari array lokal
- `saveData()` — mengirim POST ke API untuk tambah atau edit artikel
- `statusText()` — mengubah nilai 0/1 menjadi teks "Draft"/"Publish"

## Langkah 7 — File style.css

**File:** `lab8_vuejs/assets/css/style.css`

Berisi styling untuk tampilan tabel, modal form, dan tombol-tombol.


## Langkah 8 — Uji Coba

**Cara menjalankan:**
1. Pastikan **Apache** dan **MySQL** di XAMPP sudah Start
2. Buka browser ke `http://localhost/lab8_vuejs/`

**Endpoint API yang digunakan:**

| Method | URL | Fungsi |
|--------|-----|--------|
| GET | `localhost/lab11_ci/ci4/public/post` | Menampilkan semua artikel |
| POST | `localhost/lab11_ci/ci4/public/post` | Menambah artikel baru |
| POST | `localhost/lab11_ci/ci4/public/post/{id}/edit` | Mengubah artikel |
| DELETE | `localhost/lab11_ci/ci4/public/post/{id}` | Menghapus artikel |

---

## Hasil Praktikum

### Tampilan Halaman Utama
Data artikel dimuat otomatis dari API saat halaman dibuka.

📸 *[Screenshot: Halaman localhost/lab8_vuejs/ dengan tabel daftar artikel]*

---

### Fitur Tambah Data
Klik tombol **Tambah Data** → isi form → klik **Simpan**.

📸 *[Screenshot: Modal form tambah data]*

📸 *[Screenshot: Data baru muncul di tabel setelah disimpan]*

---

### Fitur Edit Data
Klik **Edit** pada salah satu baris → ubah data → klik **Simpan**.

📸 *[Screenshot: Modal form edit dengan data terisi]*

📸 *[Screenshot: Data berhasil diubah di tabel]*

---

### Fitur Hapus Data
Klik **Hapus** → konfirmasi → data terhapus dari tabel.

📸 *[Screenshot: Dialog konfirmasi hapus]*

📸 *[Screenshot: Data berhasil dihapus dari tabel]*

---
