// Chờ DOM load xong mới thực thi
document.addEventListener("DOMContentLoaded", function () {
  // Khởi tạo giỏ hàng
  initCart();

  // Cập nhật số lượng giỏ hàng trong header khi trang được tải
  updateCartBadge();
});

function initCart() {
  // Lấy các phần tử DOM
  const selectAllCheckbox = document.getElementById("select-all");
  const itemCheckboxes = document.querySelectorAll(".item-checkbox");
  const selectAllLabel = document.querySelector('label[for="select-all"]');
  const decreaseButtons = document.querySelectorAll(".quantity-btn.decrease");
  const increaseButtons = document.querySelectorAll(".quantity-btn.increase");
  const quantityInputs = document.querySelectorAll(".quantity-input");
  const productCountNumber = document.querySelector(".product-count-number");
  const removeButtons = document.querySelectorAll(".remove-btn");
  const bulkDeleteBtn = document.querySelector(".bulk-delete-btn");
  const checkoutBtn = document.querySelector(".checkout-btn");

  // Cập nhật số lượng sản phẩm hiển thị
  updateTotalProductCount();

  // Cập nhật giá tạm tính và thành tiền ban đầu
  updatePrices();

  // Sự kiện cho checkbox chọn tất cả
  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener("change", function () {
      const isChecked = this.checked;

      // Đồng bộ trạng thái với tất cả checkbox sản phẩm
      itemCheckboxes.forEach((checkbox) => {
        checkbox.checked = isChecked;
      });

      // Cập nhật label cho "Chọn tất cả"
      updateSelectAllLabel();

      // Cập nhật giá
      updatePrices();
    });
  }

  // Sự kiện cho các checkbox sản phẩm
  itemCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      // Kiểm tra xem tất cả checkbox đã được chọn chưa
      const allChecked = Array.from(itemCheckboxes).every((cb) => cb.checked);

      // Cập nhật trạng thái cho checkbox "Chọn tất cả"
      if (selectAllCheckbox) {
        selectAllCheckbox.checked = allChecked;
      }

      // Cập nhật label cho "Chọn tất cả"
      updateSelectAllLabel();

      // Cập nhật giá
      updatePrices();
    });
  });

  // Sự kiện giảm số lượng sản phẩm
  decreaseButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const cartItem = this.closest(".cart-item");
      const itemId = cartItem.dataset.id;
      const quantityInput = cartItem.querySelector(".quantity-input");
      let currentValue = parseInt(quantityInput.value);

      if (currentValue > 1) {
        currentValue--;
        quantityInput.value = currentValue;

        // Cập nhật số lượng trên server
        updateCartItemQuantity(itemId, currentValue);

        // Cập nhật UI
        updateTotalProductCount();
        updatePrices();
      }
    });
  });

  // Sự kiện tăng số lượng sản phẩm
  increaseButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const cartItem = this.closest(".cart-item");
      const itemId = cartItem.dataset.id;
      const quantityInput = cartItem.querySelector(".quantity-input");
      let currentValue = parseInt(quantityInput.value);

      currentValue++;
      quantityInput.value = currentValue;

      // Cập nhật số lượng trên server
      updateCartItemQuantity(itemId, currentValue);

      // Cập nhật UI
      updateTotalProductCount();
      updatePrices();
    });
  });

  // Sự kiện xóa sản phẩm đơn lẻ
  removeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Lấy phần tử cha (cart-item)
      const cartItem = this.closest(".cart-item");
      const itemId = cartItem.dataset.id;
      const productName = cartItem.querySelector(
        ".product-details h3"
      ).textContent;

      // Hiển thị thông báo xác nhận trước khi xóa
      if (
        confirm(`Bạn có chắc muốn xóa sản phẩm "${productName}" khỏi giỏ hàng?`)
      ) {
        // Gọi API xóa sản phẩm khỏi giỏ hàng
        removeCartItem(itemId).then(() => {
          // Xóa sản phẩm khỏi DOM
          cartItem.remove();

          // Cập nhật lại các phần tử sau khi xóa
          updateTotalProductCount();
          updateSelectAllLabel();
          updatePrices();
          updateCartBadge();

          // Kiểm tra nếu giỏ hàng trống thì hiển thị thông báo
          checkEmptyCart();
        });
      }
    });
  });

  // Sự kiện xóa hàng loạt
  if (bulkDeleteBtn) {
    bulkDeleteBtn.addEventListener("click", function () {
      const checkedItems = document.querySelectorAll(".item-checkbox:checked");

      if (checkedItems.length === 0) {
        alert("Vui lòng chọn ít nhất một sản phẩm để xóa!");
        return;
      }

      if (confirm("Bạn có chắc muốn xóa các sản phẩm đã chọn?")) {
        // Tạo một mảng promises cho các thao tác xóa
        const deletePromises = [];

        checkedItems.forEach((checkbox) => {
          const cartItem = checkbox.closest(".cart-item");
          const itemId = cartItem.dataset.id;

          // Thêm promise xóa vào mảng
          deletePromises.push(
            removeCartItem(itemId).then(() => {
              cartItem.remove();
            })
          );
        });

        // Chờ tất cả các promises hoàn thành
        Promise.all(deletePromises).then(() => {
          // Cập nhật lại các phần tử sau khi xóa
          updateTotalProductCount();
          updateSelectAllLabel();
          updatePrices();
          updateCartBadge();

          // Kiểm tra nếu giỏ hàng trống thì hiển thị thông báo
          checkEmptyCart();
        });
      }
    });
  }

  // Kiểm tra nút thanh toán
  if (checkoutBtn) {
    checkoutBtn.addEventListener("click", function (e) {
      if (this.dataset.loggedIn === "true") {
        const checkedItems = document.querySelectorAll(
          ".item-checkbox:checked"
        );
        if (checkedItems.length === 0) {
          e.preventDefault();
          alert("Vui lòng chọn ít nhất một sản phẩm để thanh toán!");
          return;
        }

        // Lấy danh sách ID sản phẩm đã chọn
        const selectedIds = Array.from(checkedItems).map((item) => {
          const cartItem = item.closest(".cart-item");
          return cartItem.dataset.id;
        });

        // Lấy tổng giá
        const finalPriceElement = document.querySelector(".total-final .price");
        const totalPrice = parseFloat(
          finalPriceElement.textContent.replace(/\./g, "")
        );

        // Gán giá trị vào form ẩn
        const selectedItemsInput = document.getElementById("selected-items");
        const totalPriceInput = document.getElementById("total-price");
        selectedItemsInput.value = JSON.stringify(selectedIds);
        totalPriceInput.value = totalPrice;

        // Gửi form
        document.getElementById("checkout-form").submit();
      }
    });
  }
}

