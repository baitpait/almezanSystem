# 👤 إنشاء/تحديث مستخدم Admin
## Create/Update Admin User

---

## إذا كان المستخدم موجوداً بالفعل:

```bash
php artisan tinker
```

**في Tinker:**
```php
// البحث عن المستخدم الموجود
$user = App\Models\User::where('email', 'admin@gmail.com')->first();

// إذا كان موجوداً، تحديثه
if ($user) {
    $user->update([
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'is_active' => true,
    ]);
    
    // إعطاء role 'admin' من Spatie
    if (!$user->hasRole('admin')) {
        $user->assignRole('admin');
    }
    
    echo "User updated successfully!";
} else {
    // إنشاء مستخدم جديد
    $user = App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'is_active' => true,
    ]);
    $user->assignRole('admin');
    echo "User created successfully!";
}

exit
```

---

## إذا أردت إنشاء مستخدم جديد ببريد مختلف:

```php
$user = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin2@gmail.com',
    'password' => Hash::make('admin123'),
    'role' => 'admin',
    'is_active' => true,
]);
$user->assignRole('admin');
exit
```

---

## التحقق من المستخدم:

```php
$user = App\Models\User::where('email', 'admin@gmail.com')->first();
echo "Name: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";
echo "Role: " . $user->role . "\n";
echo "Has admin role: " . ($user->hasRole('admin') ? 'Yes' : 'No') . "\n";
exit
```
