export const renderCarList = (data, carPage) => {
  const carListElement = document.querySelector(`.car-list`);
  let htmlStr = "";
  // prettier-ignore
  data.forEach((carItem,index) => {
    let carIndex =  carPage* 10 + index;
    htmlStr += `
        <div class='car-container'>
            <div class='car-details'>
                  <div class='car-brand-details'>
                    <p class='car-brand'>${carItem.brand_name.toUpperCase()}</p>
                    <img src=${carItem.brand_logo}  width='40px' alt=${carItem.brand_name}>
                </div>
                <p class='car-price'>RM ${carItem.price}</p>
            </div>
            <div class='car-minor-container'>
              <div class='car-minor-details'>
                <p>${carItem.model_name}</p>
                <p>${carItem.mileage} km</p>
            </div>
            <div class='car-minor-details'>
                <p>${carItem.doors} Doors</p>
                <p>${carItem.seat} Seat</p>
            </div>
          </div>
          <div class='car-images-container'>
            <svg xmlns="http://www.w3.org/2000/svg" class="left-arrow-icon" data-index=${carIndex}  xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="30px" width="30px" version="1.1" id="Layer_1" viewBox="0 0 330 330" xml:space="preserve">
              <path id="XMLID_6_" d="M165,0C74.019,0,0,74.019,0,165s74.019,165,165,165s165-74.019,165-165S255.981,0,165,0z M205.606,234.394  c5.858,5.857,5.858,15.355,0,21.213C202.678,258.535,198.839,260,195,260s-7.678-1.464-10.606-4.394l-80-79.998  c-2.813-2.813-4.394-6.628-4.394-10.606c0-3.978,1.58-7.794,4.394-10.607l80-80.002c5.857-5.858,15.355-5.858,21.213,0  c5.858,5.857,5.858,15.355,0,21.213l-69.393,69.396L205.606,234.394z"/></svg>
              <svg fill="#000000" height="30px" width="30px" class="right-arrow-icon" data-index=${carIndex}  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330 330" xml:space="preserve">
              <path id="XMLID_2_" d="M165,0C74.019,0,0,74.019,0,165s74.019,165,165,165s165-74.019,165-165S255.981,0,165,0z M225.606,175.605  l-80,80.002C142.678,258.535,138.839,260,135,260s-7.678-1.464-10.606-4.394c-5.858-5.857-5.858-15.355,0-21.213l69.393-69.396  l-69.393-69.392c-5.858-5.857-5.858-15.355,0-21.213c5.857-5.858,15.355-5.858,21.213,0l80,79.998  c2.814,2.813,4.394,6.628,4.394,10.606C230,168.976,228.42,172.792,225.606,175.605z"/>
            </svg>`
            for(let j=0; j<carItem.image_urls.length; j++){
              if(j===0){
                htmlStr += `<img loading='lazy' width='550px' height='420px' class='car-image car-image-active car-image-${carIndex}'src='${carItem.image_urls[j]}' alt='${carItem.model_name}' id='image-${carIndex}-${j}' data-image-index='${index}'>`
              }else {
                htmlStr += `<img loading='lazy' width='550px' height='420px' class='car-image car-image-${carIndex}'src='${carItem.image_urls[j]}' alt='${carItem.model_name}' id='image-${carIndex}-${j}' data-image-index='${j}'>`

              }
            }

            htmlStr += `</div>
              <a href='/onlycars/buyer/id.php?id=${carItem.car_id}' class='view-car-btn'>View Details</a>
            </div>`

          });
  carListElement.insertAdjacentHTML("beforeend", htmlStr);
};
