// Smooth scrolling for navigation links
document.querySelectorAll(".nav-link").forEach((link) => {
  link.addEventListener("click", function (e) {
    if (this.hash !== "") {
      e.preventDefault();
      const hash = this.hash;

      document.querySelector(hash).scrollIntoView({
        behavior: "smooth",
      });
    }
  });
});

// Form validation for the search bar
document.querySelector(".search-bar").addEventListener("submit", function (e) {
  const inputs = this.querySelectorAll("input");
  let isValid = true;

  inputs.forEach((input) => {
    if (!input.value) {
      input.classList.add("is-invalid");
      isValid = false;
    } else {
      input.classList.remove("is-invalid");
    }
  });

  if (!isValid) {
    e.preventDefault(); // Stop form submission if invalid
    alert("Please fill out all fields before searching.");
  }
});

// Display alert on clicking "Book Now" button
document.querySelectorAll(".btn-primary").forEach((button) => {
  button.addEventListener("click", function () {
    alert("This feature is under development. Stay tuned!");
  });
});

// Add scroll-to-top functionality
const scrollToTopBtn = document.createElement("button");
scrollToTopBtn.textContent = "↑";
scrollToTopBtn.className = "scroll-to-top";
scrollToTopBtn.style.cssText = `
  position: fixed;
  bottom: 20px;
  right: 20px;
  display: none;
  background: #00cdfe;
  color: white;
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  font-size: 1.5rem;
  cursor: pointer;
  z-index: 999;
`;
document.body.appendChild(scrollToTopBtn);

scrollToTopBtn.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

window.addEventListener("scroll", () => {
  if (window.scrollY > 300) {
    scrollToTopBtn.style.display = "block";
  } else {
    scrollToTopBtn.style.display = "none";
  }
});
