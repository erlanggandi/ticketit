# PRODUCT REQUIREMENTS DOCUMENT

**Nama Produk:** [TBD] — working title: *Internal IT Helpdesk Ticketing System*
**Versi Dokumen:** 0.1 (Draft — hasil Discovery, perlu review stakeholder)
**Tanggal:** 26 Agustus 2026
**Status:** Draft for Review
**Disiapkan oleh:** [TBD]

> Catatan pembacaan: Item yang ditandai `[TBD]` berarti informasi belum tersedia dan perlu dikonfirmasi. Item yang ditandai `[ASSUMPTION]` adalah asumsi wajar yang saya buat berdasarkan best practice, dan perlu dikonfirmasi/disesuaikan oleh tim sebelum development dimulai.

---

## 1. Product Overview

Sistem ticketing internal berbasis web untuk mengelola keluhan/masalah IT di dalam satu perusahaan (single-tenant, bukan produk SaaS multi-klien). Karyawan dapat melaporkan keluhan tanpa perlu login, sementara tim IT (Administrator) mengelola, menangani, dan melaporkan penyelesaian keluhan melalui satu dashboard terpusat dengan SLA yang terukur.

## 2. Executive Summary

Saat ini keluhan IT dilaporkan secara tidak terstruktur (chat, telepon), menyebabkan laporan sering hilang, karyawan tidak tahu progres, dan tim IT tidak punya data historis maupun target waktu respons yang terukur. Produk ini menyediakan satu sistem terpusat: form pelaporan publik tanpa login untuk karyawan, halaman cek status mandiri, serta dashboard admin dengan SLA otomatis dan pelaporan statistik untuk tim IT.

## 3. Problem Statement

| # | Masalah |
|---|---|
| P1 | Tidak ada tracking terpusat — keluhan tercecer di chat/telepon dan mudah hilang/terlewat |
| P2 | Karyawan tidak memiliki visibilitas terhadap status/progress keluhan yang mereka laporkan |
| P3 | Tidak ada SLA/target waktu respons yang terukur dari tim IT |
| P4 | Tidak ada data/riwayat historis untuk analisis (jenis keluhan yang sering terjadi, performa penanganan, dll) |

## 4. Business Goals

- BG-1: Mensentralisasi seluruh pencatatan dan pengelolaan keluhan IT ke dalam satu sistem
- BG-2: Meningkatkan transparansi status penanganan keluhan bagi karyawan
- BG-3: Menetapkan dan memantau SLA penyelesaian keluhan secara otomatis
- BG-4: Menyediakan data dan laporan untuk mendukung pengambilan keputusan tim IT/manajemen

## 5. Objectives

- Seluruh pelaporan keluhan IT dilakukan melalui sistem ini, bukan lagi via chat/telepon manual [TBD - Success Metric: target %]
- Karyawan dapat memeriksa status tiket kapan saja secara mandiri tanpa menghubungi IT langsung
- Setiap tiket memiliki SLA due date otomatis berdasarkan prioritas, dengan alert saat mendekati/lewat batas waktu
- Tersedia dashboard laporan dengan data historis untuk tim IT/manajemen

## 6. Target Users & Personas

### Persona 1 — Karyawan (Requester)
- **Tujuan:** Melaporkan masalah IT dengan cepat, mengetahui status penanganannya
- **Kebutuhan:** Form sederhana tanpa login, cara mudah mengecek status
- **Pain point saat ini:** Laporan sering tidak direspons/hilang, tidak tahu progres
- **Permission:** Create ticket (publik), lihat status tiket via nomor tiket (tanpa login)

### Persona 2 — Administrator (Tim IT)
- **Tujuan:** Mengelola dan menyelesaikan tiket secara efisien dan terukur
- **Kebutuhan:** Dashboard terpusat, notifikasi real-time, data untuk laporan ke manajemen
- **Pain point saat ini:** Tracking manual, tidak ada data historis, respons tidak terukur
- **Permission:** Full akses — kelola semua tiket, master data (Kategori, Employee, SLA), dan dashboard laporan

> `[TBD]` Apakah akan ada lebih dari satu akun Administrator (butuh fitur assign antar-admin), atau cukup satu akun untuk saat ini?

## 7. User Journey

