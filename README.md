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

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/4e350d5e-a05b-42c5-af3d-5aff8bd7c08e" />

### Fitur Tambah Data
Klik tombol **Tambah Data** → isi form → klik **Simpan**.

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/6051af7b-9659-4406-ba0f-2a4a51e82992" />

### Fitur Edit Data
Klik **Edit** pada salah satu baris → ubah data → klik **Simpan**.

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/6967412b-f11a-41fa-84b9-5d6c5999ef1a" />

### Fitur Hapus Data
Klik **Hapus** → konfirmasi → data terhapus dari tabel.

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/28b88686-1389-4494-9b31-deaea3946e50" />


# Praktikum 12 - SPA Frontend VueJS & Vue Router

## Langkah-Langkah Praktikum

### Langkah 1 — Persiapan Struktur Folder

Buka kembali folder `lab8_vuejs` dari praktikum sebelumnya, lalu buat folder baru `components` di dalam `assets/js/`. Struktur folder akhir menjadi:

```
lab8_vuejs/
├── index.html
└── assets/
    ├── css/
    │   └── style.css
    ├── img/
    │   └── foto.jpg
    └── js/
        ├── app.js
        └── components/
            ├── Home.js
            ├── Artikel.js
            └── About.js
```

### Langkah 2 — Modifikasi `index.html`

File `index.html` diubah total menjadi layout SPA. `<div id="app">` dikosongkan karena seluruh tampilan dirender oleh Vue melalui JavaScript. Library Vue Router ditambahkan via CDN. Tiga file komponen dimuat sebelum `app.js` agar sudah tersedia saat router diinisialisasi.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPA Frontend VueJS & Vue Router</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/vue-router@4/dist/vue-router.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div id="app"></div>

    <script src="assets/js/components/Home.js"></script>
    <script src="assets/js/components/Artikel.js"></script>
    <script src="assets/js/components/About.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
