import {
  getCarPage,
  getFilterOptions,
  getImageIndex,
  setFilterOptions,
  setImageIndex,
  setOrder,
  updatePageNumber,
} from "./model";

import { renderCarList } from "./view";

let leftArrowIcon = document.querySelectorAll(`.left-arrow-icon`);
let rightArrowIcon = document.querySelectorAll(`.right-arrow-icon`);
// Filter HTML ELEMENTS
const lastElement = document.querySelector(".bottom");
const brandCheckboxInputElements = document.querySelectorAll(`.brand-checkbox`);
const transmissionContainer = document.querySelector(
  `.transmission-filter-container`
);
const fuelRadioContainer = document.querySelector(`.fuel-filter-container`);
const colorCheckbox = document.querySelectorAll(`.color-label`);
const priceOrderDropDown = document.querySelector(`#price-select`);

console.log(priceOrderDropDown);
priceOrderDropDown.addEventListener("change", async (event) => {
  const carList = await setOrder(event.target.value);
  document.querySelector(".car-list").innerHTML = "";
  rerender(carList);
});

brandCheckboxInputElements.forEach((el) => {
  el.addEventListener("click", async () => {
    const brand = el.dataset.brand;
    let carList = [];
    if (el.checked) {
      carList = await setFilterOptions("brand", [
        ...getFilterOptions().brand,
        brand,
      ]);
    } else {
      el.checked = false;
      carList = await setFilterOptions(
        "brand",
        getFilterOptions().brand.filter((item) => item != brand)
      );
    }
    document.querySelector(".car-list").innerHTML = "";
    rerender(carList);
  });
});

transmissionContainer.addEventListener("click", async (event) => {
  if (!event.target.classList.value.split(" ").includes("transmission-radio")) {
    // Not a radio button
    return;
  }

  const carList = await setFilterOptions(
    "transmission",
    event.target.dataset.transmission
  );
  document.querySelector(".car-list").innerHTML = "";
  rerender(carList);
});

fuelRadioContainer.addEventListener("click", async (event) => {
  if (!event.target.classList.value.split(" ").includes("fuel-radio")) {
    // Not a radio button
    return;
  }
  if (event.target.name === "petrol") {
    document.querySelector(`#diesel`).checked = false;
  } else {
    document.querySelector(`#petrol`).checked = false;
  }
  const carList = await setFilterOptions("fuelType", event.target.name);
  document.querySelector(".car-list").innerHTML = "";
  rerender(carList);
});

colorCheckbox.forEach((el) =>
  el.addEventListener("click", async (event) => {
    const colorSelected = event.target.dataset.color;
    const checkbox = document.querySelector(`#color-checkbox-${colorSelected}`);
    const selectedChecbox = document.querySelector(
      `#color-selected-${colorSelected}`
    );
    let carList = [];
    if (checkbox.checked) {
      carList = await setFilterOptions(
        "color",
        getFilterOptions().color.filter((item) => item != colorSelected)
      );
      checkbox.checked = false;
      selectedChecbox.style.border = "3px solid transparent";
    } else {
      carList = await setFilterOptions("color", [
        ...getFilterOptions().color,
        colorSelected,
      ]);
      checkbox.checked = true;
      selectedChecbox.style.border = "3px solid #3865e0";
    }
    document.querySelector(".car-list").innerHTML = "";
    rerender(carList);
  })
);

const createImageNavigationEventListeners = () => {
  leftArrowIcon.forEach((el) =>
    el.addEventListener("click", navigateImagesToTheLeft.bind(null, el))
  );
  rightArrowIcon.forEach((el) =>
    el.addEventListener("click", navigateImagesToTheRight.bind(null, el))
  );
};
const removeEventListeners = () => {
  leftArrowIcon.forEach((el) =>
    el.removeEventListener("click", navigateImagesToTheLeft.bind(null, el))
  );
  rightArrowIcon.forEach((el) =>
    el.removeEventListener("click", navigateImagesToTheRight.bind(null, el))
  );
};

const navigateImagesToTheLeft = (el) => {
  const index = el.dataset.index;
  const imagesIndexArr = getImageIndex();
  if (imagesIndexArr[index] == 0) return;
  document
    .querySelector(`#image-${index}-${imagesIndexArr[index]}`)
    .classList.remove("car-image-active");
  imagesIndexArr[index]--;
  setImageIndex(imagesIndexArr);
  document
    .querySelector(`#image-${index}-${imagesIndexArr[index]}`)
    .classList.add("car-image-active");
};

const navigateImagesToTheRight = (el) => {
  const index = el.dataset.index;
  const imagesIndexArr = getImageIndex();
  const numOfImages = document.querySelectorAll(`.car-image-${index}`).length;
  if (imagesIndexArr[index] == numOfImages - 1) {
    return;
  }

  document
    .querySelector(`#image-${index}-${imagesIndexArr[index]}`)
    .classList.remove("car-image-active");
  imagesIndexArr[index]++;
  setImageIndex(imagesIndexArr);
  document
    .querySelector(`#image-${index}-${imagesIndexArr[index]}`)
    .classList.add("car-image-active");
};

const observeInfiniteScrolling = () => {
  const infiniteScrolling = (entries) => {
    entries.forEach(async (entry) => {
      if (entry.isIntersecting) {
        const carList = await updatePageNumber();
        rerender(carList);
      }
    });
  };

  const options = {
    root: null,
    rootMargin: "1000px",
  };
  const intersectionObserver = new IntersectionObserver(
    infiniteScrolling,
    options
  );
  intersectionObserver.observe(lastElement);
};

window.onload = () => {
  if (lastElement) {
    observeInfiniteScrolling();
  }
  createImageNavigationEventListeners();
};

const rerender = (carList) => {
  renderCarList(carList, getCarPage());
  removeEventListeners();
  leftArrowIcon = document.querySelectorAll(`.left-arrow-icon`);
  rightArrowIcon = document.querySelectorAll(`.right-arrow-icon`);
  createImageNavigationEventListeners();
};
