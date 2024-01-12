const pagination = document.querySelector("#content .pagination");

if (pagination) {
  pagination.addEventListener("click", (event) => {
    event.preventDefault();

    if (
      event.target.matches(".page-link") ||
      event.target.parentElement.matches(".page-link")
    ) {
      const page =
        event.target.dataset.page || event.target.parentElement.dataset.page; // to work on children of page-link
      const url = new URL(window.location.href);

      let currentPage;
      if (page === "next" || page === "prev") {
        if (!url.searchParams.has("page")) {
          currentPage = 1;
        } else {
          currentPage = parseInt(url.searchParams.get("page")); // get current page
          if (currentPage === 0) currentPage = 1;
        }
        page === "next" ? ++currentPage : --currentPage;
      } else {
        currentPage = page;
      }

      const currentPageLink = pagination.querySelector(
        ".page-link[aria-current]"
      );
      let newPageLink = pagination.querySelector(
        `.page-link[data-page="${currentPage}"]`
      );
      if (!newPageLink) {
        currentPage = 2;
        newPageLink = pagination.querySelector(
          `.page-link[data-page="${currentPage}"]`
        );
      }
      currentPageLink.removeAttribute("aria-current"); // remove aria-current from previous page
      newPageLink.setAttribute("aria-current", "page"); // set aria-current to selected page

      const xhr = new XMLHttpRequest();
      xhr.addEventListener("load", function () {
        const cardList = document.querySelector("#content #card-list");
        cardList.innerHTML = this.response["responseHTML"]; // show current page cards

        const next = pagination.querySelector(".page-link[data-page=next]");
        const prev = pagination.querySelector(".page-link[data-page=prev]");

        !this.response["hasNext"]
          ? next.classList.add("disabled") // disable next button if there is no next page
          : next.classList.remove("disabled"); // enable next button if there is next page

        !this.response["hasPrev"]
          ? prev.classList.add("disabled") // disable prev button if there is no prev page
          : prev.classList.remove("disabled"); // enable prev button if there is prev page
      });
      xhr.open("post", "get-cards-page.php");
      xhr.responseType = "json";

      const formData = new FormData();
      formData.append("page", currentPage);

      if (url.searchParams.has("filterType")) {
        const filterType = url.searchParams.get("filterType");
        formData.append("filter", filterType); // send filter type if exists
      }
      xhr.send(formData); // send page number

      url.searchParams.set("page", currentPage); // set page param in url
      history.pushState({}, "", url); // add new history entry
    }
  });
}
