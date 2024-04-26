const deleteBtns = document.querySelectorAll(`.delete-container`);
const checkoutBtns = document.querySelectorAll(`.checkout-btn`);
const toastModal = document.querySelector(`.modal`);

const removeItemFromCart = async (cartId) => {
  const url = new URL("http://localhost/onlycars/api/cart");
  url.searchParams.append("cartId", cartId);
  const res = await fetch(url.toString(), {
    method: "DELETE",
  });
  const json = await res.json();
  return json;
};

const displayToast = (status, message, action) => {
  toastModal.style.display = "block";
  if (status === "error") {
    toastModal.style.backgroundColor = "#d62828";
  } else {
    toastModal.style.backgroundColor = "#4cbb17";
  }
  toastModal.textContent = message;
  setTimeout(() => {
    toastModal.style.display = "none";
    toastModal.textContent = "";
    if (action) {
      action();
    }
  }, 1000);
};

const createBooking = async (carId) => {
  const url = new URL("http://localhost/onlycars/api/book");
  const res = await fetch(url.toString(), {
    method: "POST",
    body: JSON.stringify({ carId }),
  });
  const json = await res.json();

  return json;
};

deleteBtns.forEach((btn) =>
  btn.addEventListener("click", async () => {
    const json = await removeItemFromCart(btn.dataset.cartId);
    if (json.status === "success") {
      document
        .querySelector(
          `.cart-item-container[data-cart-id="${btn.dataset.cartId}"]`
        )
        .remove();
      if (document.querySelectorAll(`.cart-item-container`).length === 0) {
        document.querySelector(`.no-cart-container`).style.display = "flex";
      }
    } else {
      console.log("error deleting from cart");
    }
  })
);

const navigateToCheckout = (bookingId) => {
  window.location.href = `http://localhost/onlycars/buyer/checkout?id=${bookingId}`;
};

checkoutBtns.forEach((btn) =>
  btn.addEventListener("click", async () => {
    console.log("clicked");
    const carId = btn.dataset.carId;
    // create a pending booking
    const bookingRes = await createBooking(carId);
    if (bookingRes.success) {
      console.log(bookingRes.data.bookingId);
      displayToast(
        "success",
        "Sucessfully created booking",
        navigateToCheckout.bind(null, bookingRes.data.booking_id)
      );
    } else {
      displayToast("error", bookingRes.error);
    }
    // navigate to checkout page
  })
);