**Karyawan — Membuat Tiket**
```
Karyawan mengalami masalah IT
   → Buka halaman Create Ticket (publik, tanpa login)
   → Isi Nama (manual / autocomplete dari Employee Master)
   → Pilih Kategori
   → Pilih Prioritas
   → Isi Deskripsi
   → Submit
   → [Invalid] kembali ke form + pesan error
   → [Valid] Sistem generate No. Tiket + set status "Open" + hitung SLA due date
   → Notifikasi terkirim ke Administrator (Telegram + web)
   → Karyawan menerima No. Tiket di layar
```

**Karyawan — Cek Status**
```
Buka halaman Cek Status Tiket
   → Input No. Tiket
   → [Ditemukan] tampil status, kategori, prioritas, tanggal dibuat
   → [Tidak ditemukan] tampil pesan "Tiket tidak ditemukan"
```

**Administrator — Menangani Tiket**
```
Terima notifikasi tiket baru (Telegram/web)
   → Login
   → Buka Dashboard, lihat daftar tiket
   → Buka detail tiket
   → Update status: Open → In Progress → Resolved → Closed
   → [SLA mendekati/lewat due date] Sistem kirim alert ke Admin
```

## 8. Scope

### In Scope (MVP — P0)
- Create Ticket publik (tanpa login), dengan autocomplete nama dari Employee Master
- Kategori & Prioritas dipilih manual oleh karyawan
- Generate nomor tiket otomatis
- Perhitungan SLA otomatis berdasarkan prioritas + alert mendekati/lewat deadline
- Halaman Cek Status Tiket (publik, tanpa login)
- Login & Dashboard Administrator
- Update status tiket (Open → In Progress → Resolved → Closed)
- Kelola master data: Kategori, Employee, SLA Setting
- Notifikasi Telegram + web ke Administrator
- Pengaturan Alert Telegram (Bot Token, Chat ID, Template Pesan, Test Kirim Pesan) — dikelola Admin, tersimpan di database
- Dashboard laporan/statistik dasar
- Audit log dasar (perubahan status, aktivitas Admin)

### Out of Scope (sengaja tidak dibuat di versi ini)
- Komentar/reply thread pada tiket
- Lampiran file
- Role tambahan (Teknisi, Approver/Manager) — saat ini hanya 1 role Administrator
- Approval workflow
- Notifikasi otomatis ke karyawan (karyawan hanya cek status secara manual)
- Rating/feedback kepuasan setelah tiket selesai
- Integrasi SSO/LDAP/Active Directory maupun Email

### Future Scope
- P1: Komentar/reply thread & lampiran file pada tiket
- P1: Notifikasi otomatis ke karyawan (email/Telegram) saat status berubah
- P1: Export laporan ke PDF/Excel
- P2: Role tambahan (Teknisi, Approver) + approval workflow untuk prioritas tertentu
- P2: Rating kepuasan pelanggan setelah tiket Closed
- P2: Integrasi SSO/Active Directory

> Catatan: menambahkan item Future Scope ke MVP akan menambah waktu development dan kompleksitas (mis. role tambahan akan mengubah seluruh Permission Matrix dan Workflow approval) — perlu didiskusikan dampaknya sebelum dipindah ke MVP.

## 9. Feature Overview

| Feature | Deskripsi | Priority |
|---|---|---|
| Create Ticket (Publik) | Karyawan membuat tiket tanpa login | P0 |
| Employee Autocomplete | Saran nama dari Employee Master saat isi form | P0 |
| Cek Status Tiket (Publik) | Karyawan cek status via nomor tiket | P0 |
| Admin Login | Autentikasi Administrator | P0 |
| Ticket Management Dashboard | Lihat, filter, update status tiket | P0 |
| SLA Engine | Hitung due date otomatis + alert | P0 |
| Kelola Kategori (Master Data) | CRUD kategori keluhan | P0 |
| Kelola Employee (Master Data) | CRUD data karyawan terdaftar | P0 |
| Kelola SLA Setting | Konfigurasi durasi SLA per prioritas | P0 |
| Notifikasi Telegram + Web | Notifikasi tiket baru & alert SLA | P0 |
| Pengaturan Alert Telegram | Admin atur Bot Token, Chat ID, template pesan, dan test kirim pesan (tersimpan di database) | P0 |
| Reporting Dashboard | Statistik tiket, SLA compliance, tren | P0 |
| Audit Log | Catatan perubahan status & aktivitas admin | P0 |
| Komentar & Lampiran File | Diskusi & attachment per tiket | P1 (Future) |

