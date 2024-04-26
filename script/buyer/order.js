const deleteBtn = document.querySelectorAll(`.delete-container`);

deleteBtn.forEach((btn) =>
  btn.addEventListener("click", async () => {
    const bookingId = btn.dataset.bookingId;
    const res = await fetch(
      `http://localhost/onlycars/api/book.php?bookingId=${bookingId}`,
      {
        method: "DELETE",
      }
    );
    const json = await res.json();
    if (json.status === "success") {
      document
        .querySelector(
          `.booking-item-container[data-booking-id="${bookingId}"]`
        )
        .remove();

      if (document.querySelectorAll(".booking-item-container").length == 0) {
        document.querySelector(".no-items").classList.add("show");
      }
    }
  })
);
