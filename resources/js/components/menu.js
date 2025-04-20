let menuIsActive = false;

document.addEventListener("click", e=> { 

     const menuBttn = document.getElementById("sidebar-button");
     const menu = document.getElementById("menu");
     const menuContainer = menu.querySelector(".menu-container");

     if(menuIsActive) {
          if(e.target.matches(".bttn-close") || (e.target != menu && !menuContainer.contains(e.target)) ) {
               menu.classList.remove("active");
               menuIsActive = false
          }
     } else if(e.target == menuBttn || menuBttn.contains(e.target)) {
          menu.classList.add("active");
          menuIsActive = true;
     }

});

// Dark/Light Theme Toggle using cookies
document.addEventListener("DOMContentLoaded", () => {
    const savedTheme = getCookie("theme");
    if (savedTheme) {
        document.body.classList.add(savedTheme);
    } else {
        document.body.classList.add("light");
        setCookie("theme", "light", 7);
    }

    const themeToggleLink = document.querySelector(".theme-toggle");
    themeToggleLink.addEventListener("click", (e) => {
        e.preventDefault();
        const currentTheme = document.body.classList.contains("dark") ? "dark" : "light";
        const newTheme = currentTheme === "dark" ? "light" : "dark";

        document.body.classList.remove(currentTheme);
        document.body.classList.add(newTheme);

        setCookie("theme", newTheme, 7);
    });
});

// Helper function to set a cookie
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
}

// Helper function to get a cookie
function getCookie(name) {
    const cookies = document.cookie.split("; ");
    for (const cookie of cookies) {
        const [key, value] = cookie.split("=");
        if (key === name) {
            return value;
        }
    }
    return null;
}