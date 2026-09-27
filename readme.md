<div align="center">

# Floating PWA Installer for WordPress

![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-21759B?logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)
[![License: GPLv2+](https://img.shields.io/badge/License-GPLv2%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

[🇬🇧 English](#english) | [🇮🇷 فارسی](#persian)

</div>

---

<a id="english"></a>

## 🇬🇧 English

Turn your website into an installable app experience with a floating install button, custom triggers, and optional safe PWA endpoints.  
Developed by **Tehran Web SEO**.

### ✨ Why Floating PWA Installer?
- Lightweight, practical, and conflict-aware
- Better install UX without aggressive overrides
- Suitable for performance-focused WordPress websites

### ✅ Key Features
- Floating **Install App** button (mobile/desktop positioning)
- **Custom trigger** support (connect your own CTA button)
- Optional **Manifest / Service Worker / Icon** endpoints
- Conflict-aware behavior with existing PWA setups
- Multisite-aware endpoint handling (subdirectory safe)
- Clean settings with sanitization-focused implementation

Custom trigger example:
```html
<button type="button" id="pwa-install-trigger">Install</button>
```

### ⚙️ Compatibility Notes
- Requires **HTTPS** for install eligibility
- Supports plain and pretty permalinks
- Preserves existing manifest links / foreign overlapping workers when applicable
- Does **not** add full offline caching by default

### 🚀 Installation
1. Upload plugin to `/wp-content/plugins/` or install via WP Admin
2. Activate **Floating PWA Installer**
3. Open plugin settings in admin menu
4. Configure button style/position and optional endpoints

### 🧪 QA Checklist
- Endpoint MIME types
- HTTPS install flow
- Custom trigger behavior
- `appinstalled` hide behavior
- iOS instruction UX
- Keyboard focus, RTL, mobile positions
- Existing PWA provider compatibility
- Plain/pretty permalink behavior
- Subdirectory multisite behavior
- Uninstall opt-in cleanup behavior

### 🔐 Security & Quality
- Settings sanitization
- Escaped admin outputs
- Controlled endpoint handling
- WPCS-oriented cleanup and documentation improvements

### 📦 Changelog
**1.0.1**
- Security hardening improvements
- WPCS cleanup and documentation enhancements
- Endpoint handling refinements
- Uninstall flow improvements

### 💼 Professional Services
Need help with **WordPress**, **WooCommerce**, or **Technical SEO**?  
Developed by **Tehran Web SEO**.

---

<a id="persian"></a>

## 🇮🇷 فارسی

این افزونه وب‌سایت وردپرسی شما را به تجربه‌ای اپ‌مانند و حرفه‌ای تبدیل می‌کند تا کاربران بتوانند آن را به‌سادگی روی موبایل و دسکتاپ نصب کنند.

با فراهم‌کردن دسترسی سریع و مستقیم، نرخ بازگشت کاربران و میزان تعامل آن‌ها به‌طور محسوسی افزایش می‌یابد.

خروجی نهایی این بهبود، ارتقای تجربه کاربری، تقویت وفاداری مخاطبان و افزایش فرصت‌های فروش و جذب سرنخ‌های ارزشمند است.

امکانات اصلی:
یک دکمه شناور برای نصب سایت
امکان ویرایش و تغییر تنظیمات دکمه به دلخواه شما

توسعه‌یافته توسط **تهران وب سئو**.

### ✨ چرا این افزونه؟
- سبک، کاربردی و بدون تداخل تهاجمی
- بهبود تجربه نصب برای کاربر
- مناسب سایت‌های وردپرسی با تمرکز روی سرعت و پرفورمنس

### ✅ امکانات اصلی
- دکمه شناور «نصب اپ» (موبایل/دسکتاپ)
- پشتیبانی از **Trigger سفارشی** برای دکمه دلخواه شما
- اندپوینت‌های اختیاری Manifest / Service Worker / Icon
- سازگار با سناریوهای دارای PWA دیگر
- سازگار با Multisite (زیرشاخه)
- تنظیمات با رویکرد Sanitization

نمونه Trigger سفارشی:
```html
<button type="button" id="pwa-install-trigger">نصب اپلیکیشن</button>
```

### ⚙️ نکات سازگاری
- برای نصب PWA، داشتن **HTTPS** ضروری است
- سازگار با permalink ساده و زیبا
- در صورت وجود Manifest/Worker دیگر، رفتار محافظه‌کارانه دارد
- به‌صورت پیش‌فرض، کش آفلاین کامل اضافه نمی‌کند

### 🚀 نصب
1. افزونه را در مسیر `/wp-content/plugins/` آپلود کنید (یا از پنل نصب کنید)
2. افزونه را فعال کنید
3. وارد تنظیمات **Floating PWA Installer** شوید
4. استایل/موقعیت دکمه و اندپوینت‌ها را تنظیم کنید

### 🧪 چک‌لیست تضمین کیفیت (QA)
- بررسی نوع محتوای (MIME Type) آدرس‌های خروجی (Endpoints)
- فرایند نصب روی HTTPS
- عملکرد Trigger سفارشی
- مخفی شدن دکمه بعد از `appinstalled`
- UX راهنمای iOS
- فوکوس کیبورد، RTL، موقعیت موبایل
- سازگاری با PWAهای دیگر
- رفتار permalink ساده/زیبا
- رفتار Multisite زیرشاخه
- حذف داده‌ها فقط با uninstall opt-in

### 🔐 امنیت و کیفیت کد
- پاکسازی Sanitization تنظیمات
- Escape خروجی‌های ادمین
- کنترل دقیق اندپوینت‌ها
- بهبودهای WPCS و مستندسازی

### 📦 تغییرات نسخه
نسخه **1.0.1**
- بهبودهای امنیتی
- پاکسازی کدنویسی بر اساس WPCS
- بهینه‌سازی مدیریت endpoint
- بهبود روند uninstall

### 💼 خدمات حرفه‌ای
برای توسعه حرفه‌ای **وردپرس**، **ووکامرس** و **سئوی فنی** با ما در ارتباط باشید.  
**تهران وب سئو**
