document.addEventListener("DOMContentLoaded", function () {
  const menuBtn = document.querySelector(".menu-btn"); // Nút mở menu
  const phoneMegaMenu = document.getElementById("phone-mega-menu"); // Submenu điện thoại
  const menuContainer = document.getElementById("menu-container"); // Toàn bộ khu vực menu
  const overlay = document.querySelector(".menu-overlay"); // Overlay
  const laptopMenuItem = document.querySelector("#laptop-menu-item a"); // Mục Laptop trong menu
  const tabletMenuItem = document.querySelector("#ipad-menu-item a"); // Mục Máy tính bảng trong menu
  const phoneMenuItem = document.querySelector(
    ".main-category-list li:first-child a"
  ); // Mục Điện thoại (li đầu tiên trong main-category-list)
  const laptopSection = document.querySelector(
    ".title-wrapper1 .dienthoai-container1 a.dienthoai-title1"
  ); // Phần danh mục Laptop
  const tabletSection = document.querySelector(
    ".title-wrapper2 .dienthoai-container2 a.dienthoai-title2"
  ); // Phần danh mục Máy tính bảng
  const phoneSection = document.querySelector(
    ".title-wrapper .dienthoai-container a.dienthoai-title"
  ); // Phần danh mục Điện thoại

  let menuOpen = false;

  // Hàm hỗ trợ cuộn mượt mà
  function smoothScrollTo(targetY, duration) {
    const startY = window.pageYOffset;
    const distance = targetY - startY;
    let startTime = null;

    function animation(currentTime) {
      if (startTime === null) startTime = currentTime;
      const timeElapsed = currentTime - startTime;
      const progress = Math.min(timeElapsed / duration, 1); // Giới hạn progress tối đa là 1
      const ease = (progress) => progress * (2 - progress); // Ease-in-out

      window.scrollTo(0, startY + distance * ease(progress));

      if (timeElapsed < duration) {
        requestAnimationFrame(animation);
      }
    }

    requestAnimationFrame(animation);
  }

  // Hiển thị menu điện thoại trực tiếp khi hover vào nút menu
  menuBtn.addEventListener("mouseenter", function () {
    phoneMegaMenu.style.display = "block";
    menuOpen = true;
    overlay.classList.add("active");
  });

  // Giữ submenu điện thoại mở khi hover vào nó
  phoneMegaMenu.addEventListener("mouseenter", function () {
    phoneMegaMenu.style.display = "block";
    menuOpen = true;
    overlay.classList.add("active");
  });

  // Đóng menu khi di chuột ra ngoài phạm vi menuContainer
  menuContainer.addEventListener("mouseleave", function () {
    phoneMegaMenu.style.display = "none";
    menuOpen = false;
    overlay.classList.remove("active");
  });

  // Đóng menu khi click vào overlay
  overlay.addEventListener("click", function () {
    phoneMegaMenu.style.display = "none";
    menuOpen = false;
    overlay.classList.remove("active");
  });

  // Hàm xử lý cuộn cho các mục menu
  function handleMenuItemClick(menuItem, section) {
    if (menuItem && section) {
      menuItem.addEventListener("click", (event) => {
        event.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ a
        // Đóng submenu và overlay
        phoneMegaMenu.style.display = "none";
        menuOpen = false;
        overlay.classList.remove("active");

        // Tính toán vị trí của phần danh mục
        const targetPosition =
          section.getBoundingClientRect().top + window.pageYOffset - 100; // Trừ 100px để có khoảng cách đỉnh
        // Thực hiện cuộn mượt mà
        smoothScrollTo(targetPosition, 1200); // 800ms là thời gian cuộn
      });
    } else {
      console.error(
        `Không tìm thấy ${menuItem ? "section" : "menuItem"} cho mục`
      );
    }
  }

  // Xử lý click cho các mục menu
  handleMenuItemClick(laptopMenuItem, laptopSection); // Laptop
  handleMenuItemClick(tabletMenuItem, tabletSection); // Máy tính bảng
  handleMenuItemClick(phoneMenuItem, phoneSection); // Điện thoại
});

document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".tab-btn");
  const phoneSections = document.querySelectorAll(
    ".phone-tabs + .product-section"
  ); // Chỉ áp dụng cho phần Điện thoại

  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      let target = this.getAttribute("data-tab");

      // Xóa class active khỏi tất cả tab và section của Điện thoại
      tabs.forEach((t) => t.classList.remove("active"));
      phoneSections.forEach((s) => s.classList.remove("active"));

      // Thêm class active cho tab và section được chọn
      this.classList.add("active");
      if (document.getElementById(target)) {
        document.getElementById(target).classList.add("active");
      }
    });
  });
});

function showTab(tabId, element) {
  // Ẩn tất cả tab-content của Điện thoại
  const phoneSections = document.querySelectorAll(
    ".phone-tabs + .product-section"
  );
  phoneSections.forEach((tab) => {
    tab.classList.remove("active");
  });
  if (document.getElementById(tabId)) {
    document.getElementById(tabId).classList.add("active");
  }

  // Bỏ active khỏi tất cả tab-button của Điện thoại
  const phoneTabs = document.querySelectorAll(".phone-tabs .tab-btn");
  phoneTabs.forEach((btn) => {
    btn.classList.remove("active");
  });
  element.classList.add("active");

  // Di chuyển thanh đỏ
  let indicator = document.querySelector(".tab-indicator");
  if (indicator) {
    indicator.style.width = element.offsetWidth + "px";
    indicator.style.left = element.offsetLeft + "px";
  }
}

// Đặt mặc định cho thanh đỏ
document.addEventListener("DOMContentLoaded", function () {
  let activeTab = document.querySelector(".phone-tabs .tab-btn.active");
  let indicator = document.querySelector(".tab-indicator");
  if (activeTab && indicator) {
    indicator.style.width = activeTab.offsetWidth + "px";
    indicator.style.left = activeTab.offsetLeft + "px";
  }
});
function setSearchValue(value) {
  const searchInput = document.getElementById("search-input");
  searchInput.value = value; // Gán giá trị vào input
  document.getElementById("search-form").submit(); // Submit form tự động
}

// Hàm validateSearch (giả định tồn tại, giữ nguyên nếu đã có)
function validateSearch() {
  const searchInput = document.getElementById("search-input").value.trim();
  if (searchInput === "") {
    alert("Vui lòng nhập từ khóa tìm kiếm!");
    return false;
  }
  return true;
}