## 10. Functional Requirements

**Create Ticket**
- FR-001: System shall allow Karyawan to create a new ticket without requiring login/authentication.
- FR-002: System shall suggest matching names (autocomplete) from Employee Master Data as Karyawan types their name.
- FR-003: System shall allow ticket creation to proceed with manual name entry even if no match is found in Employee Master Data.
- FR-004: System shall require Karyawan to select a Category from a predefined, admin-managed list.
- FR-005: System shall require Karyawan to select a Priority (Low/Medium/High/Critical) manually.
- FR-006: System shall require Karyawan to input a description of the complaint.
- FR-007: System shall generate a unique, human-readable ticket number automatically upon submission.
- FR-008: System shall set the initial status of a new ticket to "Open".
- FR-009: System shall calculate an SLA due date automatically based on the selected Priority at ticket creation.
- FR-010: System shall send a notification (Telegram + web) to Administrator when a new ticket is created.
- FR-022: System shall prevent duplicate ticket submission from a single form action (e.g., disable submit button after click / idempotency check).
- FR-023: System shall validate all required fields on the Create Ticket form on both client-side and server-side.

**Cek Status Tiket**
- FR-011: System shall provide a public page where Karyawan can check ticket status by entering the ticket number, without login.
- FR-012: System shall display a "ticket not found" message when an invalid/nonexistent ticket number is entered.

**Admin & Ticket Management**
- FR-013: System shall require Administrator to log in (email + password) before accessing the Admin Dashboard.
- FR-014: System shall allow Administrator to view all tickets with filter/search by status, category, priority, and date range.
- FR-015: System shall allow Administrator to update ticket status following the defined workflow (Open → In Progress → Resolved → Closed).
- FR-016: System shall prevent any edit to a ticket once its status is "Closed" (see BR-001).

**Master Data**
- FR-017: System shall allow Administrator to create, edit, and deactivate Category records.
- FR-018: System shall allow Administrator to create, edit, and deactivate Employee master records.
- FR-019: System shall allow Administrator to configure SLA duration per Priority level.

**Notification & SLA**
- FR-020: System shall send an alert (Telegram + web) to Administrator when a ticket's SLA is approaching or has passed its due time [threshold TBD].

**Pengaturan Alert Telegram**
- FR-025: System shall allow Administrator to input and save Telegram Bot Token and Chat/Group ID via a settings page.
- FR-026: System shall allow Administrator to configure the Telegram notification message template(s), supporting placeholder variables (e.g., ticket number, category, priority, requester name, status).
- FR-027: System shall allow Administrator to send a test message using the currently saved Bot Token, Chat ID, and template, to verify the connection works.
- FR-028: System shall persist all Telegram settings (Bot Token, Chat ID, message template) in the database, not in static configuration files.
- FR-029: System shall mask the Bot Token value in the UI after it has been saved (e.g., show only partial characters) to reduce exposure of sensitive credentials.

**Reporting**
- FR-021: System shall provide a dashboard displaying ticket count by status, average resolution time, ticket count by category, and SLA compliance rate.

**Logging**
- FR-024: System shall log all ticket status changes and Administrator actions for audit purposes.

## 11. User Stories

- US-01: As a Karyawan, I want to create an IT complaint ticket without logging in, so that I can report an issue quickly.
- US-02: As a Karyawan, I want my name auto-suggested if I'm already registered, so that I don't type all my details manually.
- US-03: As a Karyawan, I want to select the category and priority myself, so that I can describe how urgent my issue is.
- US-04: As a Karyawan, I want to receive a unique ticket number after submitting, so that I can check its status later.
- US-05: As a Karyawan, I want to check my ticket's status using the ticket number, so that I know its progress without contacting IT directly.
- US-06: As an Administrator, I want to log in securely, so that only authorized IT staff can manage tickets.
- US-07: As an Administrator, I want to see all tickets with filters, so that I can prioritize my work.
- US-08: As an Administrator, I want to update ticket status as I work on it, so that progress is visible and accurate.
- US-09: As an Administrator, I want to get notified via Telegram/web for new tickets and SLA alerts, so that I don't miss urgent issues.
- US-10: As an Administrator, I want to manage Category and Employee master data, so that the ticket form stays accurate.
- US-11: As an Administrator, I want a reporting dashboard, so that I can analyze ticket trends and SLA performance.

