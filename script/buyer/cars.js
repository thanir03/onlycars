/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./script/buyer/cars/controller.js":
/*!*****************************************!*\
  !*** ./script/buyer/cars/controller.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _model__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./model */ \"./script/buyer/cars/model.js\");\n/* harmony import */ var _view__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./view */ \"./script/buyer/cars/view.js\");\n\r\n\r\n\r\n\r\nlet leftArrowIcon = document.querySelectorAll(`.left-arrow-icon`);\r\nlet rightArrowIcon = document.querySelectorAll(`.right-arrow-icon`);\r\n// Filter HTML ELEMENTS\r\nconst lastElement = document.querySelector(\".bottom\");\r\nconst brandCheckboxInputElements = document.querySelectorAll(`.brand-checkbox`);\r\nconst transmissionContainer = document.querySelector(\r\n  `.transmission-filter-container`\r\n);\r\nconst fuelRadioContainer = document.querySelector(`.fuel-filter-container`);\r\nconst colorCheckbox = document.querySelectorAll(`.color-label`);\r\nconst priceOrderDropDown = document.querySelector(`#price-select`);\r\n\r\nconsole.log(priceOrderDropDown);\r\npriceOrderDropDown.addEventListener(\"change\", async (event) => {\r\n  const carList = await (0,_model__WEBPACK_IMPORTED_MODULE_0__.setOrder)(event.target.value);\r\n  document.querySelector(\".car-list\").innerHTML = \"\";\r\n  rerender(carList);\r\n});\r\n\r\nbrandCheckboxInputElements.forEach((el) => {\r\n  el.addEventListener(\"click\", async () => {\r\n    const brand = el.dataset.brand;\r\n    let carList = [];\r\n    if (el.checked) {\r\n      carList = await (0,_model__WEBPACK_IMPORTED_MODULE_0__.setFilterOptions)(\"brand\", [\r\n        ...(0,_model__WEBPACK_IMPORTED_MODULE_0__.getFilterOptions)().brand,\r\n        brand,\r\n      ]);\r\n    } else {\r\n      el.checked = false;\r\n      carList = await (0,_model__WEBPACK_IMPORTED_MODULE_0__.setFilterOptions)(\r\n        \"brand\",\r\n        (0,_model__WEBPACK_IMPORTED_MODULE_0__.getFilterOptions)().brand.filter((item) => item != brand)\r\n      );\r\n    }\r\n    document.querySelector(\".car-list\").innerHTML = \"\";\r\n    rerender(carList);\r\n  });\r\n});\r\n\r\ntransmissionContainer.addEventListener(\"click\", async (event) => {\r\n  if (!event.target.classList.value.split(\" \").includes(\"transmission-radio\")) {\r\n    // Not a radio button\r\n    return;\r\n  }\r\n\r\n  const carList = await (0,_model__WEBPACK_IMPORTED_MODULE_0__.setFilterOptions)(\r\n    \"transmission\",\r\n    event.target.dataset.transmission\r\n  );\r\n  document.querySelector(\".car-list\").innerHTML = \"\";\r\n  rerender(carList);\r\n});\r\n\r\nfuelRadioContainer.addEventListener(\"click\", async (event) => {\r\n  if (!event.target.classList.value.split(\" \").includes(\"fuel-radio\")) {\r\n    // Not a radio button\r\n    return;\r\n  }\r\n  if (event.target.name === \"petrol\") {\r\n    document.querySelector(`#diesel`).checked = false;\r\n  } else {\r\n    document.querySelector(`#petrol`).checked = false;\r\n  }\r\n  const carList = await (0,_model__WEBPACK_IMPORTED_MODULE_0__.setFilterOptions)(\"fuelType\", event.target.name);\r\n  document.querySelector(\".car-list\").innerHTML = \"\";\r\n  rerender(carList);\r\n});\r\n\r\ncolorCheckbox.forEach((el) =>\r\n  el.addEventListener(\"click\", async (event) => {\r\n    const colorSelected = event.target.dataset.color;\r\n    const checkbox = document.querySelector(`#color-checkbox-${colorSelected}`);\r\n    const selectedChecbox = document.querySelector(\r\n      `#color-selected-${colorSelected}`\r\n    );\r\n    let carList = [];\r\n    if (checkbox.checked) {\r\n      carList = await (0,_model__WEBPACK_IMPORTED_MODULE_0__.setFilterOptions)(\r\n        \"color\",\r\n        (0,_model__WEBPACK_IMPORTED_MODULE_0__.getFilterOptions)().color.filter((item) => item != colorSelected)\r\n      );\r\n      checkbox.checked = false;\r\n      selectedChecbox.style.border = \"3px solid transparent\";\r\n    } else {\r\n      carList = await (0,_model__WEBPACK_IMPORTED_MODULE_0__.setFilterOptions)(\"color\", [\r\n        ...(0,_model__WEBPACK_IMPORTED_MODULE_0__.getFilterOptions)().color,\r\n        colorSelected,\r\n      ]);\r\n      checkbox.checked = true;\r\n      selectedChecbox.style.border = \"3px solid #3865e0\";\r\n    }\r\n    document.querySelector(\".car-list\").innerHTML = \"\";\r\n    rerender(carList);\r\n  })\r\n);\r\n\r\nconst createImageNavigationEventListeners = () => {\r\n  leftArrowIcon.forEach((el) =>\r\n    el.addEventListener(\"click\", navigateImagesToTheLeft.bind(null, el))\r\n  );\r\n  rightArrowIcon.forEach((el) =>\r\n    el.addEventListener(\"click\", navigateImagesToTheRight.bind(null, el))\r\n  );\r\n};\r\nconst removeEventListeners = () => {\r\n  leftArrowIcon.forEach((el) =>\r\n    el.removeEventListener(\"click\", navigateImagesToTheLeft.bind(null, el))\r\n  );\r\n  rightArrowIcon.forEach((el) =>\r\n    el.removeEventListener(\"click\", navigateImagesToTheRight.bind(null, el))\r\n  );\r\n};\r\n\r\nconst navigateImagesToTheLeft = (el) => {\r\n  const index = el.dataset.index;\r\n  const imagesIndexArr = (0,_model__WEBPACK_IMPORTED_MODULE_0__.getImageIndex)();\r\n  if (imagesIndexArr[index] == 0) return;\r\n  document\r\n    .querySelector(`#image-${index}-${imagesIndexArr[index]}`)\r\n    .classList.remove(\"car-image-active\");\r\n  imagesIndexArr[index]--;\r\n  (0,_model__WEBPACK_IMPORTED_MODULE_0__.setImageIndex)(imagesIndexArr);\r\n  document\r\n    .querySelector(`#image-${index}-${imagesIndexArr[index]}`)\r\n    .classList.add(\"car-image-active\");\r\n};\r\n\r\nconst navigateImagesToTheRight = (el) => {\r\n  const index = el.dataset.index;\r\n  const imagesIndexArr = (0,_model__WEBPACK_IMPORTED_MODULE_0__.getImageIndex)();\r\n  const numOfImages = document.querySelectorAll(`.car-image-${index}`).length;\r\n  if (imagesIndexArr[index] == numOfImages - 1) {\r\n    return;\r\n  }\r\n\r\n  document\r\n    .querySelector(`#image-${index}-${imagesIndexArr[index]}`)\r\n    .classList.remove(\"car-image-active\");\r\n  imagesIndexArr[index]++;\r\n  (0,_model__WEBPACK_IMPORTED_MODULE_0__.setImageIndex)(imagesIndexArr);\r\n  document\r\n    .querySelector(`#image-${index}-${imagesIndexArr[index]}`)\r\n    .classList.add(\"car-image-active\");\r\n};\r\n\r\nconst observeInfiniteScrolling = () => {\r\n  const infiniteScrolling = (entries) => {\r\n    entries.forEach(async (entry) => {\r\n      if (entry.isIntersecting) {\r\n        const carList = await (0,_model__WEBPACK_IMPORTED_MODULE_0__.updatePageNumber)();\r\n        rerender(carList);\r\n      }\r\n    });\r\n  };\r\n\r\n  const options = {\r\n    root: null,\r\n    rootMargin: \"1000px\",\r\n  };\r\n  const intersectionObserver = new IntersectionObserver(\r\n    infiniteScrolling,\r\n    options\r\n  );\r\n  intersectionObserver.observe(lastElement);\r\n};\r\n\r\nwindow.onload = () => {\r\n  if (lastElement) {\r\n    observeInfiniteScrolling();\r\n  }\r\n  createImageNavigationEventListeners();\r\n};\r\n\r\nconst rerender = (carList) => {\r\n  (0,_view__WEBPACK_IMPORTED_MODULE_1__.renderCarList)(carList, (0,_model__WEBPACK_IMPORTED_MODULE_0__.getCarPage)());\r\n  removeEventListeners();\r\n  leftArrowIcon = document.querySelectorAll(`.left-arrow-icon`);\r\n  rightArrowIcon = document.querySelectorAll(`.right-arrow-icon`);\r\n  createImageNavigationEventListeners();\r\n};\r\n\n\n//# sourceURL=webpack:///./script/buyer/cars/controller.js?");

