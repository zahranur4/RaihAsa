# 🚀 Panduan Menjalankan Tests & Meningkatkan Coverage

## 1️⃣ Jalankan Tests Dasar

```bash
# Jalankan semua tests
php artisan test

# Jalankan hanya Unit Tests
php artisan test tests/Unit

# Jalankan hanya Feature Tests
php artisan test tests/Feature

# Jalankan test file spesifik
php artisan test tests/Unit/UserTest.php
php artisan test tests/Feature/Admin/DonasiControllerTest.php
```

---

## 2️⃣ Lihat Code Coverage Report

```bash
# Jalankan tests dengan coverage HTML report
php artisan test --coverage-html=coverage-report

# Atau dengan clover XML
php artisan test --coverage-clover=coverage.xml

# Jalankan dan lihat percentage
php artisan test --coverage
```

Hasil coverage akan dibuat di folder `coverage-report/index.html` - buka di browser untuk lihat detail.

---

## 3️⃣ File Tests yang Sudah Dibuat

### ✅ Unit Tests (tests/Unit/)
- **UserTest.php** - Test User model attributes & relationships
- **DonasiBarangTest.php** - Test DonasiBarang model CRUD & attributes
- **WishlistTest.php** - Test Wishlist model status & urgensi
- **FoodRescueTest.php** - Test FoodRescue model operations
- **PantiProfileTest.php** - Test PantiProfile attributes
- **RelawanProfileTest.php** - Test RelawanProfile attributes

### ✅ Feature Tests (tests/Feature/)
- **Admin/DonasiControllerTest.php** - Test Admin Donasi Controller (index, create, store, edit, update, delete)

---

## 4️⃣ Strategi Meningkatkan Coverage Lebih Lanjut

### 🎯 Target Cepat (60-70% Coverage)

Buat tests untuk semua Admin Controllers:
```
tests/Feature/Admin/AdminControllerTest.php
tests/Feature/Admin/PantiAdminTest.php
tests/Feature/Admin/VolunteerAdminTest.php
```

### 🎯 Target Menengah (70-80% Coverage)

Expand feature tests untuk:
- WishlistController (index, show, store, update)
- FoodRescueController (semua methods)
- MyDonationsController (list, detail, update status)
- DonorProfileController

### 🎯 Target Tinggi (80%+ Coverage)

Test edge cases:
- Validation errors
- Authorization checks
- Error handling
- Business logic

---

## 5️⃣ Template Test untuk Controller Baru

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_access_index()
    {
        $response = $this->actingAs($this->user)->get(route('your.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_create_item()
    {
        $data = [/* form data */];
        $response = $this->actingAs($this->user)->post(route('your.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('table_name', $data);
    }
}
```

---

## 6️⃣ Tips Cepat Naikin Coverage

1. **Prioritas Model Tests Dulu** ✅
   - Paling mudah dibuat
   - Coverage naik cepat
   - Tidak perlu HTTP request

2. **Test Happy Path Dulu** ✅
   - Buat test untuk kasus normal
   - Nanti tambah edge cases

3. **Gunakan Factories** ✅
   ```php
   $user = User::factory()->create();
   $donation = DonasiBarang::factory()->create();
   ```

4. **Batch Operations** ✅
   - Jalankan tests sering
   - Lihat coverage naik step by step

---

## 7️⃣ Monitoring Progress

| Phase | Aksi | Target Coverage |
|-------|------|-----------------|
| **Sekarang** | ✅ Sudah dibuat | < 50% |
| **Next** | ✅ Jalankan Unit Tests | 55-60% |
| **Next** | ✅ Jalankan Feature Tests | 65-70% |
| **Next** | ⭕ Test Controllers Lain | 75-80% |
| **Final** | ⭕ Edge Cases & Validation | 85%+ |

---

## 📊 Command Checklist

```bash
# Jalankan untuk cek progress
php artisan test --coverage

# Lihat detail coverage
php artisan test --coverage-html=coverage-report
# Buka: coverage-report/index.html di browser

# Jalankan test spesifik untuk debug
php artisan test tests/Unit/UserTest.php -v

# Jalankan dengan output detail
php artisan test --verbose
```

**Good luck! Coverage naik terus! 🎉**