## 12. Acceptance Criteria

**Create Ticket**
```
Given Karyawan berada di halaman Create Ticket
When Karyawan mengisi nama, memilih kategori, memilih prioritas, mengisi deskripsi, dan menekan Submit
Then System membuat tiket baru dengan status "Open"
And System memberikan nomor tiket unik
And System menghitung SLA due date berdasarkan prioritas yang dipilih
And System mengirim notifikasi ke Administrator via Telegram dan web
```

**Autocomplete Nama**
```
Given Karyawan mengetik nama pada field Nama
When nama yang diketik cocok dengan data di Employee Master
Then System menampilkan saran nama yang sesuai untuk dipilih

Given nama yang diketik tidak ditemukan di Employee Master
When Karyawan melanjutkan mengisi form secara manual
Then System tetap menerima input tersebut sebagai tiket yang valid
```

**Cek Status Tiket**
```
Given Karyawan berada di halaman Cek Status Tiket
When Karyawan memasukkan nomor tiket yang valid
Then System menampilkan status, kategori, prioritas, dan tanggal dibuat tiket

Given nomor tiket yang dimasukkan tidak valid
When Karyawan menekan tombol Cari
Then System menampilkan pesan "Tiket tidak ditemukan"
```

**Update Status oleh Admin**
```
Given Administrator login dan membuka tiket berstatus "Open"
When Administrator mengubah status menjadi "In Progress"
Then System menyimpan perubahan dan mencatat waktu perubahan pada Audit Log

Given tiket berstatus "Closed"
When Administrator mencoba mengubah status atau isi tiket
Then System menolak perubahan tersebut
```

**SLA Alert**
```
Given tiket memiliki SLA due date yang telah dihitung
When waktu saat ini mendekati atau melewati SLA due date [threshold TBD]
Then System mengirimkan notifikasi alert ke Administrator via Telegram dan web
```

**Pengaturan Alert Telegram**
```
Given Administrator berada di halaman Pengaturan Alert Telegram
When Administrator mengisi Bot Token, Chat ID, dan template pesan, lalu menekan Simpan
Then System menyimpan seluruh pengaturan ke database
And System menampilkan konfirmasi berhasil disimpan

Given Bot Token dan Chat ID sudah tersimpan
When Administrator menekan tombol "Test Kirim Pesan"
Then System mengirim pesan percobaan ke Chat ID yang dikonfigurasi menggunakan template yang aktif
And System menampilkan status berhasil atau gagal pengiriman

Given Bot Token atau Chat ID kosong atau tidak valid
When Administrator menekan Simpan atau Test Kirim Pesan
Then System menampilkan pesan error yang jelas dan tidak melanjutkan proses
```

## 13. Business Rules

- BR-001: Tiket berstatus "Closed" tidak dapat diedit atau diubah statusnya kembali. Karyawan yang ingin melapor ulang harus membuat tiket baru.
- BR-002: Kategori dan Prioritas ditentukan secara **manual oleh Karyawan** saat submit tiket; sistem tidak melakukan override otomatis. *(Catatan: ini menggantikan opsi awal "prioritas otomatis berdasarkan kategori" yang sempat dibahas di sesi discovery — versi final mengikuti keputusan ini.)*
- BR-003: Setiap tiket wajib memiliki nomor tiket unik yang digunakan sebagai referensi pengecekan status tanpa login.
- BR-004: SLA due date dihitung otomatis saat tiket berstatus "Open", berdasarkan durasi yang dikonfigurasi Admin untuk masing-masing level prioritas. `[TBD - Business Rule: nilai durasi default per prioritas]`
- BR-005: Jika nama yang diinput Karyawan cocok dengan Employee Master, sistem menampilkannya sebagai saran; jika tidak ditemukan, Karyawan tetap dapat melanjutkan secara manual. `[TBD - Business Rule: apakah entry manual yang tidak match disimpan sebagai data karyawan baru, atau hanya dipakai sekali untuk tiket tersebut]`
- BR-006: Hanya Administrator yang dapat mengubah status tiket, mengelola master data (Kategori, Employee, SLA Setting), dan mengakses dashboard laporan.
- BR-007: Pengaturan Telegram (Bot Token, Chat ID, Template Pesan) hanya dapat diakses dan diubah oleh Administrator.
- BR-008: Seluruh proses pengiriman notifikasi Telegram wajib menggunakan Bot Token, Chat ID, dan template yang tersimpan di database — bukan nilai hardcoded di file konfigurasi.
- `[TBD - Business Rule]`: Apakah transisi status Resolved → Closed dilakukan manual oleh Admin, atau otomatis setelah periode tertentu tanpa keberatan dari karyawan?

