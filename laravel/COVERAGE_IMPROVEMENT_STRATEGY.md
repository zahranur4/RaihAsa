# Strategi Peningkatan Code Coverage

## 📊 Status Saat Ini
- **Coverage Total**: < 50%
- **Sudah Ada**: 15 Feature Tests + 1 Unit Test
- **Yang Perlu**: Tests untuk Models, Controllers, dan logika bisnis

---

## 🎯 Prioritas Peningkatan Coverage

### Level 1: WAJIB (Akan naik signifikan)

#### 1. **Model Tests (Unit Tests)**
Buat `tests/Unit/` untuk setiap model:
- `UserTest.php` - Test relationships, accessors, mutators
- `DonasiBarangTest.php` - Test model relationships
- `FoodRescueTest.php` - Test model methods
- `WishlistTest.php` - Test model logic
- `PantiProfileTest.php` - Test relationships
- `RelawanProfileTest.php` - Test relationships

```php
// Contoh: tests/Unit/UserTest.php
public function test_user_has_relawan_profiles_relationship()
public function test_user_get_name_attribute_returns_nama()
public function test_user_has_correct_fillable_attributes()
public function test_user_casts_is_admin_to_boolean()
```

#### 2. **Controller Tests (Feature Tests)**
Test semua methods di controllers:

**Admin Controllers:**
- `tests/Feature/Admin/AdminControllerTest.php` - Test index, CRUD
- `tests/Feature/Admin/DonasiControllerTest.php` - Test create, store, update, delete
- Buat untuk Panti dan Donor juga

**Main Controllers:**
- Expand `WishlistTest.php` - Test add, remove, update
- Expand `MyDonationsTest.php` - Test list, detail, status update
- Test `FoodRescueController` - Semua functionality

#### 3. **Request/Validation Tests**
- Test Form Requests validation
- Test request authorization

---

## 🔧 Langkah-Langkah Implementasi

### Step 1: Jalankan Coverage Report untuk Melihat Detail
```bash
php artisan test --coverage
```

### Step 2: Identifikasi File dengan Coverage Terendah
Buka `coverage-report/index.html` dan lihat file mana yang belum tercakup.

### Step 3: Buat Tests untuk Coverage Terendah Dulu
Prioritas:
1. Models (coverage naik cepat)
2. Controllers (paling banyak code)
3. Service classes (jika ada)

---

## 📝 Template Tests untuk Dimulai

### A. Model Test Template
```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    // Test relationships
    // Test accessors/mutators
    // Test scopes
    // Test attributes
}
```

### B. Controller Test Template
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_returns_user_data()
    {
        $user = User::factory()->create();
        
        $response = $this->get(route('users.show', $user));
        
        $response->assertStatus(200);
        $response->assertViewHas('user', $user);
    }
}
```

---

## 🚀 Target Realistis

| Fase | Coverage | Actions |
|------|----------|---------|
| **Saat Ini** | < 50% | Baseline |
| **Fase 1** | 60% | Model + Basic Controller Tests |
| **Fase 2** | 75% | Edge cases + Error handling |
| **Fase 3** | 85%+ | Full feature coverage |

---

## 💡 Tips Cepat Naikkan Coverage

1. **Mulai dari Models** - Lebih mudah, coverage naik cepat
2. **Test Happy Path dulu** - Kemudian error cases
3. **Gunakan Factories** - Buat data test lebih cepat
4. **Test Relationships** - Models punya banyak relationships
5. **Test Validation** - Controllers punya validasi

---

## 📌 Tools Berguna

```bash
# Lihat coverage detail
php artisan test --coverage-clover=coverage.xml

# Hanya run unit tests
php artisan test tests/Unit --coverage

# Run dengan HTML report
php artisan test --coverage-html=coverage-report

# Run specific test file
php artisan test tests/Unit/UserTest.php
```

---

## ✅ Checklist Implementasi

- [ ] Buat tests/Unit/UserTest.php
- [ ] Buat tests/Unit/DonasiBarangTest.php
- [ ] Buat tests/Unit/FoodRescueTest.php
- [ ] Expand Admin Controller Tests
- [ ] Expand Feature Tests untuk semua controller methods
- [ ] Test Error Handling & Validation
- [ ] Achieve 70%+ coverage
- [ ] Achieve 80%+ coverage
