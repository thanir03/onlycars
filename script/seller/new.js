const form = document.querySelector(`.photo-box`);
const imageInput = document.querySelector(`#upload`);
const modelName = document.querySelector(`.CarModel`);
const year = document.querySelector(`.CarYear`);
const mileage = document.querySelector(`.Mileage`);
const doors = document.querySelector(`.Doors`);
const seat = document.querySelector(`.Seats`);
const width = document.querySelector(`.Width`);
const height = document.querySelector(`.Height`);
const length = document.querySelector(`.Length`);
const color = document.querySelector(`.Color`);
const price = document.querySelector(`.Price`);
const imageContainer = document.querySelector(`.img-uploaded`);
const btn = document.querySelector(`.submit-button`);
const modal = document.querySelector(`.modal`);

let imageList = [];

function isFormValid() {
  const currentYear = new Date().getFullYear();
  const minDoors = 2;
  const maxDoors = 10;
  const minSeats = 2;
  const maxSeats = 10;
  const minPrice = 1000;
  const maxPrice = 1000000;
  const imagesValid =
    imageInput.files.length >= 3 && imageInput.files.length <= 8;
  const modelNameValid = modelName.value.length !== 0;
  const yearValid =
    year.value.trim().length !== 0 &&
    Number(year.value) <= currentYear &&
    currentYear - Number(year.value) <= 50;
  const mileageValid =
    mileage.value.length !== 0 &&
    Number(mileage.value) >= 0 &&
    Number(mileage.value) <= 200000;
  const doorsValid =
    doors.value.length !== 0 &&
    Number(doors.value) >= minDoors &&
    Number(doors.value) <= maxDoors;
  const seatValid =
    seat.value.length !== 0 &&
    Number(seat.value) >= minSeats &&
    Number(seat.value) <= maxSeats;
  const widthValid = width.value.length !== 0 && Number(width.value) > 0;
  const heightValid = height.value.length !== 0 && Number(height.value) > 0;
  const lengthValid = length.value.length !== 0 && Number(length.value) > 0;
  const colorValid = color.value.length !== 0;
  const priceValid =
    price.value.length !== 0 &&
    Number(price.value) > 0 &&
    Number(price.value) >= minPrice &&
    Number(price.value) <= maxPrice;

  return (
    imagesValid &&
    modelNameValid &&
    yearValid &&
    mileageValid &&
    doorsValid &&
    seatValid &&
    widthValid &&
    heightValid &&
    lengthValid &&
    colorValid &&
    priceValid
  );
}

imageInput.addEventListener("change", (event) => {
  const files = Array.from(event.target.files);
  let err = "";
  if (files.length < 3) {
    err = "Must Upload at least 3 images";
  }
  if (files.length > 8) {
    err = "Too many images";
  }
  imageList = files;
  const urls = files.map((item) => URL.createObjectURL(item));
  console.log(urls);
  let html = "";
  for (let i = 0; i < urls.length; i++) {
    html += `<img width='175px' src=${urls[i]} alt=${"hello"} />`;
  }

  if (err) {
    document.querySelector(
      `.img-error`
    ).innerHTML = `<p class='error'>${err}</p>`;
  } else {
    document.querySelector(`.img-error`).innerHTML = ``;
  }
  imageContainer.innerHTML = html;
});

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  if (!isFormValid()) {
    modal.innerHTML = "<p>Invalid car details entered</p>";
    modal.style.display = "flex";
    modal.style.backgroundColor = "red";
    setTimeout(() => {
      modal.style.display = "none";
    }, 3000);
    return;
  }

  const data = new FormData(form);
  btn.textContent = "Loading ...";
  const res = await fetch("http://localhost/onlycars/api/cars.php", {
    method: "POST",
    body: data,
  });
  const json = await res.json();
  if (json["status"] === "success") {
    modal.innerHTML = "<p>Succesfully added car details</p>";
    modal.style.display = "flex";
    modal.style.backgroundColor = "green";
  } else {
    modal.innerHTML = "<p>Failed to add car details</p>";
    modal.style.display = "flex";
    modal.style.backgroundColor = "red";
  }

  setTimeout(() => {
    modal.style.display = "none";
    if (json["status"] === "success") {
      window.location.href = `http://localhost/onlycars/seller/id.php?id=${json.carId}`;
    }
  }, 3000);

  btn.textContent = "Submit Information";
});

