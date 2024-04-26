const signupForm = document.querySelector(`.sign-up`);
const loginForm = document.querySelector(`.sign-in`);

const loginEmail = document.querySelector(`#email`);
const loginPassword = document.querySelector(`#password`);

const signupEmail = document.querySelector(`#emailSignup`);
const signupPassword = document.querySelector(`#passwordSignup`);
const signupPhone = document.querySelector("#phoneSignup");
const modal = document.querySelector(`.modal`);

console.log("js");
signupForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const email = signupEmail.value;
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!regex.test(email)) {
    document.querySelector(`.invalid-email-signup`).innerHTML =
      "<p>Invalid email</p>";
  } else {
    document.querySelector(`.invalid-email-signup`).innerHTML = "";
  }
  const password = signupPassword.value;

  if (password.length < 4) {
    document.querySelector(`.invalid-password-signup`).innerHTML =
      "<p>Invalid password</p>";
  } else {
    document.querySelector(`.invalid-password-signup`).innerHTML = "";
  }
  const phone = signupPhone.value;
  if (phone.length < 10) {
    document.querySelector(`.invalid-phone-signup`).innerHTML =
      "<p>Invalid phone</p>";
  } else {
    document.querySelector(`.invalid-phone-signup`).innerHTML = "";
  }
  if (regex.test(email) && password.length >= 4 && phone.length >= 10) {
    const res = await fetch(`http://localhost/onlycars/api/seller.php`, {
      method: "POST",
      body: JSON.stringify({
        email,
        password,
        phone,
      }),
    });
    const json = await res.json();
    if (json.status === "success") {
      window.location.href = "http://localhost/onlycars/seller/cars.php";
    } else {
      modal.innerHTML = `<p>${json.message ?? "ERROR Occurred"} </p>`;
      modal.style.display = "flex";
      modal.style.backgroundColor = "red";
      setTimeout(() => {
        modal.style.display = "none";
      }, 3000);
    }
  }
});

loginForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const email = loginEmail.value;
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!regex.test(email)) {
    document.querySelector(`.invalid-email-login`).innerHTML =
      "<p>Invalid email</p>";
  } else {
    document.querySelector(`.invalid-email-login`).innerHTML = "";
  }
  const password = loginPassword.value;

  if (password.length < 4) {
    document.querySelector(`.invalid-password-login`).innerHTML =
      "<p>Invalid password</p>";
  } else {
    document.querySelector(`.invalid-password-login`).innerHTML = "";
  }
  if (regex.test(email) && password.length >= 4) {
    const res = await fetch(
      `http://localhost/onlycars/api/seller.php?email=${email}&password=${password}`,
      {
        method: "GET",
      }
    );
    const json = await res.json();
    if (json.status === "success") {
      window.location.href = "http://localhost/onlycars/seller/cars.php";
    } else {
      modal.innerHTML = `<p>${json.message ?? "ERROR Occurred"} </p>`;
      modal.style.display = "flex";
      modal.style.backgroundColor = "red";
      setTimeout(() => {
        modal.style.display = "none";
      }, 3000);
    }
  } else {
  }
});
