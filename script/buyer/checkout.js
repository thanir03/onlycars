const payBtn = document.querySelector(`.pay-now`);

payBtn.addEventListener("click", async () => {
  console.log("clicked");
  const res = await fetch("http://localhost/onlycars/api/checkout", {
    method: "POST",
    body: JSON.stringify({
      bookingId: payBtn.dataset.bookingId,
    }),
  });

  const data = await res.json();
  if (data.status === "success") {
    window.location.href = data.url;
  }
});
