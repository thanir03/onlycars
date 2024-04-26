<nav class="nav-container">
  <a href="/onlycars">
    <h1 class="onlycars-title">Only<span class="onlycars-title-span">Cars</span></h1>
  </a>
  <div  class="nav-link-container">
    <a href="/onlycars/buyer/cars" id="buyer/cars-link" style="cursor: pointer;" class="nav-link">
      Buy Car
    </a>  
    <p class="nav-link">
      <a href="/onlycars/buyer/cart">Cart</a>
    </p>
    <p class="nav-link">
      <a href="/onlycars/buyer/orders">Orders</a>
    </p>
    <div>
      <?php  
      if($authDetails["isLoggedIn"]) {
        echo "<div class='auth'>";
         echo  sprintf("<p>%s</p>", $authDetails["email"]) ;
         echo "<button class='logout'>Logout</button>";  
         echo "</div>";
        } else { 
          echo "<a href='/onlycars/buyer/auth'>Sign In</a>";
        }  
      ?>
    </div>
  </div>

  <svg class="hamburger-icon"  xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 24 24" fill="none">
    <g id="SVGRepo_bgCarrier" stroke-width="0"/>
    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
    <g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M4 5C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H4ZM7 12C7 11.4477 7.44772 11 8 11H20C20.5523 11 21 11.4477 21 12C21 12.5523 20.5523 13 20 13H8C7.44772 13 7 12.5523 7 12ZM13 18C13 17.4477 13.4477 17 14 17H20C20.5523 17 21 17.4477 21 18C21 18.5523 20.5523 19 20 19H14C13.4477 19 13 18.5523 13 18Z" fill="#000000"/> </g>
  </svg>
</nav>
<script>
  const logoutBtn = document.querySelector(".logout")
  if(logoutBtn){
    logoutBtn.addEventListener("click", () => {
      console.log('executed');
      document.cookie = "userToken=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    
      window.location.href = "http://localhost/onlycars/buyer/cars";
    })
  }
</script>