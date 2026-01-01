# أفضل طريقة لتشغيل Laravel على macOS (أفضل أداء)

## الخطوة 1: إكمال إعداد Homebrew

نفذ هذه الأوامر في Terminal:

```bash
# إضافة Homebrew إلى PATH
echo >> /Users/baitpait/.zprofile
echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> /Users/baitpait/.zprofile
eval "$(/opt/homebrew/bin/brew shellenv)"

# التحقق من التثبيت
brew --version
```

---

## أفضل طريقة لتشغيل Laravel على macOS

### الخيار 1: Laravel Serve (الأفضل للتطوير) ⭐⭐⭐⭐⭐

**المميزات:**
- ✅ سريع جداً
- ✅ لا يحتاج إعداد
- ✅ مثالي للتطوير
- ✅ يعمل مباشرة

**الاستخدام:**
```bash
cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"
php artisan serve
```

**الوصول:** http://localhost:8000

**لإيقاف:** اضغط `Control + C`

---

### الخيار 2: Laravel Valet (الأفضل للإنتاج والتطوير) ⭐⭐⭐⭐⭐

**المميزات:**
- ✅ أسرع من Laravel Serve
- ✅ يعمل على `.test` domain (مثل `project.test`)
- ✅ SSL تلقائي
- ✅ مثالي للمشاريع المتعددة
- ✅ أفضل أداء

**التثبيت:**
```bash
# تثبيت Valet
composer global require laravel/valet

# إعداد Valet
valet install

# الانتقال لمجلد المشاريع
cd ~/Projects  # أو أي مجلد تريده

# ربط المشروع
valet link dr-system

# أو من داخل مجلد المشروع
cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"
valet link
```

**الوصول:** http://dr-system.test

**الأوامر المفيدة:**
```bash
valet start      # بدء Valet
valet stop       # إيقاف Valet
valet restart    # إعادة تشغيل
valet links      # عرض جميع الروابط
valet unlink     # إلغاء الربط
```

---

### الخيار 3: Apache (للمشاريع الكبيرة) ⭐⭐⭐⭐

**المميزات:**
- ✅ قوي جداً
- ✅ مناسب للإنتاج
- ✅ يدعم Virtual Hosts

**التثبيت:**
```bash
brew install httpd
```

**الإعداد:** معقد نسبياً

---

### الخيار 4: Nginx (أسرع خادم) ⭐⭐⭐⭐⭐

**المميزات:**
- ✅ أسرع من Apache
- ✅ أقل استهلاكاً للذاكرة
- ✅ مناسب للإنتاج

**التثبيت:**
```bash
brew install nginx
```

**الإعداد:** معقد نسبياً

---

## التوصية: Laravel Valet ⭐⭐⭐⭐⭐

### لماذا Valet هو الأفضل:

1. **أسرع أداء** - محسّن خصيصاً لـ Laravel
2. **سهل الإعداد** - أمر واحد فقط
3. **SSL تلقائي** - https://project.test
4. **عدة مشاريع** - كل مشروع على domain منفصل
5. **لا يحتاج إعداد** - يعمل مباشرة
6. **أفضل للمطورين** - المعيار في macOS

---

## خطوات التثبيت الكاملة (Valet)

### 1. تثبيت PHP و Composer

```bash
# تثبيت PHP
brew install php@8.2
brew link php@8.2

# تثبيت Composer
brew install composer
```

### 2. تثبيت Valet

```bash
# تثبيت Valet
composer global require laravel/valet

# إضافة Composer global bin إلى PATH
echo 'export PATH="$HOME/.composer/vendor/bin:$PATH"' >> ~/.zprofile
source ~/.zprofile

# إعداد Valet
valet install
```

### 3. ربط المشروع

```bash
# الانتقال لمجلد المشروع
cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"

# ربط المشروع
valet link dr-system
```

### 4. الوصول للمشروع

افتح المتصفح على: **http://dr-system.test**

---

## تحسين الأداء

### 1. استخدام OPcache

```bash
# تفعيل OPcache في php.ini
# عادة في: /opt/homebrew/etc/php/8.2/php.ini
```

### 2. استخدام Redis للـ Cache

```bash
# تثبيت Redis
brew install redis
brew services start redis
```

### 3. تحسين قاعدة البيانات

```bash
# إضافة indexes
php artisan migrate

# تحسين الاستعلامات
php artisan optimize
```

### 4. تحسين Composer

```bash
# تحديث Composer
composer self-update

# تحسين autoload
composer dump-autoload -o
```

---

## مقارنة الأداء

| الطريقة | السرعة | السهولة | الاستخدام |
|---------|--------|---------|-----------|
| **Laravel Serve** | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | التطوير السريع |
| **Laravel Valet** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | الأفضل للمطورين |
| **Apache** | ⭐⭐⭐⭐ | ⭐⭐⭐ | الإنتاج |
| **Nginx** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | الإنتاج |

---

## الأوامر السريعة

### Laravel Serve (بسيط)
```bash
php artisan serve
```

### Laravel Valet (الأفضل)
```bash
valet install
valet link dr-system
# ثم افتح: http://dr-system.test
```

---

## نصيحتي النهائية

**استخدم Laravel Valet** لأنه:
- ✅ أسرع أداء
- ✅ أسهل إعداد
- ✅ أفضل تجربة
- ✅ المعيار في macOS

**استخدم Laravel Serve** فقط إذا:
- ❌ تريد شيئاً بسيطاً جداً
- ❌ مشروع واحد فقط
- ❌ لا تريد تثبيت Valet

---

## الخطوات الكاملة (ملخص)

```bash
# 1. إكمال إعداد Homebrew
echo >> /Users/baitpait/.zprofile
echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> /Users/baitpait/.zprofile
eval "$(/opt/homebrew/bin/brew shellenv)"

# 2. تثبيت PHP و Composer
brew install php@8.2 composer mysql node
brew link php@8.2

# 3. تثبيت Valet (الأفضل)
composer global require laravel/valet
echo 'export PATH="$HOME/.composer/vendor/bin:$PATH"' >> ~/.zprofile
source ~/.zprofile
valet install

# 4. ربط المشروع
cd "/Users/baitpait/BAITPAIT/Bait Pait/Project/Dr alaa Talbeshy/Dr/Dr-system"
valet link dr-system

# 5. افتح المتصفح على: http://dr-system.test
```

---

**آخر تحديث:** 2025-12-10

