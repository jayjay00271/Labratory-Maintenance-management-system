document.addEventListener("DOMContentLoaded", () => {
  const images = [
    "comlabpic1.jpeg",
    "comlabpic2.jpg",
    "comlab3.jpg"
  ];

  const bgSlider = document.querySelector(".background-slider");

  images.forEach((imgSrc, index) => {
    const div = document.createElement("div");
    div.classList.add("background-image");
    if(index===0) div.classList.add("active");
    div.style.backgroundImage = `url(${imgSrc})`;
    bgSlider.appendChild(div);
  });

  let current = 0;
  const slides = document.querySelectorAll(".background-image");

  setInterval(() => {
    slides[current].classList.remove("active");
    current = (current+1) % slides.length;
    slides[current].classList.add("active");
  }, 3000);
});