/***/ }),

/***/ "./script/buyer/cars/model.js":
/*!************************************!*\
  !*** ./script/buyer/cars/model.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   getCarPage: () => (/* binding */ getCarPage),\n/* harmony export */   getFilterOptions: () => (/* binding */ getFilterOptions),\n/* harmony export */   getImageIndex: () => (/* binding */ getImageIndex),\n/* harmony export */   getState: () => (/* binding */ getState),\n/* harmony export */   setFilterOptions: () => (/* binding */ setFilterOptions),\n/* harmony export */   setImageIndex: () => (/* binding */ setImageIndex),\n/* harmony export */   setOrder: () => (/* binding */ setOrder),\n/* harmony export */   updatePageNumber: () => (/* binding */ updatePageNumber)\n/* harmony export */ });\nconst state = {\r\n  imageIndexState: new Array(\r\n    document.querySelectorAll(\".left-arrow-icon\").length\r\n  ).fill(0),\r\n  carPage: 0,\r\n  filterOption: {\r\n    brand: [],\r\n    transmission: \"\",\r\n    color: [],\r\n    fuelType: \"\",\r\n  },\r\n  isOrderedAsc: false,\r\n  canUpdateCarPage: true,\r\n};\r\n\r\nconst getState = () => state;\r\nconst getCarPage = () => state.carPage;\r\nconst getImageIndex = () => state.imageIndexState;\r\nconst setImageIndex = (newImageIndex) => {\r\n  state.imageIndexState = newImageIndex;\r\n};\r\nconst getFilterOptions = () => state.filterOption;\r\n\r\nconst setOrder = async (order) => {\r\n  state.isOrderedAsc = order === \"lth\";\r\n  state.carPage = 0;\r\n  const carList = await findCars(\r\n    state.filterOption,\r\n    state.carPage,\r\n    state.isOrderedAsc\r\n  );\r\n  state.imageIndexState = new Array(carList.length).fill(0);\r\n\r\n  return carList;\r\n};\r\nconst setFilterOptions = async (type, value) => {\r\n  state.canUpdateCarPage = true;\r\n  state.filterOption[type] = value;\r\n  state.carPage = 0;\r\n  const carList = await findCars(\r\n    state.filterOption,\r\n    state.carPage,\r\n    state.isOrderedAsc\r\n  );\r\n  state.imageIndexState = new Array(carList.length).fill(0);\r\n  return carList;\r\n};\r\n\r\nconst updatePageNumber = async () => {\r\n  if (!state.canUpdateCarPage) return [];\r\n  state.carPage++;\r\n  const carList = await findCars(\r\n    state.filterOption,\r\n    state.carPage,\r\n    state.isOrderedAsc\r\n  );\r\n  state.imageIndexState = [\r\n    ...state.imageIndexState,\r\n    ...new Array(carList.length).fill(0),\r\n  ];\r\n  return carList;\r\n};\r\n\r\nconst findCars = async (filterOptions, carPage, isOrderedAsc) => {\r\n  const { brand, transmission, color, fuelType } = filterOptions;\r\n  const url = new URL(\"http://localhost/onlycars/api/cars.php\");\r\n  if (brand.length > 0) {\r\n    url.searchParams.append(\"brand\", brand.join(\",\"));\r\n  }\r\n  if (transmission) {\r\n    url.searchParams.append(\"transmission\", transmission);\r\n  }\r\n\r\n  if (color.length > 0) {\r\n    url.searchParams.append(\r\n      \"color\",\r\n      color.map((item) => item.toUpperCase()).join(\",\")\r\n    );\r\n  }\r\n\r\n  if (fuelType) {\r\n    url.searchParams.append(\"fuel\", fuelType);\r\n  }\r\n\r\n  url.searchParams.append(\"page\", carPage);\r\n  url.searchParams.append(\"order\", isOrderedAsc ? \"ASC\" : \"DESC\");\r\n\r\n  try {\r\n    const response = await fetch(url.toString());\r\n    const json = await response.json();\r\n    if (json.length === 0) {\r\n      state.canUpdateCarPage = false;\r\n    } else {\r\n      state.canUpdateCarPage = true;\r\n    }\r\n    return json;\r\n  } catch (error) {\r\n    return null;\r\n  }\r\n};\r\n\n\n//# sourceURL=webpack:///./script/buyer/cars/model.js?");

/***/ }),

