"use strict";

// check all checkbox or uncheck all
function checkbox() {
  let selectAllCheckbox = document.querySelectorAll("input[name='checkboxPost']");
  let globalCheckbox = document.querySelector("#checkbox-all");

  globalCheckbox.addEventListener("change", function () {
    if (this.checked) {
      for (let i = 0; i < selectAllCheckbox.length; i++) {
        selectAllCheckbox[i].checked = true;
      }
    } else {
      for (let i = 0; i < selectAllCheckbox.length; i++) {
        selectAllCheckbox[i].checked = false;
      }
    }
  });
}

// Envoie les informations des articles séléctionnés au serveur pour effectuer l'action indiquée
function action(action, link) {

  let selectAllCheckbox = document.querySelectorAll("input[name='checkboxPost']");
  let arrayCheckbox = [];
  let selectPublishAllLink = document.getElementById(action);
  let idList = "";

  selectPublishAllLink.addEventListener("click", (e) => {
    e.preventDefault();

    selectAllCheckbox.forEach((checkbox) => {
      if (checkbox.checked === true) {
        let id_post = checkbox.id;
        arrayCheckbox.push(id_post.split("-")[1]);
      }
    });

    arrayCheckbox.forEach((checkbox) => {
      idList += "-" + checkbox;
    });
    if (arrayCheckbox[0]) {
      window.location.href = `${link}/${idList}`;
    }
  });
}

checkbox();
action("publishSelected", "touslesarticles/publishselected");
action("hideSelected", "touslesarticles/hideselected");
action("deleteAll", "touslesarticles/deleteselected");