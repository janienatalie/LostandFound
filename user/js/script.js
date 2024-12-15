const dropdown = document.getElementById("dropdown");

dropdown.addEventListener("change", function () {
  if (dropdown.value) {
    dropdown.style.color = "#763996";
  }
});
