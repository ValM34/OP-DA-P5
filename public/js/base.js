"use strict";

let burgerMenu = document.querySelector(".burger-header-btn-container");

function changeClass(action1, action2, i, action3) {
  for (let i = 0; i < burgerMenuLines.length; i++) {
    burgerMenuLines[i].classList.remove(action1);
    burgerMenuLines[i].classList.add(action2);
    let header = document.querySelectorAll("header>a");
    if (burgerMenuLines.length - 1 === i) {
      action3;
    }
  }
}

burgerMenu.addEventListener("click", () => {
  let burgerMenuLines = document.querySelectorAll(".burger-header-btn-container>span");
  for (let i = 0; i < burgerMenuLines.length; i++) {
    if (burgerMenuLines[i].classList.value === "burger-header-btn") {
      changeClass(
        "burger-header-btn",
        "burger-header-btn-open",
        i,
        header.forEach(header => header.classList.remove("hidden"))
      );
    } else if (burgerMenuLines[i].classList.value === "burger-header-btn-close") {
      changeClass(
        "burger-header-btn-close",
        "burger-header-btn-open",
        i,
        header.forEach(header => header.classList.remove("hidden"))
      );
    } else if (burgerMenuLines[i].classList.value === "burger-header-btn-open") {
      changeClass(
        "burger-header-btn-open",
        "burger-header-btn-close",
        i,
        header.forEach(header => header.classList.add("hidden"))
      );
    }
  }
});

let header = document.querySelectorAll("header>a");

if ((window.innerWidth > 767) & header[0].classList.value.includes("hidden")) {
  for (let p = 0; p < header.length; p++) {
    header[p].classList.remove("hidden");
  }
}

let burgerMenuLines = document.querySelectorAll(".burger-header-btn-container>span");
window.addEventListener("resize", () => {
  if (window.innerWidth < 768 & header[0].classList.value.includes("hidden") === false) {
    for (let p = 0; p < header.length; p++) {
      header[p].classList.add("hidden");
    }
    for (let i = 0; i < burgerMenuLines.length; i++) {
      if (burgerMenuLines[i].classList.value === "burger-header-btn-open") {
        burgerMenuLines[i].classList.remove("burger-header-btn-open");
        burgerMenuLines[i].classList.add("burger-header-btn-close");
      }
    }
  } else if (window.innerWidth >= 768 & header[0].classList.value.includes("hidden") === true) {
    for (let p = 0; p < header.length; p++) {
      header[p].classList.remove("hidden");
    }
  }
});
