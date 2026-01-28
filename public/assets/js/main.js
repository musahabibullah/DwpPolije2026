/**
* Template Name: Logis
* Template URL: https://bootstrapmade.com/logis-bootstrap-logistics-website-template/
* Updated: Aug 07 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  mobileNavToggleBtn.addEventListener('click', mobileNavToogle);

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Frequently Asked Questions Toggle
   */
  document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle').forEach((faqItem) => {
    faqItem.addEventListener('click', () => {
      faqItem.parentNode.classList.toggle('faq-active');
    });
  });


  // MENCARI NIK di LANDING PAGE dan otomatis tedirect ke halaman payment-user
  document.addEventListener("DOMContentLoaded", function () {
      document.getElementById('searchForm').addEventListener('submit', function(event) {
          event.preventDefault();

          let nik = document.getElementById('nikInput').value;

          if (nik.trim() === '') {
              Swal.fire({
                  icon: 'warning',
                  title: 'Peringatan!',
                  text: 'Harap masukkan NIK terlebih dahulu.',
              });
              return;
          }

          // Cek ke database apakah NIK terdaftar dan status pembayaran
          fetch('/search-nik', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({ nik: nik })
          })
          .then(response => response.json())
          .then(data => {
              if (data.status === 'exists') {
                  Swal.fire({
                      icon: 'info',
                      title: 'Form Sudah Dikirim!',
                      text: data.message,
                      confirmButtonText: 'Baik, Terima Kasih!'
                  });
              } else if (data.status === 'error') {
                  Swal.fire({
                      icon: 'error',
                      title: 'NIK Tidak Ditemukan!',
                      text: data.message,
                  });
              } else {
                  Swal.fire({
                      icon: 'success',
                      title: 'Silakan Lanjut!',
                      text: data.message,
                      confirmButtonText: 'Lanjutkan'
                  }).then(() => {
                      window.location.href = "/payment-user?nik=" + nik;
                  });
              }
          })
          .catch(error => {
              console.error('Error:', error);
              Swal.fire({
                  icon: 'error',
                  title: 'Terjadi Kesalahan!',
                  text: 'Silakan coba lagi.',
              });
          });
      });
  });

  // START COUNTDOWN LANDING PAGE
  function startCountdown(targetDate) {
      function updateCountdown() {
          const now = new Date().getTime();
          const distance = targetDate - now;

          if (distance <= 0) {
              document.getElementById("days").innerText = "00";
              document.getElementById("hours").innerText = "00";
              document.getElementById("minutes").innerText = "00";
              document.getElementById("seconds").innerText = "00";

              document.getElementById("nikInput").disabled = true;
              document.getElementById("nikInput").placeholder = "Form Pembayaran telah Ditutup";
              document.getElementById("searchNIKbtn").disabled = true;

              Swal.fire({
                title: "Mohon Maaf",
                text: "Form pembayaran telah ditutup.",
                icon: "warning",
                confirmButtonText: "Baik"
            });
              // Tampilkan overlay dan popup
              return;
          }

          const days = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
          const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
          const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
          const seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');

          document.getElementById("days").innerText = days;
          document.getElementById("hours").innerText = hours;
          document.getElementById("minutes").innerText = minutes;
          document.getElementById("seconds").innerText = seconds;
      }

      updateCountdown();
  }

  // Set target waktu (misalnya countdown menuju 17 Maret 2025)
  const targetDate = new Date("March 31, 2026 23:59:59").getTime();
  startCountdown(targetDate);
  })();