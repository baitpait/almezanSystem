# تثبيت Homebrew على macOS

## المشكلة

إذا ظهر لك هذا الخطأ:
```
zsh: command not found: brew
```

هذا يعني أن **Homebrew غير مثبت** على جهازك. يجب تثبيته أولاً.

---

## الحل: تثبيت Homebrew

### الخطوة 1: فتح Terminal

1. اضغط **Command + Space** (⌘ + Space)
2. اكتب **Terminal**
3. اضغط **Enter**

### الخطوة 2: تثبيت Homebrew

انسخ هذا الأمر كاملاً والصقه في Terminal:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

**كيفية التنفيذ:**
1. انسخ الأمر أعلاه كاملاً (من `/bin/bash` حتى النهاية)
2. الصق في Terminal (Command + V)
3. اضغط **Enter**
4. قد يطلب منك إدخال **كلمة مرور Mac** - اكتبها واضغط Enter
5. انتظر حتى ينتهي التثبيت (قد يستغرق 5-10 دقائق)

### الخطوة 3: إضافة Homebrew إلى PATH

بعد انتهاء التثبيت، قد يظهر لك رسالة مثل:

```
==> Next steps:
- Run these two commands in your terminal to add Homebrew to your PATH:
    echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile
    eval "$(/opt/homebrew/bin/brew shellenv)"
```

**انسخ هذين الأمرين واحداً تلو الآخر:**

#### الأمر الأول:
```bash
echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile
```

#### الأمر الثاني:
```bash
eval "$(/opt/homebrew/bin/brew shellenv)"
```

### الخطوة 4: التحقق من التثبيت

بعد تنفيذ الأوامر أعلاه، تحقق من أن Homebrew يعمل:

```bash
brew --version
```

إذا ظهر لك إصدار Homebrew (مثل `Homebrew 4.x.x`)، فالتثبيت نجح! ✅

---

## بعد تثبيت Homebrew

الآن يمكنك تثبيت البرمجيات المطلوبة:

### 1. تثبيت PHP
```bash
brew install php@8.2
brew link php@8.2
```

### 2. تثبيت Composer
```bash
brew install composer
```

### 3. تثبيت MySQL
```bash
brew install mysql
brew services start mysql
```

### 4. تثبيت Node.js
```bash
brew install node
```

---

## حل المشاكل

### المشكلة: لا يطلب كلمة مرور

**الحل:** هذا طبيعي إذا كان Homebrew مثبتاً مسبقاً. جرب الخطوة 3 مباشرة.

### المشكلة: خطأ في الصلاحيات

**الحل:** تأكد من أنك تستخدم حساب مستخدم بصلاحيات إدارية (Administrator).

### المشكلة: خطأ في الاتصال

**الحل:** تأكد من اتصالك بالإنترنت.

### المشكلة: لا يزال يظهر "command not found"

**الحل:** 
1. أغلق Terminal تماماً
2. افتح Terminal جديد
3. جرب الأمر مرة أخرى

أو نفذ هذا الأمر:
```bash
eval "$(/opt/homebrew/bin/brew shellenv)"
```

---

## ملاحظات مهمة

1. **كلمة المرور:** عند طلب كلمة المرور، لن تظهر الأحرف عند الكتابة - هذا طبيعي! فقط اكتب واضغط Enter.

2. **الانتظار:** تثبيت Homebrew قد يستغرق 5-10 دقائق. لا تغلق Terminal أثناء التثبيت.

3. **الإنترنت:** تأكد من اتصالك بالإنترنت قبل البدء.

---

## التحقق النهائي

بعد تثبيت Homebrew، تأكد من أن كل شيء يعمل:

```bash
# التحقق من Homebrew
brew --version

# تحديث Homebrew (موصى به)
brew update
```

---

## الخطوات الكاملة (ملخص)

```bash
# 1. تثبيت Homebrew
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# 2. إضافة Homebrew إلى PATH
echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile
eval "$(/opt/homebrew/bin/brew shellenv)"

# 3. التحقق من التثبيت
brew --version

# 4. تثبيت البرمجيات المطلوبة
brew install php@8.2 composer mysql node
```

---

**آخر تحديث:** 2025-12-10