## 14. Role & Permission Matrix

| Aksi | Karyawan (Publik) | Administrator |
|---|---|---|
| Login ke sistem | Tidak perlu | ✓ (wajib) |
| Create Ticket | ✓ | `[TBD]` |
| Lihat status tiket (via No. Tiket) | ✓ (tiket manapun jika tahu nomornya) | ✓ (semua tiket) |
| Lihat daftar semua tiket | ✗ | ✓ |
| Update status tiket | ✗ | ✓ |
| Kelola Kategori | ✗ | ✓ |
| Kelola Employee Master Data | ✗ | ✓ |
| Kelola SLA Setting | ✗ | ✓ |
| Kelola Pengaturan Alert Telegram | ✗ | ✓ |
| Lihat Dashboard Laporan | ✗ | ✓ |

> Saat ini hanya ada satu role di sisi IT (Administrator, menangani semua). Jika di masa depan ditambahkan role Teknisi/Approver (lihat Future Scope), matrix ini perlu direvisi.

## 15. Workflow

**Status & Transition**

| From | To | Who | Kondisi |
|---|---|---|---|
| (none) | Open | System | Saat tiket berhasil dibuat |
| Open | In Progress | Administrator | Admin mulai menangani tiket |
| In Progress | Resolved | Administrator | Pekerjaan selesai dikerjakan |
| Resolved | Closed | Administrator | `[TBD]` manual atau otomatis |
| Closed | (status apapun) | — | Tidak diperbolehkan (BR-001) |

Diagram alur karyawan dan admin dijabarkan pada Bagian 7 (User Journey).

## 16. Data Requirements

**Entity: Ticket**

| Field | Type | Required | Validation/Default |
|---|---|---|---|
| ticket_id | UUID/int (PK) | Auto | — |
| ticket_number | string | Auto | Unique, human-readable (mis. `TCK-2026-00001`) |
| requester_name | string | Required | Manual atau dari autocomplete |
| requester_id | FK → Employee | Optional | Null jika tidak match Employee Master |
| category_id | FK → Category | Required | Dipilih karyawan |
| priority | enum(Low/Medium/High/Critical) | Required | Dipilih karyawan |
| description | text | Required | Min. karakter `[TBD]` |
| status | enum(Open/In Progress/Resolved/Closed) | Auto | Default: Open |
| sla_due_at | datetime | Auto | Dihitung dari priority + created_at |
| created_at | datetime | Auto | — |
| updated_at | datetime | Auto | — |
| resolved_at | datetime | Optional | Diisi saat status → Resolved |
| closed_at | datetime | Optional | Diisi saat status → Closed |

**Entity: Category** (master data)
| Field | Type | Keterangan |
|---|---|---|
| category_id | int (PK) | — |
| category_name | string | Contoh: Hardware, Software, Jaringan, Akses/Akun `[ASSUMPTION - daftar awal, perlu konfirmasi]` |
| is_active | boolean | Untuk menonaktifkan tanpa hapus data |

**Entity: Employee** (master data untuk autocomplete)
| Field | Type | Keterangan |
|---|---|---|
| employee_id | int (PK) | — |
| employee_name | string | — |
| department | string | `[TBD]` required/optional |
| contact | string | `[TBD]` email/no. telp, required/optional |
| is_active | boolean | — |

**Entity: Administrator (User)**
| Field | Type | Keterangan |
|---|---|---|
| user_id | int (PK) | — |
| name | string | — |
| email | string | Unique, untuk login |
| password | string (hashed) | bcrypt `[ASSUMPTION]` |
| is_active | boolean | — |

**Entity: SLA Setting** (master data)
| Field | Type | Keterangan |
|---|---|---|
| priority | enum | Low/Medium/High/Critical |
| duration_hours | int | `[TBD]`, diusulkan default di Bagian 24 (Assumptions) |

