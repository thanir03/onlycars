const state = {
  imageIndex: 0,
};

const leftArrow = document.querySelector(`.left-arrow-icon`);
const rightArrow = document.querySelector(`.right-arrow-icon`);
const totalImages = document.querySelectorAll(`.car-image`);
const editBtn = document.querySelector(`.edit-car-btn`);
const listBtn = document.querySelector(`.list-car-btn`);
const backDropModal = document.querySelector(`.edit-modal`);
const modal = document.querySelector(`.edit-container`);
const price = document.querySelector(`.Price`);
const toastModal = document.querySelector(`.modal`);

const carId = Number(document.body.dataset.car_id);

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

editBtn.addEventListener("click", (e) => {
  document.querySelector(".edit-modal").style.display = "flex";
  window.scrollTo({ top: 0, behavior: "smooth" });
  let updateBtn = document.querySelector(`.update-btn`);
  updateBtn.addEventListener("click", () => {
    updateCar({ carId, price: Number(price.value) });
  });
});

modal.addEventListener("click", (e) => {
  e.stopPropagation();
});

backDropModal.addEventListener("click", () => {
  console.log("backdrop closed");
  document.querySelector(".edit-modal").style.display = "none";
});

price.addEventListener("blur", () => {
  let err = "";
  const minPrice = 1_000;
  const maxPrice = 1_000_000;
  if (price.value.length === 0) {
    err = "Price is empty";
  } else if (Number(price.value) <= 0) {
    err = "Invalid price value";
  } else if (Number(price.value) < minPrice || Number(price.value) > maxPrice) {
    err = `Price must be between ${minPrice} and ${maxPrice}`;
  }
  if (err) {
    document.querySelector(
      `.price-error`
    ).innerHTML = `<p class='error'>${err}</p>`;
  } else {
    document.querySelector(`.price-error`).innerHTML = "";
  }
});

listBtn.addEventListener("click", () => {
  const isListed = listBtn.dataset.listed === "true";
  updateCar({ isListed: !isListed, carId });
});

const updateCar = async (carDetails) => {
  try {
    const res = await fetch("http://localhost/onlycars/api/cars.php", {
      method: "PUT",
      body: JSON.stringify(carDetails),
    });

    const json = await res.json();
    if (json.status == "success") {
      displayToast("success", "Succesfully update car details", () =>
        window.location.reload()
      );
    } else {
      displayToast("error", "Failed to update car details");
    }
  } catch (error) {
    displayToast("error", "Unkown error");
  }
};

const displayToast = (status, message, action = () => {}) => {
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
    action();
  }, 1000);
};
