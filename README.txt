DACS_CSE702131-1-2-25-N01_NHOM_8



Hướng dẫn chạy chương trình


Bước 1: Cài đặt môi trường
Cài đặt phần mềm XAMPP để sử dụng Apache và MySQL.

Bước 2: Tạo cơ sở dữ liệu

Mở phpMyAdmin.
http://localhost/phpmyadmin/

Tạo database mới (ví dụ: webbanhang).

Import file database.sql của hệ thống để tạo các bảng dữ liệu.

Bước 3: Sao chép mã nguồn

Copy thư mục project vào thư mục htdocs trong XAMPP.

Ví dụ:

xampp/htdocs/webbanhang

Bước 4: Khởi động server

Mở XAMPP Control Panel.

Start Apache và MySQL.

Bước 5: Truy cập hệ thống
Mở trình duyệt và truy cập:

http://localhost/webbanhang

Sau khi truy cập thành công, người dùng có thể đăng ký, đăng nhập và sử dụng các chức năng của hệ thống.
lưu ý khi đăng nhập admin và user tách ra 2 trình duyệt khác.

-----------------------------------------------------------------------------

http://localhost/phpmyadmin/

http://localhost/webbanhang

http://localhost/webbanhang/admin

ALTER TABLE User AUTO_INCREMENT = 1; reset lại bảng