modelName.addEventListener("blur", () => {
  if (modelName.value.length === 0) {
    document.querySelector(`.car-model-error`).innerHTML =
      "<p class='error'>Empty car Model</p>";
  } else {
    document.querySelector(`.car-model-error`).innerHTML = "";
  }
});

year.addEventListener("blur", () => {
  const currentYear = new Date().getFullYear();
  let err = "";
  if (year.value.trim().length === 0) {
    console.log(year.value);
    err = "Year is empty";
  } else if (Number(year.value) > currentYear) {
    err = "Invalid car year";
  } else if (currentYear - Number(year.value) > 50) {
    err = "Car cannot be sold. Too long";
  }
  console.log(err);
  if (err) {
    document.querySelector(
      `.year-error`
    ).innerHTML = `<p class='error'>${err}</p>`;
  } else {
    document.querySelector(`.year-error`).innerHTML = "";
  }
});

mileage.addEventListener("blur", () => {
  let err = "";
  if (mileage.value.length === 0) {
    err = "Mileage is empty";
  } else if (Number(mileage.value) < 0) {
    err = "Invalid mileage value";
  } else if (Number(mileage.value) > 200_000) {
    err = "Car cannot be more than 200 000 km";
  }
  if (err) {
    document.querySelector(
      `.mileage-error`
    ).innerHTML = `<p class='error'>${err}</p>`;
  } else {
    document.querySelector(`.mileage-error`).innerHTML = "";
  }
});

doors.addEventListener("blur", () => {
  let err = "";
  const minDoors = 2;
  const maxDoors = 10;
  if (doors.value.length === 0) {
    err = "Number of doors is empty";
  } else {
    const numDoors = Number(doors.value);
    if (numDoors < minDoors || numDoors > maxDoors) {
      err = `Number of doors must be between ${minDoors} and ${maxDoors}`;
    }
  }
  if (err) {
    document.querySelector(
      `.doors-error`
    ).innerHTML = `<p class='error'>${err}</p>`;
  } else {
    document.querySelector(`.doors-error`).innerHTML = "";
  }
});

seat.addEventListener("blur", () => {
  let err = "";
  const minSeats = 2;
  const maxSeats = 10;
  if (seat.value.length === 0) {
    err = "Number of seats is empty";
  } else {
    const numSeats = Number(seat.value);
    if (numSeats < minSeats || numSeats > maxSeats) {
      err = `Number of seats must be between ${minSeats} and ${maxSeats}`;
    }
  }
  if (err) {
    document.querySelector(
      `.seat-error`
    ).innerHTML = `<p class='error'>${err}</p>`;
  } else {
    document.querySelector(`.seat-error`).innerHTML = "";
  }
});

width.addEventListener("blur", () => {
  let err = "";
  if (width.value.length === 0) {
    err = "Width is empty";
  } else if (Number(width.value) <= 0) {
    err = "Invalid width value";
  }
  if (err) {
    document.querySelector(
      `.width-error`
    ).innerHTML = `<p class='error'>${err}</p>`;
  } else {
    document.querySelector(`.width-error`).innerHTML = "";
  }
});

height.addEventListener("blur", () => {
  let err = "";
  if (height.value.length === 0) {
    err = "Height is empty";
  } else if (Number(height.value) <= 0) {
    err = "Invalid height value";
  }
  if (err) {
    document.querySelector(
      `.height-error`
    ).innerHTML = `<p class='error'>${err}</p>`;
  } else {
    document.querySelector(`.height-error`).innerHTML = "";
  }
});

length.addEventListener("blur", () => {
  let err = "";
  if (length.value.length === 0) {
    err = "Length is empty";
  } else if (Number(length.value) <= 0) {
    err = "Invalid length value";
  }
  if (err) {
    document.querySelector(
      `.length-error`
    ).innerHTML = `<p class='error'>${err}</p>`;
  } else {
    document.querySelector(`.length-error`).innerHTML = "";
  }
});

color.addEventListener("blur", () => {
  let err = "";
  if (color.value.length === 0) {
    err = "Color is empty";
  }
  if (err) {
    document.querySelector(
      `.color-error`
    ).innerHTML = `<p class='error'>${err}</p>`;
  } else {
    document.querySelector(`.color-error`).innerHTML = "";
  }
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