**Entity: TelegramSetting** (master data, dikelola Admin, tersimpan di database)
| Field | Type | Keterangan |
|---|---|---|
| id | int (PK) | — |
| bot_token | string (encrypted) | Disimpan terenkripsi di database `[ASSUMPTION - security]` |
| chat_id | string | Chat ID atau Group ID tujuan notifikasi |
| message_template_new_ticket | text | Template pesan untuk event tiket baru, mendukung placeholder `[ASSUMPTION - lihat Open Question soal 1 vs banyak template]` |
| message_template_sla_alert | text | Template pesan untuk event SLA mendekati/lewat due date |
| updated_at | datetime | — |
| updated_by | FK → Administrator | Mencatat siapa terakhir mengubah setting |

## 17. Notification Requirements

| Event | Channel | Penerima | Catatan |
|---|---|---|---|
| Tiket baru dibuat | Telegram + Web | Administrator | — |
| SLA mendekati due date | Telegram + Web | Administrator | Threshold `[TBD]`, ASSUMPTION: H-1 jam sebelum due |
| SLA lewat due date (breach) | Telegram + Web | Administrator | — |

`[TBD]` Apakah notifikasi Telegram dikirim ke 1 grup/channel IT bersama, atau ke masing-masing akun Admin secara individual?

Bot Token, Chat ID, dan isi pesan **tidak lagi hardcoded** — seluruhnya diatur Admin melalui menu Pengaturan Alert Telegram (lihat FR-025 s/d FR-029, dan entity `TelegramSetting` di Bagian 16) dan tersimpan di database.

Placeholder variable yang didukung pada template pesan `[ASSUMPTION - daftar awal, perlu konfirmasi]`:
`{ticket_number}`, `{category}`, `{priority}`, `{requester_name}`, `{status}`, `{sla_due_at}`, `{created_at}`

## 18. Error Handling & Edge Cases

| Kasus | Expected Behavior |
|---|---|
| Double submit (klik Submit 2x) | Tombol dinonaktifkan setelah diklik; sistem mencegah duplikasi tiket |
| Internet terputus saat submit | Tampilkan pesan error, data belum tersimpan, minta submit ulang |
| Field wajib kosong | Validasi client & server-side, tampilkan pesan error per field |
| Nama tidak ditemukan di Employee Master | Tetap bisa lanjut mengisi manual (lihat BR-005) |
| Session Admin expired | Redirect ke halaman login dengan pesan sesi berakhir |
| Akses halaman Admin tanpa login | Redirect ke halaman login |
| Nomor tiket tidak ditemukan saat cek status | Tampilkan pesan "Tiket tidak ditemukan" |
| Tiket sudah Closed dicoba diubah | Sistem menolak perubahan (BR-001) |
| Update tiket bersamaan oleh 2 sesi Admin (concurrent update) | `[TBD]` — ASSUMPTION sementara: last-write-wins, ditampilkan warning jika data berubah sejak dibuka |
| Notifikasi Telegram gagal terkirim (API timeout) | Tiket tetap tersimpan; kegagalan notifikasi dicatat di log, tidak menggagalkan proses submit |
| Database unavailable | Tampilkan halaman error umum, tidak menampilkan detail teknis/stack trace |

## 19. Non-Functional Requirements

- **Performance:** Halaman Create Ticket & Cek Status Tiket dimuat < 2 detik pada kondisi jaringan normal `[ASSUMPTION]`
- **Availability:** `[TBD]` — belum ada target uptime spesifik
- **Scalability:** `[TBD]` — volume pengguna/tiket harian belum diketahui
- **Usability:** Form publik harus dapat digunakan tanpa training/panduan
- **Accessibility:** `[TBD]`
- **Browser Compatibility:** Browser modern versi terbaru (Chrome, Firefox, Edge) `[ASSUMPTION]`
- **Responsive/Mobile:** UI responsif (Tailwind CSS) agar dapat diakses dari browser mobile `[ASSUMPTION]`
- **Logging:** Log aktivitas penting (perubahan status, aksi admin)
- **Monitoring:** `[TBD]`
- **Backup:** Backup rutin database PostgreSQL `[TBD - frekuensi]`

## 20. Security Requirements