// Hàm cập nhật số sản phẩm tổng cộng
function updateTotalProductCount() {
  const quantityInputs = document.querySelectorAll(".quantity-input");
  const productCountNumber = document.querySelector(".product-count-number");

  if (!productCountNumber) return;

  let totalItems = 0;
  quantityInputs.forEach((input) => {
    totalItems += parseInt(input.value);
  });

  productCountNumber.textContent = totalItems;
}

// Hàm cập nhật label "Chọn tất cả"
function updateSelectAllLabel() {
  const selectAllLabel = document.querySelector('label[for="select-all"]');
  if (!selectAllLabel) return;

  const checkedCount = document.querySelectorAll(
    ".item-checkbox:checked"
  ).length;
  selectAllLabel.textContent = `Chọn tất cả (${checkedCount})`;
}

// Hàm cập nhật giá tạm tính và thành tiền
function updatePrices() {
  const cartItems = document.querySelectorAll(".cart-item");
  const provisionalPrice = document.querySelector(".total-provisional .price");
  const finalPrice = document.querySelector(".total-final .price");

  if (!provisionalPrice || !finalPrice) return;

  let totalPrice = 0;

  cartItems.forEach((item) => {
    const checkbox = item.querySelector(".item-checkbox");

    // Chỉ tính giá cho các sản phẩm đã chọn
    if (checkbox && checkbox.checked) {
      const priceText = item.querySelector(".current-price").textContent;
      const price = parseFloat(priceText.replace(/\./g, ""));
      const quantity = parseInt(item.querySelector(".quantity-input").value);

      totalPrice += price * quantity;
    }
  });

  // Định dạng giá tiền với dấu chấm ngăn cách hàng nghìn
  provisionalPrice.textContent = formatCurrency(totalPrice);
  finalPrice.textContent = formatCurrency(totalPrice);
}

