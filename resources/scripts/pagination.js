const pagination = document.querySelector("#content .pagination");

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
      currentPage = getNewPage(url, page);
    } else {
      currentPage = page;
    }

    const currentPageLink = pagination.querySelector(
      ".page-link[aria-current]",
    );
    let newPageLink = pagination.querySelector(
      `.page-link[data-page="${currentPage}"]`,
    );

    if (!newPageLink) {
      currentPage = 2;
      newPageLink = pagination.querySelector(
        `.page-link[data-page="${currentPage}"]`,
      );
    }
    currentPageLink.removeAttribute("aria-current");
    newPageLink.setAttribute("aria-current", "page");

    const xhr = new XMLHttpRequest();

    xhr.addEventListener("load", function () {
      getCards(this);
      checkPaginationBtns(this);
    });

    xhr.open("post", "get-cards-page.php");
    xhr.responseType = "json";

    const formData = new FormData();
    formData.append("page", currentPage);

    if (url.searchParams.has("filterType")) {
      const filterType = url.searchParams.get("filterType");
      formData.append("filter", filterType);
    }
    xhr.send(formData);

    url.searchParams.set("page", currentPage);
    history.pushState({}, "", url);
  }
});

/**
 * @param {URL} url
 * @param {string} page
 */
function getNewPage(url, page) {
  let _currentPage;

  if (!url.searchParams.has("page")) {
    _currentPage = 1;
  } else {
    _currentPage = parseInt(url.searchParams.get("page"));
    if (_currentPage === 0) _currentPage = 1;
  }
  return page === "next" ? ++_currentPage : --_currentPage;
}

/**
 * @param {XMLHttpRequest} xhr
 */
function getCards(xhr) {
  const cardList = document.querySelector("#content #card-list");
  cardList.innerHTML = xhr.response["responseHTML"];
}

/**
 * @param {XMLHttpRequest} xhr
 */
function checkPaginationBtns(xhr) {
  const next = pagination.querySelector(".page-link[data-page=next]");
  const prev = pagination.querySelector(".page-link[data-page=prev]");

  !xhr.response["hasNext"]
    ? next.classList.add("disabled")
    : next.classList.remove("disabled");

  !xhr.response["hasPrev"]
    ? prev.classList.add("disabled")
    : prev.classList.remove("disabled");
}