- **Authentication:** Administrator login dengan email + password (hashed, bcrypt) `[ASSUMPTION - default Laravel auth]`
- **Authorization/RBAC:** Saat ini 1 role (Administrator); permission belum granular
- **Session Management:** Session-based (default Laravel), timeout `[TBD]`
- **Password Security:** Minimal policy (mis. 8 karakter) `[ASSUMPTION]`
- **Input Validation:** Semua input form divalidasi di client & server-side
- **Rate Limiting / Anti-Spam:** Form publik (Create Ticket & Cek Status) berpotensi disalahgunakan bot/spam karena tanpa login → disarankan rate limiting dan/atau captcha `[ASSUMPTION, lihat Risiko #26]`
- **Audit Log:** Pencatatan aktivitas Administrator dan perubahan status tiket
- **Sensitive Data Protection:** Data karyawan (nama, kontak) tidak diekspos ke endpoint publik selain data tiket yang relevan; Bot Token Telegram disimpan terenkripsi di database dan tidak ditampilkan penuh/plain text di UI setelah disimpan (lihat FR-029)
- **XSS/CSRF/SQL Injection:** Proteksi standar Laravel (CSRF token, Eloquent ORM, output escaping)

## 21. Integration Requirements

- **Telegram Bot API** — untuk notifikasi ke Administrator. Bot Token & Chat/Group ID dibuat terlebih dahulu di platform Telegram (via BotFather) oleh tim IT, kemudian diinput dan dikelola Admin melalui menu Pengaturan Alert Telegram di sistem (bukan disimpan statis di file konfigurasi/`.env`)
- Tidak ada integrasi SSO/LDAP/Active Directory (karyawan tidak login; Admin pakai auth internal)
- Tidak ada integrasi Email/SMTP pada MVP ini

## 22. Audit & Logging

- Setiap perubahan status tiket dicatat: siapa, kapan, dari status apa ke status apa
- Setiap aksi CRUD Admin terhadap master data (Kategori, Employee, SLA Setting) dicatat
- Login/logout Administrator dicatat `[ASSUMPTION]`
- Retention period log: `[TBD]`

## 23. Success Metrics

- Seluruh keluhan IT dilaporkan melalui sistem, bukan lagi via chat/telepon manual — target `[TBD - Success Metric]`
- Karyawan dapat mengecek status tiket kapan saja tanpa menghubungi IT secara langsung (tercapai secara fungsional begitu fitur live)
- Persentase tiket selesai sesuai SLA — target `[TBD - Success Metric]`
- Dashboard laporan digunakan tim IT/manajemen secara rutin — frekuensi target `[TBD - Success Metric]`

## 24. Assumptions

Daftar konsolidasi seluruh `[ASSUMPTION]` dalam dokumen ini:
- Kategori awal: Hardware, Software, Jaringan, Akses/Akun, Lainnya (dapat diubah Admin)
- Default durasi SLA (usulan, perlu konfirmasi): Critical = 4 jam, High = 1 hari kerja, Medium = 3 hari kerja, Low = 5 hari kerja
- Login Administrator menggunakan email + password standar (tanpa 2FA di MVP)
- Alert SLA "mendekati due date" dikirim H-1 jam sebelum due
- Concurrent update ticket ditangani dengan pola last-write-wins
- Browser modern terbaru & tampilan responsif untuk akses mobile
- Backend auth memakai mekanisme default Laravel (bcrypt hash, session-based)

## 25. Dependencies

- Tim IT perlu membuat Bot Telegram (via BotFather) dan menyiapkan Chat/Group ID tujuan sebelum dapat dikonfigurasi melalui menu Pengaturan Alert Telegram
- Infrastruktur server untuk deployment Docker (VPS/on-premise) `[TBD]`
- Data awal Employee Master perlu disiapkan/diimpor sebelum go-live `[TBD - siapa yang menyediakan]`

## 26. Risks & Mitigation

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Karyawan dapat memilih Prioritas sembarangan (mis. selalu pilih Critical agar cepat ditangani) karena tidak ada validasi/approval | SLA jadi tidak akurat, tim IT kewalahan menangani "Critical" palsu | Disarankan Admin dapat **override/ubah prioritas** setelah review awal `[ASSUMPTION, perlu dikonfirmasi apakah fitur ini termasuk MVP]` |
| Form publik tanpa login rentan disalahgunakan (spam/bot) | Data tiket palsu membebani sistem & tim IT | Tambahkan rate limiting dan/atau captcha pada form publik |
| Tailwind CSS via CDN tidak mendukung purge/optimasi build production | Ukuran halaman lebih besar dari optimal, styling custom terbatas | Pertimbangkan migrasi ke Tailwind build process (npm) pasca-MVP jika performa jadi isu |
| Employee Master belum terisi lengkap saat go-live | Fitur autocomplete tidak maksimal di awal | Import data karyawan sebelum go-live, atau terima entry manual sebagai fallback (sudah didukung sistem) |

