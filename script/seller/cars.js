let leftArrowIcon = document.querySelectorAll(`.left-arrow-icon`);
let rightArrowIcon = document.querySelectorAll(`.right-arrow-icon`);

const state = {
  imageIndexState: new Array(
    document.querySelectorAll(".left-arrow-icon").length
  ).fill(0),
};

const navigateImagesToTheLeft = (el) => {
  console.log("executed");
  const index = el.dataset.index;
  if (state.imageIndexState[index] == 0) return;
  console.log("Before", state.imageIndexState[index]);
  document
    .querySelector(`#image-${index}-${state.imageIndexState[index]}`)
    .classList.remove("car-image-active");
  state.imageIndexState[index]--;
  console.log("AFTER", state.imageIndexState[index]);
  document
    .querySelector(`#image-${index}-${state.imageIndexState[index]}`)
    .classList.add("car-image-active");
};

const navigateImagesToTheRight = (el) => {
  console.log("executed");
  const index = el.dataset.index;
  const numOfImages = document.querySelectorAll(`.car-image-${index}`).length;
  if (state.imageIndexState[index] == numOfImages - 1) {
    return;
  }
  console.log("Before", state.imageIndexState[index]);
  document
    .querySelector(`#image-${index}-${state.imageIndexState[index]}`)
    .classList.remove("car-image-active");
  state.imageIndexState[index]++;
  console.log("AFTER", state.imageIndexState[index]);

  document
    .querySelector(`#image-${index}-${state.imageIndexState[index]}`)
    .classList.add("car-image-active");
};

const createImageNavigationEventListeners = () => {
  leftArrowIcon.forEach((el) =>
    el.addEventListener("click", navigateImagesToTheLeft.bind(null, el))
  );
  rightArrowIcon.forEach((el) =>
    el.addEventListener("click", navigateImagesToTheRight.bind(null, el))
  );
};

createImageNavigationEventListeners();
