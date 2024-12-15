const dropdown = document.getElementById("dropdown");
const dropdowncampus = document.getElementById("dropdowncampus");

dropdown.addEventListener("change", function () {
  if (dropdown.value) {
    dropdown.style.color = "#763996";
  }
});

dropdowncampus.addEventListener("change", function () {
  if (dropdowncampus.value) {
    dropdowncampus.style.color = "#763996";
  }
});
