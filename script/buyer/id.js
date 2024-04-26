const state = {
  imageIndex: 0,
};

const leftArrow = document.querySelector(`.left-arrow-icon`);
const rightArrow = document.querySelector(`.right-arrow-icon`);
const totalImages = document.querySelectorAll(`.car-image`);
const toastModal = document.querySelector(`.modal`);

const backBtn = document.querySelector(`.back-btn`);
const navigateDecrementImageIndex = () => {
  if (state.imageIndex == 0) return;
  totalImages[state.imageIndex].classList.remove("image-active");
  totalImages[--state.imageIndex].classList.add("image-active");
};
const navigateIncrementImageIndex = () => {
  if (state.imageIndex === totalImages.length - 1) return;
  totalImages[state.imageIndex].classList.remove("image-active");
  totalImages[++state.imageIndex].classList.add("image-active");
};

leftArrow.addEventListener("click", navigateDecrementImageIndex);
rightArrow.addEventListener("click", navigateIncrementImageIndex);

const bookBtn = document.querySelector(`.book-now-btn`);
const addToCartBtn = document.querySelector(`.add-cart-btn`);

bookBtn.addEventListener("click", async () => {
  // Make http request to add user
  // Navigate to orders page
  bookBtn.textContent = "Loading";
  const carId = document.body.dataset.car_id;
  try {
    const response = await fetch(`http://localhost/onlycars/api/book.php`, {
      method: "POST",
      body: JSON.stringify({
        carId,
      }),
    });
    bookBtn.textContent = "Book Car";
    const json = await response.json();
    console.log(json);
    if (response.ok) {
      displayToast("success",json.success);
    } else {
      // display an error popup
      displayToast("error", json.error);
    }
  } catch (error) {
    console.log(error);
    displayToast("error", "Unknown error occurred");
  }
});

addToCartBtn.addEventListener("click", async () => {
  const carId = document.body.dataset.car_id;
  const response = await fetch("http://localhost/onlycars/api/cart.php", {
    method: "POST",
    body: JSON.stringify({ carId }),
  });
  const json = await response.json();
  displayToast(json.status, json.message);
});

const displayToast = (status, message) => {
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
  }, 1000);
};
