let lastKnownScrollPosition = 0;

let navs = document.querySelectorAll('.link-nav-bar');
let ainfos = document.querySelectorAll('.about-info');
for (let i = 0; i < navs.length; i++) {
  navs[i].addEventListener('click',function(event) {
    ainfos[i].scrollIntoView({ behavior: 'smooth' });
  })
}

function scrollChangeBarColor(scrollPos) {
  if (scrollPos==0) {
    document.getElementById("barra").setAttribute("style", "background-color: #000000");
  }
    else {
    posAboutMe = document.getElementById("footer").offsetTop;
    heightDoc = window.innerHeight;
    document.getElementById("barra").setAttribute("style", "background-color: #33302B");
    // if (scrollPos > posAboutMe - heightDoc) {
    //   document.getElementById("foto-and-info").setAttribute("style", "position:relative");
    //   document.getElementById("info-container").setAttribute("style", "align-items:end");
    // } 
  }
}


document.addEventListener("scroll", (event) => {
  screenHeight = window.innerHeight;
  lastKnownScrollPosition = window.scrollY;
  scrollChangeBarColor(lastKnownScrollPosition);
  popUpSections( screenHeight );
//   if (!ticking) {
//     window.requestAnimationFrame(() => {
//       scrollChangeBarColor(lastKnownScrollPosition);
//       ticking = false;
//     });

//     ticking = true;
//   }
});

function saveScrollPosition() {
  localStorage.setItem('scrollPosition', window.scrollY);
}

// Function to restore scroll position
function restoreScrollPosition() {
  const scrollPosition = localStorage.getItem('scrollPosition');
  if (scrollPosition !== null) {
      window.scrollTo(0, parseInt(scrollPosition));
      localStorage.removeItem('scrollPosition'); // Clean up after restoring
  }
}

// Call restoreScrollPosition when the page loads
window.addEventListener('load', restoreScrollPosition);

// Save scroll position when the form is submitted
function handleSubmit(event) {
  saveScrollPosition();
}


function popUpSections(screenHeight) {
  var pars = document.querySelectorAll('.about-info > p, h2, h3, h4, ul, div');
  pars.forEach( ( elem ) => {
    yPosInScreen = elem.getBoundingClientRect().top
    if((yPosInScreen<(screenHeight/1.4)) && (!elem.classList.contains('entrance'))) {
      elem.classList.add('entrance');
    }
  } );
    
  }
  



document.addEventListener("DOMContentLoaded", function() {
  eventListeners();
})

function eventListeners() {
  let menu = document.querySelector(".menu-hamburguesa");
  let dark = document.querySelector(".dark-mode-button");
  let body = document.querySelector("body");
  var quotes = document.getElementsByClassName("quote-container");
  

  //let pips = document.querySelector(".pip");
  // let quotePrev = document.querySelector(".previous-quote");
  // let quoteCurr = document.querySelector(".current-quote");
  // let quoteLat = document.querySelector(".later-quote");

  menu.addEventListener("click", function() {
    menu.classList.toggle('is-opened');
    let nav = document.querySelector('.navegacion');
    nav.classList.toggle('mostrar');
  } );

  dark.addEventListener("click", function() {
    body.classList.toggle("dark-mode");
  });

  window.setInterval(function() {
    for (let i=0; i<quotes.length; i++) {
      quoteEl = quotes[i];
      if(quoteEl.classList.contains("current-quote")) {
        idxCurrent = i;
      } else if (quoteEl.classList.contains("later-quote")) {
        idxLater = i;
      }
      else if (quoteEl.classList.contains("previous-quote")) {
        idxPrevious = i;
      }
    }  
    
    $(".pip").eq(idxCurrent).css("::after", "width:20rem");

    idxLaterLater = (idxLater+1 == quotes.length) ? 0 : idxLater+1;
    idxPreviousPrevious = (idxPrevious-1 == -1) ? quotes.length-1 : idxPrevious-1;    

    quotes[idxPreviousPrevious].classList.toggle("previous-left-quote");

    quotes[idxPrevious].classList.toggle("previous-quote");
    quotes[idxPrevious].classList.toggle("previous-left-quote");

    quotes[idxCurrent].classList.toggle("current-quote");
    quotes[idxCurrent].classList.toggle("previous-quote");   
    
    quotes[idxLater].classList.toggle("later-quote");  
    quotes[idxLater].classList.toggle("current-quote");      

    //quotes[idxLaterLater].classList.toggle("later-later-quote");
    quotes[idxLaterLater].classList.toggle("later-quote");    

    $(".pip").eq(idxCurrent).toggleClass("current-pip");  
    $(".pip").eq(idxLater).toggleClass("current-pip");  


  }, 6000)
  
}




// let researchHeaders = document.querySelectorAll(".header-resbox");


// for (let i = 0; i < researchHeaders.length; i++) {
//   researchHeaders[i].addEventListener("mouseover", (event)=>{
//     expandInfo(i);
//   });
// }

// function expandInfo(i) {
//   let researchtexts = document.querySelectorAll(".moreinfo-box");
//   researchtexts[i].animate(expandInfoAnimation, expandInfoAnimationTime);
// }

// const expandInfoAnimation = [
//   { height: "0" },
//   { height: "100%" },
// ];

// const expandInfoAnimationTime = {
//   duration: 500,
//   iterations: 1,
// };