"use strict";

let burgerMenu = document.querySelector(".burger-header-btn-container");

burgerMenu.addEventListener("click", (e) => {
  let burgerMenuLines = document.querySelectorAll(".burger-header-btn-container>span");
  for (let i = 0; i < burgerMenuLines.length; i++) {
    if (burgerMenuLines[i].classList.value === "burger-header-btn") {
      burgerMenuLines[i].classList.remove("burger-header-btn");
      burgerMenuLines[i].classList.add("burger-header-btn-open");
      let header = document.querySelectorAll("header>a");
      if (burgerMenuLines.length - 1 === i) {
        console.log(header);
        for (let p = 0; p < header.length; p++) {
          header[p].classList.remove("hidden");
        }
      }
    } else if (burgerMenuLines[i].classList.value === "burger-header-btn-close") {
      burgerMenuLines[i].classList.remove("burger-header-btn-close");
      burgerMenuLines[i].classList.add("burger-header-btn-open");
      let header = document.querySelectorAll("header>a");
      if (burgerMenuLines.length - 1 === i) {
        console.log(header);
        for (let p = 0; p < header.length; p++) {
          header[p].classList.remove("hidden");
        }
      }
    } else if (burgerMenuLines[i].classList.value === "burger-header-btn-open") {
      burgerMenuLines[i].classList.remove("burger-header-btn-open");
      burgerMenuLines[i].classList.add("burger-header-btn-close");
      let header = document.querySelectorAll("header>a");
      if (burgerMenuLines.length - 1 === i) {
        console.log(header);
        for (let p = 0; p < header.length; p++) {
          header[p].classList.add("hidden");
        }
      }
    }
  }
})

let header = document.querySelectorAll("header>a");

if (window.innerWidth > 767 & header[0].classList.value.includes("hidden")) {
  for (let p = 0; p < header.length; p++) {
    header[p].classList.remove("hidden");
  }
}

let burgerMenuLines = document.querySelectorAll(".burger-header-btn-container>span");
window.addEventListener("resize", (e) => {
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
})
