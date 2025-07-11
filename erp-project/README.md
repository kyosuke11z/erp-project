# 🚀 Laravel ERP - ระบบจัดการธุรกิจครบวงจร

[![PHP Version](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Livewire Version](https://img.shields.io/badge/Livewire-3.x-4d52c1?style=for-the-badge&logo=livewire)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-06B6D4?style=for-the-badge&logo=tailwindcss)](https://tailwindcss.com)


โปรเจกต์ Portfolio สำหรับสร้างระบบ Enterprise Resource Planning (ERP) พื้นฐานด้วยเทคโนโลยีที่ทันสมัย ออกแบบมาเพื่อการจัดการข้อมูลหลักของธุรกิจอย่างมีประสิทธิภาพและมอบประสบการณ์การใช้งานที่รวดเร็ว

---

### ✨ Live Demo

> **[ลิงก์สำหรับ Demo จะถูกเพิ่มที่นี่เมื่อ Deploy เสร็จสิ้น]**

---

### 📸 Screenshots

*(ส่วนนี้สำหรับใส่ภาพหน้าจอของโปรแกรมเมื่อพัฒนาเสร็จแล้ว)*

<p align="center">
  <!-- <img src="link-to-your-screenshot.png" width="48%"> -->
  <!-- <img src="link-to-your-screenshot-2.png" width="48%"> -->
</p>

---

## 🌟 Core Features (คุณสมบัติหลัก)

-   **ระบบผู้ใช้งานและสิทธิ์ (Authentication & Authorization):**
    -   ระบบล็อกอิน-ล็อกเอาต์
    -   การจัดการสิทธิ์ตามบทบาท (Role-based Access Control) ด้วย `spatie/laravel-permission`
-   **การจัดการหมวดหมู่ (Category Management):**
    -   ระบบ CRUD (Create, Read, Update, Delete) ที่สมบูรณ์แบบ
    -   จัดการข้อมูลผ่าน Modal ไม่ต้องเปลี่ยนหน้า
    -   ระบบค้นหาแบบ Real-time (Live Search)
    -   ระบบ Soft Deletes พร้อมหน้า "ถังขยะ" สำหรับกู้คืนหรือลบถาวร
-   **การจัดการสินค้า (Product Management):**
    -   ระบบ CRUD ที่สมบูรณ์พร้อมความสัมพันธ์กับตารางหมวดหมู่
    -   ระบบค้นหาจากชื่อสินค้าหรือ SKU
    -   กำหนด "จุดสั่งซื้อขั้นต่ำ" (Minimum Stock Level) สำหรับสินค้าแต่ละชิ้นได้
    -   ระบบ Soft Deletes และ "ถังขยะ"
-   **การจัดการลูกค้า (Customer Management):**
    -   ระบบ CRUD พื้นฐานสำหรับจัดการข้อมูลลูกค้า
-   **การจัดการคำสั่งขาย (Sales Order Management):**
    -   ระบบสร้างใบสั่งขายที่สามารถเลือกสินค้าได้หลายรายการ
    -   คำนวณยอดรวมอัตโนมัติ
    -   แสดงรายการคำสั่งขายทั้งหมด พร้อมระบบค้นหา (ตามเลขที่, ชื่อลูกค้า) และ Pagination
    -   ระบบตรวจสอบสต็อกสินค้าคงเหลือก่อนสร้างใบสั่งขาย
    -   ตัดสต็อกสินค้าอัตโนมัติเมื่อสร้างใบสั่งขาย
-   **ระบบแจ้งเตือนสต็อกต่ำ (Low Stock Notification):**
    -   ระบบตรวจสอบสต็อกสินค้าอัตโนมัติผ่าน Artisan Command (`app:check-low-stock`)
    -   แจ้งเตือนไปยังผู้ดูแลระบบ (Admin) ผ่าน Database Notification เมื่อสินค้าถึงจุดสั่งซื้อขั้นต่ำ
    -   แสดงผลการแจ้งเตือนบน UI ที่เมนูบาร์
-   **ระบบจัดซื้อและรับสินค้า (Purchasing & Receiving):**
    -   ระบบ CRUD สำหรับใบสั่งซื้อ (Purchase Order)
    -   ระบบรับสินค้า (Goods Receipt) ที่อ้างอิงจากใบสั่งซื้อ
    -   ระบบคืนสินค้าให้ซัพพลายเออร์ (Supplier Return) พร้อมการตรวจสอบจำนวนที่ซับซ้อนและปรับลดสต็อกอัตโนมัติ
    -   สามารถพิมพ์ใบสั่งซื้อเป็นไฟล์ PDF ได้

---

## 🛠️ Technology Stack (เทคโนโลยีที่ใช้)

-   **Backend:** Laravel v12
-   **Frontend:** Livewire v3, Tailwind CSS 4.x, Alpine.js
-   **Database:** MySQL
-   **Development Environment:** Docker, Laravel Sail, WSL2 (Ubuntu)

---

## ⚙️ การติดตั้งและรันโปรเจกต์ (Local Setup)

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/laravel-erp-portfolio.git
    cd laravel-erp-portfolio
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    npm install
    ```

3.  **Setup environment file:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *จากนั้นเข้าไปแก้ไขค่าการเชื่อมต่อฐานข้อมูลในไฟล์ `.env`*

4.  **Build frontend assets:**
    ```bash
    npm run build
    ```

5.  **Run database migrations (and seeder if available):**
    ```bash
    php artisan migrate --seed
    ```

6.  **Start the development server:**
    ```bash
    php artisan serve
    ```

---

## 🗺️ Roadmap (แผนการพัฒนาต่อไป)

-   [ ] **Dashboard:** เพิ่มการแสดงผลข้อมูลสรุปและกราฟ
-   [x] **Customers:** ระบบจัดการข้อมูลลูกค้า
-   [x] **Sales:** ระบบจัดการคำสั่งขาย (Sales Order)
    -   [x] การตัดสต็อกสินค้าอัตโนมัติเมื่อสร้าง/ลบออเดอร์
-   [x] **Purchasing:** ระบบจัดการใบสั่งซื้อ (Purchase Order) และ Supplier
    -   [x] ระบบจัดการข้อมูล Supplier
    -   [x] สร้าง/แก้ไข/ดูรายละเอียด ใบสั่งซื้อ
    -   [x] ระบบรับสินค้า (Goods Receipt) จากใบสั่งซื้อ
    -   [x] ระบบคืนสินค้าให้ซัพพลายเออร์ (Supplier Return)
        -   [x] ตรวจสอบจำนวนที่สามารถคืนได้จากประวัติการคืน
        -   [x] ปรับลดสต็อกสินค้าอัตโนมัติเมื่อทำการคืน
    -   [x] พิมพ์ใบสั่งซื้อเป็น PDF
    -   [x] สร้างหน้าสำหรับแสดงรายการ "ใบคืนสินค้า" (Supplier Return Index/Show)
    -   [x] ในหน้า "รายละเอียดใบรับสินค้า" เพิ่มส่วนแสดงประวัติการคืนสินค้า
-   [ ] **Finance:** ระบบบันทึกรายรับ-รายจ่ายเบื้องต้น
-   [ ] **Settings:** หน้าตั้งค่าระบบทั่วไป

---

## 📄 License

This project is open-source and available under the MIT License.