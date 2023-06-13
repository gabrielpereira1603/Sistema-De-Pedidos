let navBar = document.querySelector(".nav-bar");
let navLinks = document.querySelectorAll(".nav-bar ul li a");

navLinks.forEach(function(link) {
  link.addEventListener("click", function(event) {
    // Remove a classe "active" de todos os links
    navLinks.forEach(function(link) {
      link.classList.remove("active");
    });

    // Adiciona a classe "active" ao link atualmente clicado
    this.classList.add("active");

    // Atualiza a posição da seta com base no link atualmente clicado
    let arrow = document.querySelector(".nav-bar a.active::before");
    let linkRect = this.getBoundingClientRect();
    arrow.style.top = linkRect.top + linkRect.height / 2 + "px";

    // Impede o comportamento padrão do link (navegar para outra página)
    event.preventDefault();
  });
});

let initialPoint;
let finalPoint;

document.addEventListener('touchstart', function(event) {
  initialPoint = event.changedTouches[0];
}, false);

document.addEventListener('touchend', function(event) {
  finalPoint = event.changedTouches[0];
  let xAbs = Math.abs(initialPoint.pageX - finalPoint.pageX);
  let yAbs = Math.abs(initialPoint.pageY - finalPoint.pageY);
  if (xAbs > 20 || yAbs > 20) {
    if (xAbs > yAbs) {
      if (finalPoint.pageX < initialPoint.pageX) {
        /* СВАЙП ВЛЕВО */
        if (window.innerWidth < 720) {
          navBar.style.width = "0";
        }
      } else {
        /* СВАЙП ВПРАВО */
        if (window.innerWidth < 720) {
          navBar.style.width = "300px";
        }
      }
    }
  }
}, false);

window.addEventListener('resize', function() {
  if (window.innerWidth > 720) {
    navBar.style.width = "300px";
  } else {
    navBar.style.width = "0";
  }
});

// Obtém o link da página atual
let currentPage = window.location.pathname;

// Remove a classe "active" de todos os links
navLinks.forEach(function(link) {
  link.classList.remove("active");
});

// Adiciona a classe "active" ao link correspondente à página atual
navLinks.forEach(function(link) {
  let linkHref = link.getAttribute("href");
  if (linkHref === currentPage) {
    link.classList.add("active");
  }
});