```


### Langkah 3 — Membuat Komponen `Home.js`

File `assets/js/components/Home.js` berisi komponen halaman beranda. Komponen ini ditampilkan saat pengguna mengakses route `/`.

```javascript
const Home = {
    template: `
    <div class="home-container">
        <h2>Selamat Datang di Portal Admin Artikel</h2>
        <p>Gunakan menu navigasi di atas untuk mengelola data artikel secara real-time
        memanfaatkan RESTful API CodeIgniter 4 dan VueJS.</p>
    </div>
    `
};
```


### Langkah 4 — Membuat Komponen `Artikel.js`

File `assets/js/components/Artikel.js` berisi seluruh logika CRUD artikel yang dipindahkan dari `app.js` lama. Komponen ini terhubung ke REST API CodeIgniter 4.

```javascript
const Artikel = {
    template: `
    <div>
        <h2>Manajemen Data Artikel</h2>
        <button id="btn-tambah" @click="tambah">Tambah Data</button>

        <div class="modal" v-if="showForm">
            <div class="modal-content">
                <span class="close" @click="showForm = false">&times;</span>
                <form id="form-data" @submit.prevent="saveData">
                    <h3>{{ formTitle }}</h3>
                    <div>
                        <input type="text" v-model="formData.judul"
                               placeholder="Judul Artikel" required>
                    </div>
                    <div>
                        <textarea v-model="formData.isi" rows="6"
                                  placeholder="Isi Artikel" required></textarea>
                    </div>
                    <div>
                        <select v-model="formData.status">
                            <option v-for="option in statusOptions" :value="option.value">
                                {{ option.text }}
                            </option>
                        </select>
                    </div>
                    <input type="hidden" v-model="formData.id">
                    <button type="submit" id="btnSimpan">Simpan</button>
                    <button type="button" @click="showForm = false">Batal</button>
                </form>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, index) in artikel" :key="row.id">
                    <td class="center-text">{{ row.id }}</td>
                    <td>{{ row.judul }}</td>
                    <td>{{ statusText(row.status) }}</td>
                    <td class="center-text">
                        <a href="#" @click.prevent="edit(row)">Edit</a>
                        <a href="#" @click.prevent="hapus(index, row.id)">Hapus</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    `,
    data() {
        return {
            artikel: [],
            formData: { id: null, judul: '', isi: '', status: 0 },
            showForm: false,
            formTitle: 'Tambah Data',
            statusOptions: [
                { text: 'Draft',   value: 0 },
                { text: 'Publish', value: 1 }
            ]
        }
    },
    mounted() {
        this.loadData();
    },
    methods: {
        loadData() {
            axios.get(apiUrl + '/post')
                .then(response => { this.artikel = response.data.artikel; })
                .catch(error => console.log(error));
        },
        tambah() {
            this.showForm  = true;
            this.formTitle = 'Tambah Data';
            this.formData  = { id: null, judul: '', isi: '', status: 0 };
        },
        edit(data) {
            this.showForm  = true;
            this.formTitle = 'Ubah Data';
            this.formData  = { id: data.id, judul: data.judul, isi: data.isi, status: data.status };
        },
        hapus(index, id) {
            if (confirm('Yakin menghapus data?')) {
                axios.delete(apiUrl + '/post/' + id)
                    .then(response => { this.artikel.splice(index, 1); })
                    .catch(error => console.log(error));
            }
        },
        saveData() {
            const params = new URLSearchParams();
            params.append('judul', this.formData.judul);
            params.append('isi', this.formData.isi);
            params.append('status', this.formData.status);

            if (this.formData.id) {
                axios.post(apiUrl + '/post/' + this.formData.id + '/edit', params)
                    .then(response => { this.loadData(); })
                    .catch(error => console.log(error));
            } else {
                axios.post(apiUrl + '/post', params)
                    .then(response => { this.loadData(); })
                    .catch(error => console.log(error));
            }
            this.formData = { id: null, judul: '', isi: '', status: 0 };
            this.showForm = false;
        },
        statusText(status) {
            if (!status) return 'Draft';
            return status == 1 ? 'Publish' : 'Draft';
        }
    }
};
```


### Langkah 5 — Membuat Komponen `About.js` (Tugas)

File `assets/js/components/About.js` menampilkan halaman profil mahasiswa. Ini merupakan tugas tambahan dari modul — menambahkan route `/about` beserta komponen baru.

```javascript
const About = {
    template: `
    <div class="home-container">
        <h2>About</h2>
        <img src="assets/img/foto.jpg" alt="Foto"
             style="width:120px; border-radius:50%; margin-bottom:10px;"><br>
        <table border="1" cellpadding="8" style="border-collapse:collapse;">
            <tr><td><b>Nama</b></td><td>[Nama Lengkap]</td></tr>
            <tr><td><b>NIM</b></td><td>[NIM]</td></tr>
            <tr><td><b>Kelas</b></td><td>[Kelas]</td></tr>
            <tr><td><b>Mata Kuliah</b></td><td>Pemrograman Web 2</td></tr>
        </table>
    </div>
    `
};
```


### Langkah 6 — Konfigurasi Vue Router di `app.js`

File `assets/js/app.js` diperbarui menjadi inti konfigurasi SPA. Di sini didefinisikan routes, instance Vue Router, dan komponen root `App` yang berisi template navigasi dan `<router-view>`.

- `createWebHashHistory()` — menggunakan `#` di URL sehingga tidak perlu konfigurasi server
- `<router-link>` — komponen navigasi Vue Router pengganti tag `<a>`
- `<router-view>` — tempat komponen halaman aktif dirender secara dinamis
- `app.use(router)` — mendaftarkan router ke instance Vue

```javascript
const { createApp } = Vue;
const { createRouter, createWebHashHistory } = VueRouter;

const apiUrl = 'http://localhost/lab11_ci/ci4/public';

const routes = [
    { path: '/',        component: Home },
    { path: '/artikel', component: Artikel },
    { path: '/about',   component: About },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

const App = {
    template: `
    <div>
        <header>
            <h1>Aplikasi Panel Single Page (SPA)</h1>
            <nav class="nav-menu">
                <router-link to="/">Beranda</router-link> |
                <router-link to="/artikel">Kelola Artikel</router-link> |
                <router-link to="/about">About</router-link>
            </nav>
        </header>
        <main style="margin-top: 20px;">
            <router-view></router-view>
        </main>
    </div>
    `
};

const app = createApp(App);
app.use(router);
app.mount('#app');
```


### Langkah 7 — Menambahkan CSS Navigasi di `style.css`

Menambahkan style untuk navbar dan class `.router-link-exact-active` yang otomatis ditambahkan Vue Router pada link aktif.

```css
.nav-menu {
    padding: 10px;
    background: #eff1ff;
    border-radius: 5px;
    margin-bottom: 15px;
}

.nav-menu a {
    text-decoration: none;
    color: #3152d6;
    font-weight: bold;
    padding: 5px 10px;
}

/* Style otomatis saat route aktif */
.router-link-exact-active {
    background-color: #3152d6;
    color: #ffffff !important;
    border-radius: 3px;
}

.home-container {
    padding: 20px;
    border: 1px solid #eff1ff;
    background: #fafafa;
}
```


