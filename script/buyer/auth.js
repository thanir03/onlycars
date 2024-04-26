const signupForm = document.querySelector(`.sign-up`);
const loginForm = document.querySelector(`.sign-in`);

const loginEmail = document.querySelector(`#email`);
const loginPassword = document.querySelector(`#password`);

const signupEmail = document.querySelector(`#emailSignup`);
const signupPassword = document.querySelector(`#passwordSignup`);

const modal = document.querySelector(`.modal`);
signupForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const email = signupEmail.value;
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!regex.test(email)) {
    document.querySelector(`.invalid-email-login`).innerHTML =
      "<p>Invalid email</p>";
  } else {
    document.querySelector(`.invalid-email-login`).innerHTML = "";
  }
  const password = signupPassword.value;

  if (password.length < 4) {
    document.querySelector(`.invalid-password-login`).innerHTML =
      "<p>Invalid password</p>";
  } else {
    document.querySelector(`.invalid-password-login`).innerHTML = "";
  }
  if (regex.test(email) && password.length >= 4) {
    const res = await fetch(`http://localhost/onlycars/api/user.php`, {
      method: "POST",
      body: JSON.stringify({
        email,
        password,
      }),
    });
    const json = await res.json();
    if (json.status === "success") {
      window.location.href = "http://localhost/onlycars/buyer/cars.php";
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
      `http://localhost/onlycars/api/user.php?email=${email}&password=${password}`,
      {
        method: "GET",
      }
    );
    const json = await res.json();
    if (json.status === "success") {
      window.location.href = "http://localhost/onlycars/buyer/cars.php";
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
