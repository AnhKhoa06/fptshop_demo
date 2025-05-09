



document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.querySelector('.menu-btn'); // Nút mở menu
    const phoneMegaMenu = document.getElementById('phone-mega-menu'); // Submenu điện thoại
    const menuContainer = document.getElementById('menu-container'); // Toàn bộ khu vực menu
    const overlay = document.querySelector('.menu-overlay'); // Overlay
    
    let menuOpen = false;
    
    // Hiển thị menu điện thoại trực tiếp khi hover vào nút menu
    menuBtn.addEventListener('mouseenter', function() {
        phoneMegaMenu.style.display = 'block';
        menuOpen = true;
        overlay.classList.add('active');
    });
    
    // Giữ submenu điện thoại mở khi hover vào nó
    phoneMegaMenu.addEventListener('mouseenter', function() {
        phoneMegaMenu.style.display = 'block';
        menuOpen = true;
        overlay.classList.add('active');
    });
    
    // Đóng menu khi di chuột ra ngoài phạm vi menuContainer
    menuContainer.addEventListener('mouseleave', function() {
        phoneMegaMenu.style.display = 'none';
        menuOpen = false;
        overlay.classList.remove('active');
    });
    
    // Đóng menu khi click vào overlay
    overlay.addEventListener('click', function() {
        phoneMegaMenu.style.display = 'none';
        menuOpen = false;
        overlay.classList.remove('active');
    });
});






















<!-- JavaScript xử lý từng từ khóa riêng biệt -->
document.getElementById('tag-iphone16').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('search-input').value = "iphone 16";
});

document.getElementById('tag-ipad').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('search-input').value = "poco x3";
});

document.getElementById('tag-oppo').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('search-input').value = "iphone 12prm";
});

document.getElementById('tag-samsung').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('search-input').value = "ss s25ultra";
});




document.addEventListener("DOMContentLoaded", function() {
    const tabs = document.querySelectorAll(".tab-btn");
    const sections = document.querySelectorAll(".product-section");

    tabs.forEach(tab => {
        tab.addEventListener("click", function() {
            let target = this.getAttribute("data-tab");

            // Xóa class active khỏi tất cả
            tabs.forEach(t => t.classList.remove("active"));
            sections.forEach(s => s.classList.remove("active"));

            // Thêm class active cho tab được chọn
            this.classList.add("active");
            document.getElementById(target).classList.add("active");
        });
    });
});




function showTab(tabId, element) {
    // Ẩn tất cả tab-content
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.getElementById(tabId).classList.add('active');

    // Bỏ active khỏi tất cả tab-button
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    element.classList.add('active');

    // Di chuyển thanh đỏ
    let indicator = document.querySelector('.tab-indicator');
    indicator.style.width = element.offsetWidth + 'px';
    indicator.style.left = element.offsetLeft + 'px';
}

// Đặt mặc định cho thanh đỏ
document.addEventListener("DOMContentLoaded", function() {
    let activeTab = document.querySelector(".tab-button.active");
    let indicator = document.querySelector('.tab-indicator');
    indicator.style.width = activeTab.offsetWidth + 'px';
    indicator.style.left = activeTab.offsetLeft + 'px';
});
