## Hasil Running Aplikasi

### Halaman Beranda (`localhost/lab8_vuejs/#/`)

Halaman pertama yang muncul saat aplikasi dibuka. Menu **Beranda** aktif ditandai warna biru.

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/06d537c2-06b7-4eaf-86c8-55120c13e382" />

### Halaman Kelola Artikel (`localhost/lab8_vuejs/#/artikel`)

Halaman manajemen artikel yang menampilkan data dari REST API CodeIgniter 4.

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/daf90304-0c7c-46cf-acfa-2c87d2b18e9f" />

### Halaman About (`localhost/lab8_vuejs/#/about`)

Halaman profil mahasiswa dengan foto dan data identitas.

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/7548016e-3c7a-45e9-9e39-93bf5a3de8e8" />

# Praktikum 13 - VueJS Autentikasi dan Navigation Guards (SPA Security)

## Langkah-Langkah Praktikum

## TAHAP 1 — Backend CodeIgniter 4

### Langkah 1.1 — Membuat Auth Controller

Buat folder `Api` di dalam `app/Controllers/`, lalu buat file baru `app/Controllers/Api/Auth.php`.

Controller ini berfungsi menerima request login dari frontend, memverifikasi kredensial user ke database, dan mengembalikan token jika berhasil.

**Penjelasan kode:**
- `$this->request->getVar('username')` — mengambil input username dari request
- `password_verify()` — memverifikasi password yang di-hash menggunakan PHP
- `base64_encode("TOKEN-SECRET-" . $user['username'])` — membuat token sederhana untuk simulasi autentikasi
- `failUnauthorized()` — mengembalikan response HTTP 401 jika login gagal

### Langkah 1.2 — Mendaftarkan Route API Login

Buka `app/Config/Routes.php` dan tambahkan satu baris route:

```php
$routes->post('api/login', 'Api\Auth::login');
```

Route ini mendaftarkan endpoint `POST /api/login` yang akan diakses oleh frontend Vue menggunakan Axios.

## TAHAP 2 — Frontend VueJS

### Langkah 2.1 — Membuat Komponen Login

Buat file baru `assets/js/components/Login.js`.

Komponen ini menampilkan form login dengan dua input (username dan password), lalu mengirimkan data ke API backend menggunakan Axios.

**Penjelasan direktif Vue yang digunakan:**
- `v-model="username"` — two-way binding input ke data `username`
- `@submit.prevent="handleLogin"` — mencegah reload halaman saat form disubmit
- `v-if="errorMessage"` — menampilkan pesan error hanya jika ada

**Alur login:**
1. User isi form dan klik tombol Masuk
2. Axios POST ke `apiUrl + '/api/login'`
3. Jika berhasil → simpan `isLoggedIn` dan `userToken` ke localStorage → redirect ke `/artikel`
4. Jika gagal → tampilkan pesan error di bawah form

### Langkah 2.2 — Mengonfigurasi Navigation Guards di app.js

Buka `assets/js/app.js` dan lakukan 3 perubahan utama:

**1. Tambah rute Login dan properti meta:**
- Rute `/login` didaftarkan dengan komponen `Login`
- Rute `/artikel` dan `/about` diberi properti `meta: { requiresAuth: true }` — menandakan rute ini hanya boleh diakses setelah login

**2. Tambah fungsi `router.beforeEach()`:**
Fungsi ini dipanggil setiap kali user berpindah halaman. Logikanya:
- Cek apakah rute tujuan memerlukan autentikasi (`requiresAuth: true`)
- Jika ya, cek localStorage apakah `isLoggedIn === 'true'`
- Jika belum login → tampilkan alert dan redirect ke `/login`
- Jika sudah login → izinkan akses (`next()`)

**3. Tambah method `logout()`:**
Menghapus data localStorage dan redirect ke halaman beranda.

### Langkah 2.3 — Menyesuaikan index.html

Buka `index.html` dan lakukan perubahan:

**Yang diubah:**
- Judul halaman menjadi `Secured SPA Frontend VueJS`
- Tambah script `Login.js` sebelum `app.js`
- Tambah menu navigasi Login/Logout yang dinamis:
  - `v-if="!isLoggedIn"` — tampilkan link Login jika belum login
  - `v-else` — tampilkan link Logout jika sudah login
  - `@click.prevent="logout"` — panggil method logout saat diklik