## 27. Open Questions

- Nama resmi produk?
- Berapa durasi SLA per level prioritas (jika berbeda dari asumsi di Bagian 24)?
- Berapa threshold waktu untuk alert "SLA mendekati due date"?
- Target metrik keberhasilan (% laporan via sistem, % SLA compliance)?
- Apakah transisi Resolved → Closed dilakukan manual oleh Admin atau otomatis?
- Apakah Admin dapat mengubah/override Prioritas & Kategori yang dipilih karyawan?
- Apakah akan ada lebih dari 1 akun Administrator (perlu fitur assign)?
- Field Employee Master apa saja yang wajib (department, kontak)?
- Spesifikasi infrastruktur deployment (VPS, domain, SSL)?
- Apakah dibutuhkan rate limiting/captcha di form publik?
- Apakah template pesan Telegram berbeda untuk tiap jenis event (tiket baru vs SLA alert), atau cukup satu template umum untuk semua?
- Apakah Bot Token wajib dienkripsi di database, atau cukup disimpan sebagai teks biasa dengan proteksi akses database saja?

## 28. Release Plan

- **Phase 1 (MVP):** Seluruh fitur In Scope pada Bagian 8 — Create Ticket, Cek Status, Admin Dashboard, Master Data (Kategori/Employee/SLA), Notifikasi Telegram+Web, Reporting dashboard dasar, Audit log
- **Phase 2 (Future):** Komentar & lampiran file, notifikasi otomatis ke karyawan, export laporan
- **Phase 3 (Future):** Role tambahan (Teknisi/Approver) + approval workflow, rating kepuasan, integrasi SSO
- Timeline aktual: `[TBD]`

## 29. QA Checklist

- [ ] Karyawan dapat membuat tiket tanpa login, dengan validasi field wajib berjalan
- [ ] Autocomplete nama berfungsi untuk nama yang terdaftar, dan tetap menerima input manual untuk yang tidak terdaftar
- [ ] Nomor tiket ter-generate unik setiap submission
- [ ] SLA due date terhitung sesuai prioritas yang dipilih
- [ ] Notifikasi Telegram & web terkirim saat tiket baru dan saat SLA alert
- [ ] Halaman Cek Status menampilkan data yang benar untuk nomor tiket valid, dan pesan error untuk nomor tidak valid
- [ ] Login Admin berhasil/gagal sesuai kredensial, session expired ter-handle
- [ ] Transisi status tiket mengikuti alur yang ditentukan (Open→In Progress→Resolved→Closed)
- [ ] Tiket Closed tidak dapat diedit/diubah
- [ ] CRUD Kategori, Employee, SLA Setting berfungsi sesuai permission Admin
- [ ] Dashboard laporan menampilkan statistik yang akurat (jumlah per status, rata-rata waktu selesai, SLA compliance)
- [ ] Double submit tidak menghasilkan tiket duplikat
- [ ] Audit log mencatat perubahan status & aktivitas admin dengan benar
- [ ] Admin dapat menyimpan Bot Token, Chat ID, dan template pesan Telegram, dan seluruhnya tersimpan di database
- [ ] Tombol Test Kirim Pesan berhasil mengirim pesan ke Chat ID yang dikonfigurasi, dan menampilkan status berhasil/gagal
- [ ] Notifikasi yang benar-benar terkirim menggunakan template pesan yang dikonfigurasi Admin (bukan hardcoded)
- [ ] Bot Token tidak ditampilkan penuh/plain text di UI setelah disimpan
- [ ] Uji keamanan dasar: CSRF, XSS, SQL Injection, rate limiting pada endpoint publik

## 30. Definition of Done

- Seluruh Functional Requirement (FR-001 s/d FR-024) terimplementasi dan lulus UAT
- Seluruh Acceptance Criteria pada Bagian 12 terpenuhi
- Tidak ada bug Critical/Major yang terbuka
- Validasi keamanan dasar (auth, input validation, CSRF/XSS/SQL Injection protection) selesai
- Dokumentasi teknis dasar (setup, deployment Docker, environment variable) tersedia
- Deployment berhasil dilakukan di environment staging melalui Docker, siap untuk UAT stakeholder
