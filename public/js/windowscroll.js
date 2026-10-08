window.addEventListener("beforeunload", function () {
        localStorage.setItem("scrollPos", window.scrollY);
    });

window.addEventListener("load", function () {
    const scrollPos = localStorage.getItem("scrollPos");
    if (scrollPos !== null) {
        window.scrollTo(0, parseInt(scrollPos));
    }
});