// Hàm định dạng tiền tệ
function formatCurrency(amount) {
  return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Hàm cập nhật số lượng sản phẩm
function updateCartItemQuantity(itemId, quantity) {
  const formData = new FormData();
  formData.append("item_id", itemId);
  formData.append("quantity", quantity);
  formData.append("action", "update");

  console.log("Sending update request:", itemId, quantity);

  return fetch("update_cart.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      // In log để kiểm tra
      console.log("Raw response:", response);
      return response.text();
    })
    .then((text) => {
      console.log("Response text:", text);
      try {
        return JSON.parse(text);
      } catch (e) {
        console.error("Không thể parse JSON:", e);
        throw new Error("Phản hồi không phải định dạng JSON hợp lệ");
      }
    })
    .then((data) => {
      if (!data.success) {
        console.error("Lỗi cập nhật:", data.message);
        alert(data.message || "Có lỗi xảy ra khi cập nhật giỏ hàng");
      }
      // Cập nhật số lượng sản phẩm trong giỏ hàng header
      updateCartBadge();
      return data;
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Đã xảy ra lỗi khi cập nhật giỏ hàng");
    });
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeCartItem(itemId) {
  const formData = new FormData();
  formData.append("item_id", itemId);
  formData.append("action", "remove");

  console.log("Sending remove request for item ID:", itemId);

  return fetch("update_cart.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      console.log("Raw response:", response);
      return response.text();
    })
    .then((text) => {
      console.log("Response text:", text);
      try {
        return JSON.parse(text);
      } catch (e) {
        console.error("Không thể parse JSON:", e);
        throw new Error("Phản hồi không phải định dạng JSON hợp lệ");
      }
    })
    .then((data) => {
      if (!data.success) {
        console.error("Lỗi xóa:", data.message);
        alert(data.message || "Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng");
      }
      return data;
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Đã xảy ra lỗi khi xóa sản phẩm khỏi giỏ hàng");
    });
}

// Hàm kiểm tra giỏ hàng trống và hiển thị thông báo nếu cần
function checkEmptyCart() {
  const cartItems = document.querySelectorAll(".cart-item");
  const cartItemsContainer = document.querySelector(".cart-items");

  if (cartItems.length === 0 && cartItemsContainer) {
    cartItemsContainer.innerHTML =
      '<div class="empty-cart-message">Giỏ hàng của bạn hiện đang trống</div>';

    // Ẩn phần thanh toán và chọn tất cả nếu không có sản phẩm
    const selectAllContainer = document.querySelector(".select-all-container");
    if (selectAllContainer) {
      selectAllContainer.style.visibility = "hidden";
    }

    const bulkActions = document.querySelector(".bulk-actions");
    if (bulkActions) {
      bulkActions.style.visibility = "hidden";
    }

    // Reset giá về 0
    const provisionalPrice = document.querySelector(
      ".total-provisional .price"
    );
    const finalPrice = document.querySelector(".total-final .price");

    if (provisionalPrice) provisionalPrice.textContent = "0";
    if (finalPrice) finalPrice.textContent = "0";
  }
}

function updateCartBadge() {
  fetch("product/get_cart_count.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      if (data.success && data.count !== undefined) {
        const cartBadges = document.querySelectorAll(".cart-badge");
        cartBadges.forEach((badge) => {
          badge.textContent = data.count;
        });
      } else {
        console.warn("Dữ liệu trả về không hợp lệ:", data);
      }
    })
    .catch((error) => {
      console.error("Lỗi khi cập nhật số lượng giỏ hàng:", error);
    });
}
