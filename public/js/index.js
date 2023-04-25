const headerMobileButton = document.querySelector(".headerMenuXSIcon");
const headerMobileList = document.querySelector(".headerMenuXSList");

headerMobileButton.addEventListener("click", () => {
  headerMobileList.classList.toggle("show");
});
