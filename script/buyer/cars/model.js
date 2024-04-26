const state = {
  imageIndexState: new Array(
    document.querySelectorAll(".left-arrow-icon").length
  ).fill(0),
  carPage: 0,
  filterOption: {
    brand: [],
    transmission: "",
    color: [],
    fuelType: "",
  },
  isOrderedAsc: false,
  canUpdateCarPage: true,
};

export const getState = () => state;
export const getCarPage = () => state.carPage;
export const getImageIndex = () => state.imageIndexState;
export const setImageIndex = (newImageIndex) => {
  state.imageIndexState = newImageIndex;
};
export const getFilterOptions = () => state.filterOption;

export const setOrder = async (order) => {
  state.isOrderedAsc = order === "lth";
  state.carPage = 0;
  const carList = await findCars(
    state.filterOption,
    state.carPage,
    state.isOrderedAsc
  );
  state.imageIndexState = new Array(carList.length).fill(0);

  return carList;
};
export const setFilterOptions = async (type, value) => {
  state.canUpdateCarPage = true;
  state.filterOption[type] = value;
  state.carPage = 0;
  const carList = await findCars(
    state.filterOption,
    state.carPage,
    state.isOrderedAsc
  );
  state.imageIndexState = new Array(carList.length).fill(0);
  return carList;
};

export const updatePageNumber = async () => {
  if (!state.canUpdateCarPage) return [];
  state.carPage++;
  const carList = await findCars(
    state.filterOption,
    state.carPage,
    state.isOrderedAsc
  );
  state.imageIndexState = [
    ...state.imageIndexState,
    ...new Array(carList.length).fill(0),
  ];
  return carList;
};

const findCars = async (filterOptions, carPage, isOrderedAsc) => {
  const { brand, transmission, color, fuelType } = filterOptions;
  const url = new URL("http://localhost/onlycars/api/cars.php");
  if (brand.length > 0) {
    url.searchParams.append("brand", brand.join(","));
  }
  if (transmission) {
    url.searchParams.append("transmission", transmission);
  }

  if (color.length > 0) {
    url.searchParams.append(
      "color",
      color.map((item) => item.toUpperCase()).join(",")
    );
  }

  if (fuelType) {
    url.searchParams.append("fuel", fuelType);
  }

  url.searchParams.append("page", carPage);
  url.searchParams.append("order", isOrderedAsc ? "ASC" : "DESC");

  try {
    const response = await fetch(url.toString());
    const json = await response.json();
    if (json.length === 0) {
      state.canUpdateCarPage = false;
    } else {
      state.canUpdateCarPage = true;
    }
    return json;
  } catch (error) {
    return null;
  }
};