/***/ "./script/buyer/cars/view.js":
/*!***********************************!*\
  !*** ./script/buyer/cars/view.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   renderCarList: () => (/* binding */ renderCarList)\n/* harmony export */ });\nconst renderCarList = (data, carPage) => {\r\n  const carListElement = document.querySelector(`.car-list`);\r\n  let htmlStr = \"\";\r\n  // prettier-ignore\r\n  data.forEach((carItem,index) => {\r\n    let carIndex =  carPage* 10 + index;\r\n    htmlStr += `\r\n        <div class='car-container'>\r\n            <div class='car-details'>\r\n                  <div class='car-brand-details'>\r\n                    <p class='car-brand'>${carItem.brand_name.toUpperCase()}</p>\r\n                    <img src=${carItem.brand_logo}  width='40px' alt=${carItem.brand_name}>\r\n                </div>\r\n                <p class='car-price'>RM ${carItem.price}</p>\r\n            </div>\r\n            <div class='car-minor-container'>\r\n              <div class='car-minor-details'>\r\n                <p>${carItem.model_name}</p>\r\n                <p>${carItem.mileage} km</p>\r\n            </div>\r\n            <div class='car-minor-details'>\r\n                <p>${carItem.doors} Doors</p>\r\n                <p>${carItem.seat} Seat</p>\r\n            </div>\r\n          </div>\r\n          <div class='car-images-container'>\r\n            <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"left-arrow-icon\" data-index=${carIndex}  xmlns:xlink=\"http://www.w3.org/1999/xlink\" fill=\"#000000\" height=\"30px\" width=\"30px\" version=\"1.1\" id=\"Layer_1\" viewBox=\"0 0 330 330\" xml:space=\"preserve\">\r\n              <path id=\"XMLID_6_\" d=\"M165,0C74.019,0,0,74.019,0,165s74.019,165,165,165s165-74.019,165-165S255.981,0,165,0z M205.606,234.394  c5.858,5.857,5.858,15.355,0,21.213C202.678,258.535,198.839,260,195,260s-7.678-1.464-10.606-4.394l-80-79.998  c-2.813-2.813-4.394-6.628-4.394-10.606c0-3.978,1.58-7.794,4.394-10.607l80-80.002c5.857-5.858,15.355-5.858,21.213,0  c5.858,5.857,5.858,15.355,0,21.213l-69.393,69.396L205.606,234.394z\"/></svg>\r\n              <svg fill=\"#000000\" height=\"30px\" width=\"30px\" class=\"right-arrow-icon\" data-index=${carIndex}  version=\"1.1\" id=\"Layer_1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" viewBox=\"0 0 330 330\" xml:space=\"preserve\">\r\n              <path id=\"XMLID_2_\" d=\"M165,0C74.019,0,0,74.019,0,165s74.019,165,165,165s165-74.019,165-165S255.981,0,165,0z M225.606,175.605  l-80,80.002C142.678,258.535,138.839,260,135,260s-7.678-1.464-10.606-4.394c-5.858-5.857-5.858-15.355,0-21.213l69.393-69.396  l-69.393-69.392c-5.858-5.857-5.858-15.355,0-21.213c5.857-5.858,15.355-5.858,21.213,0l80,79.998  c2.814,2.813,4.394,6.628,4.394,10.606C230,168.976,228.42,172.792,225.606,175.605z\"/>\r\n            </svg>`\r\n            for(let j=0; j<carItem.image_urls.length; j++){\r\n              if(j===0){\r\n                htmlStr += `<img loading='lazy' width='550px' height='420px' class='car-image car-image-active car-image-${carIndex}'src='${carItem.image_urls[j]}' alt='${carItem.model_name}' id='image-${carIndex}-${j}' data-image-index='${index}'>`\r\n              }else {\r\n                htmlStr += `<img loading='lazy' width='550px' height='420px' class='car-image car-image-${carIndex}'src='${carItem.image_urls[j]}' alt='${carItem.model_name}' id='image-${carIndex}-${j}' data-image-index='${j}'>`\r\n\r\n              }\r\n            }\r\n\r\n            htmlStr += `</div>\r\n              <a href='/onlycars/buyer/id.php?id=${carItem.car_id}' class='view-car-btn'>View Details</a>\r\n            </div>`\r\n\r\n          });\r\n  carListElement.insertAdjacentHTML(\"beforeend\", htmlStr);\r\n};\r\n\n\n//# sourceURL=webpack:///./script/buyer/cars/view.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./script/buyer/cars/controller.js");
/******/ 	
/******/ })()
;