### Langkah 2.4 — Menambahkan CSS Form Login

Tambahkan CSS berikut di paling bawah `assets/css/style.css` untuk styling form login:

- `.login-container` — memusatkan form di tengah halaman menggunakan flexbox
- `.login-box` — kotak form dengan border, shadow, dan background putih
- `.btn-login` — tombol submit berwarna biru
- `.error-msg` — teks error berwarna merah di bawah form


## Hasil Pengujian

### Skenario A — Akses Ditolak (Belum Login)

1. Buka `http://localhost/lab8_vuejs/`
2. Klik menu **Kelola Artikel**
3. Sistem menampilkan alert "Akses Ditolak! Anda harus login terlebih dahulu."
4. Halaman otomatis diarahkan ke form Login

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/f3ea03de-dc29-4976-86e8-0d7540a30f93" />
<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/09200cf7-e577-41e3-8108-fa67eb3db67a" />

### Skenario B — Login Berhasil

1. Buka halaman Login di `http://localhost/lab8_vuejs/#/login`
2. Masukkan username: `admin` dan password: `admin123`
3. Sistem memvalidasi ke database backend melalui Axios
4. Jika berhasil → masuk ke halaman Kelola Artikel
5. Menu navigasi berubah dari **Login** menjadi **Logout**

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/1cf7b0a3-6ea4-4b82-8c17-6c0d472dc3ba" />

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/353d3fba-14d0-48d0-b46f-7f3101e8079c" />

### Skenario C — Logout

1. Klik link **Logout** di menu navigasi
2. Muncul konfirmasi "Apakah Anda yakin ingin keluar?"
3. Klik OK → localStorage dihapus → kembali ke halaman Beranda
4. Menu navigasi kembali menampilkan link **Login**

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/26f84f4b-26e4-4c2d-a608-c6956a44525d" />

<img width="960" height="504" alt="Image" src="https://github.com/user-attachments/assets/f20fecc4-fbc5-44cd-9984-2a5014ea035a" />


# Praktikum 14 - Keamanan API, Token Authentication, dan Axios Interceptors

## Langkah-Langkah Praktikum

## TAHAP 1 — Backend CodeIgniter 4

### Langkah 1.1 — Membuat ApiAuthFilter

Buat file baru `app/Filters/ApiAuthFilter.php`.

Filter ini bertugas memeriksa setiap request yang masuk ke endpoint yang diproteksi. Jika tidak ada token di header `Authorization`, server langsung menolak request dengan response HTTP 401.

**Penjelasan kode:**
- `$request->getServer('HTTP_AUTHORIZATION')` — mengambil nilai header Authorization dari request HTTP
- `preg_match('/Bearer\s(\S+)/', $authHeader, $matches)` — mengekstrak nilai token dari format `Bearer <token>`
- `Services::response()->setStatusCode(401)` — mengembalikan HTTP 401 Unauthorized jika token tidak ada atau tidak valid
- Method `after()` dibiarkan kosong karena tidak ada aksi yang perlu dilakukan setelah response dikirim

```php
<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');

        if (!$authHeader) {
            $response = Services::response();
            $response->setStatusCode(401);
            return $response->setJSON([
                'status'   => 401,
                'error'    => 401,
                'messages' => 'Akses Ditolak. Token tidak ditemukan pada request!'
            ]);
        }

        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if (!$token || empty($token)) {
            $response = Services::response();
            $response->setStatusCode(401);
            return $response->setJSON([
                'status'   => 401,
                'error'    => 401,
                'messages' => 'Sesi Token tidak valid atau kedaluwarsa!'
            ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak diperlukan aksi setelah request
    }
}
```

### Langkah 1.2 — Mendaftarkan Filter di Filters.php

Buka `app/Config/Filters.php` dan tambahkan alias `apiauth` di bagian `$aliases`:

```php
'apiauth' => \App\Filters\ApiAuthFilter::class,
```

**Penjelasan:**
Mendaftarkan alias `apiauth` memungkinkan filter dipanggil menggunakan nama pendek di konfigurasi route, tanpa perlu menulis namespace lengkap setiap saat.

### Langkah 1.3 — Menerapkan Filter ke Route

Buka `app/Config/Routes.php` dan tambahkan route yang diproteksi dengan filter `apiauth`:

```php
// Route yang diproteksi — hanya bisa diakses dengan token
$routes->post('post', 'Post::create', ['filter' => 'apiauth']);
$routes->put('post/(:segment)', 'Post::update/$1', ['filter' => 'apiauth']);
$routes->delete('post/(:segment)', 'Post::delete/$1', ['filter' => 'apiauth']);

// Route resource tetap ada untuk GET (tidak diproteksi)
$routes->resource('post');
```

**Penjelasan:**
- Hanya operasi yang mengubah data (POST, PUT, DELETE) yang diproteksi dengan token
- Operasi GET dibiarkan terbuka agar data artikel tetap bisa dibaca publik
- `['filter' => 'apiauth']` — menerapkan ApiAuthFilter pada route tersebut


## TAHAP 2 — Frontend VueJS

### Langkah 2.1 — Menambahkan Axios Interceptors di app.js

Buka `assets/js/app.js` dan tambahkan dua blok interceptor sebelum definisi routes.

**Request Interceptor — Penyuntik Token Otomatis:**

```javascript
axios.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('userToken');
        if (token) {
            config.headers['Authorization'] = 'Bearer ' + token;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);
```

**Penjelasan:**
- Setiap kali Axios mengirim request (GET, POST, DELETE, dll), interceptor ini otomatis berjalan terlebih dahulu
- `localStorage.getItem('userToken')` — mengambil token yang disimpan saat login berhasil
- `config.headers['Authorization'] = 'Bearer ' + token` — menyuntikkan token ke header request
- Tanpa interceptor, kita harus menambahkan header ini secara manual di setiap pemanggilan Axios

**Response Interceptor — Penangkap Error 401 Global:**

```javascript
axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response && error.response.status === 401) {
            alert('Sesi Anda telah berakhir atau Token tidak sah. Silakan login kembali.');
            localStorage.clear();
            window.location.href = '#/login';
            window.location.reload();
        }
        return Promise.reject(error);
    }
);
```

**Penjelasan:**
- Jika server mengembalikan status 401, interceptor ini otomatis menangkapnya
- localStorage dihapus dan user diarahkan kembali ke halaman login
- Ini memastikan tidak ada data sensitif yang tersisa di browser saat sesi berakhir

## Hasil Pengujian

### Test 1 — Postman Tanpa Token (Harus 401)

**Langkah:**
1. Buka Postman
2. Method: `POST`
3. URL: `http://localhost/lab11_ci/ci4/public/post`
4. Tab Body → x-www-form-urlencoded → isi judul dan isi
5. Klik Send tanpa menambahkan header Authorization

**Hasil yang diharapkan:** Response HTTP 401 dengan pesan "Akses Ditolak. Token tidak ditemukan pada request!"

> **Screenshot:**
> ![Postman 401](screenshots/p14_postman_401.png)

---

### Test 2 — Login dan Dapatkan Token

**Langkah:**
1. Buka `http://localhost/lab8_vuejs/`
2. Klik menu **Login**
3. Masukkan username dan password
4. Klik Masuk Aplikasi

**Hasil yang diharapkan:** Berhasil masuk ke halaman Kelola Artikel, menu berubah dari Login menjadi Logout.

> **Screenshot:**
> ![Form Login](screenshots/p14_form_login.png)

> **Screenshot:**
> ![Login Berhasil](screenshots/p14_login_berhasil.png)

---

### Test 3 — DevTools Network (Bukti Token Disuntik Otomatis)

**Langkah:**
1. Login di `http://localhost/lab8_vuejs/`
2. Buka DevTools → tab **Network**
3. Coba hapus salah satu artikel
4. Klik request yang muncul di panel Network
5. Lihat tab **Headers** → bagian **Request Headers**
6. Cari baris `Authorization: Bearer ...`

**Hasil yang diharapkan:** Header `Authorization: Bearer <token>` muncul secara otomatis tanpa ditulis manual di kode komponen.

> **Screenshot:**
> ![DevTools Authorization Header](screenshots/p14_devtools_auth.png)

---

### Test 4 — Logout

**Langkah:**
1. Klik link **Logout** di menu navigasi
2. Muncul konfirmasi "Apakah Anda yakin ingin keluar aplikasi?"
3. Klik OK

**Hasil yang diharapkan:** localStorage terhapus, kembali ke halaman Beranda, menu kembali menampilkan Login.

> **Screenshot:**
> ![Konfirmasi Logout](screenshots/p14_logout.png)

---

*Praktikum 14 — Pemrograman Web 2 © 2024 Universitas Pelita Bangsa*
