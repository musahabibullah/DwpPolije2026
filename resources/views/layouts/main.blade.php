@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<main class="main">

    <!-- Hero Section -->
    <section id="beranda" class="hero section dark-background">

      <img src="assets/img/world-dotted-map.png" alt="" class="hero-bg" data-aos="fade-in">

      <div class="container">
        <div class="row gy-4 d-flex justify-content-between">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h2 data-aos="fade-up">Sudahkah Anda Memeriksa Data Anda?</h2>
            <p data-aos="fade-up" data-aos-delay="100">Ingin tahu apakah anda termasuk dalam daftar penerima parcel dari program bazar? Cukup masukkan NIK/NIPPPK/NIP anda dan lakukan pencarian sekarang juga! Pastikan Informasi yang anda masukkan benar agar hak anda Sebagai Penerima Tidak Terlewatkan.</p>

            <form id="searchForm" class="form-search d-flex align-items-stretch mb-3" data-aos="fade-up" data-aos-delay="200">
                <input type="text" id="nikInput" class="form-control" placeholder="Masukkan NIK/NIPPPK/NIP Anda">
                <button id="searchNIKbtn" type="submit" class="btn btn-primary">Search</button>
            </form>

            <div class="row gy-4" data-aos="fade-up" data-aos-delay="300">

              <div class="col-lg-3 col-6">
                <div class="stats-item text-center w-100 h-100">
                  <span id="days">19</span>
                  <p>Hari</p>
                </div>
              </div><!-- End Stats Item -->

              <div class="col-lg-3 col-6">
                <div class="stats-item text-center w-100 h-100">
                  <span id="hours">12</span>
                  <p>Jam</p>
                </div>
              </div><!-- End Stats Item -->

              <div class="col-lg-3 col-6">
                <div class="stats-item text-center w-100 h-100">
                  <span id="minutes">35</span>
                  <p>Menit</p>
                </div>
              </div><!-- End Stats Item -->

              <div class="col-lg-3 col-6">
                <div class="stats-item text-center w-100 h-100">
                  <span id="seconds">31</span>
                  <p>Detik</p>
                </div>
              </div><!-- End Stats Item -->

            </div>

          </div>

          <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
            <img src="assets/img/hero-img.svg" class="img-fluid mb-3 mb-lg-0" alt="">
          </div>

        </div>
      </div>

    </section><!-- /Hero Section -->

    </section><!-- /Featured Services Section -->

    <!-- About Section -->
    <section id="tentangkami" class="about section">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6 position-relative align-self-start order-lg-last order-first" data-aos="fade-up" data-aos-delay="200">
            <img src="assets/img/halamandepan.jpg" class="img-fluid" alt="">
            <a href="https://www.youtube.com/watch?v=N0YLUZ5HobA&t=13s" class="glightbox pulsating-play-btn"></a>
          </div>

          <div class="col-lg-6 content order-last order-lg-first" data-aos="fade-up" data-aos-delay="100">
            <h3>Tentang Kami</h3>
            <p>
            Taman Penitipan Anak Daycare DWP Polije adalah tempat yang hangat dan penuh kasih sayang bagi anak-anak. Kami berkomitmen memberikan perawatan dan pengasuhan terbaik, memastikan mereka tumbuh dengan bahagia, sehat, dan berprestasi. Dengan lingkungan yang aman dan mendukung, kami membantu anak-anak mengembangkan potensi mereka untuk masa depan yang cerah, sehingga orang tua dapat merasa tenang dan percaya bahwa anak-anak berada dalam asuhan yang tepat.
            </p>
            <ul>
              <li>
                <i class="bi bi-diagram-3"></i>
                <div>
                  <h5>Tenaga pengajar yang berpengalaman dan terlatih</h5>
                  <p>Struktur pendidikan yang didukung oleh tenaga pengajar berkualitas dan berpengalaman.</p>
                </div>
              </li>
              <li>
                <i class="bi bi-fullscreen-exit"></i>
                <div>
                  <h5>Program pendidikan yang merangsang perkembangan kognitif, sosial, dan emosional anak</h5>
                  <p>Sistem pembelajaran yang dirancang untuk mengembangkan kemampuan berpikir, berinteraksi, dan mengelola emosi anak secara seimbang.</p>
                </div>
              </li>
              <li>
                <i class="bi bi-broadcast"></i>
                <div>
                  <h5>Lingkungan yang bersih, aman, dan higienis untuk menjaga kesehatan anak-anak</h5>
                  <p>Lingkungan yang terjaga dengan baik untuk memastikan anak-anak dapat belajar dan bermain dengan nyaman tanpa risiko kesehatan.</p>
                </div>
              </li>
            </ul>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Galeri Section -->
    <section id="galeri" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Galeri Kami<br></span>
        <h2>Galeri Kami</h2>
        <p>Mengabadikan momen berharga dalam kebersamaan dan dedikasi</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card">
              <div class="card-img">
                <img src="assets/img/K1.jpeg" alt="" class="img-fluid">
              </div>
              <h3>Sosialisasi Tumbuh Kembang Anak</h3>
              <p>Kegiatan yang mendukung proses tumbuh kembang anak secara fisik, sosial, dan emosional. Anak-anak berinteraksi melalui permainan edukatif yang merangsang perkembangan motorik halus dan kasar, dengan pendamping terlatih yang membimbing setiap aktivitas.</p>
            </div>
          </div>
          <!-- End Card Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="card">
              <div class="card-img">
                <img src="assets/img/K3.jpeg" alt="" class="img-fluid">
              </div>
              <h3>Pembuatan Makanan Sehat Anak</h3>
              <p>Kegiatan pembuatan makanan sehat untuk anak mengedukasi para pengasuh atau orang tua tentang pentingnya asupan gizi yang seimbang. Para peserta diajarkan cara mengolah bahan bergizi sesuai dengan kebutuhan anak-anak untuk mendukung pertumbuhan dan perkembangan mereka secara optimal.</p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card">
              <div class="card-img">
                <img src="assets/img/K4.jpeg" alt="" class="img-fluid">
              </div>
              <h3>Pemberdayaan Pengasuh TPA DWP Polije
              </h3>
              <p>Kegiatan pemberdayaan pengasuh TPA DWP Polije bertujuan untuk meningkatkan kemampuan dan pengetahuan pengasuh dalam merawat dan mendidik anak. Pengasuh diberikan pelatihan tentang berbagai teknik pengasuhan, pendidikan anak usia dini, serta cara menangani anak dengan kebutuhan khusus.</p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="card">
              <div class="card-img">
                <img src="assets/img/K7.jpeg" alt="" class="img-fluid">
              </div>
              <h3>Pertemuan Pengurus TPA DWP Polije
              </h3>
              <p>Pertemuan pengurus TPA DWP Polije membahas pengelolaan dan pengembangan program, merencanakan kegiatan, mengevaluasi program, serta menyusun strategi untuk meningkatkan kualitas layanan dan pembelajaran anak.</p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="card">
              <div class="card-img">
                <img src="assets/img/K9.jpeg" alt="" class="img-fluid">
              </div>
              <h3>Peresmian TPA DWP Polije
              </h3>
              <p>Peresmian TPA DWP Polije menandai dibukanya fasilitas baru untuk anak-anak. Acara ini dihadiri berbagai pihak terkait dan mencakup seremonial pembukaan. Tujuannya adalah memperkenalkan TPA kepada masyarakat serta menyampaikan informasi tentang layanan yang mendukung tumbuh kembang anak.</p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="card">
              <div class="card-img">
                <img src="assets/img/K12.jpeg" alt="" class="img-fluid">
              </div>
              <h3>Kegiatan Harian Anak TPA DWP Polije
              </h3>
              <p>Kegiatan harian anak di TPA DWP Polije mencakup permainan edukatif, seni, dan pembelajaran dasar yang mendukung perkembangan fisik, kognitif, dan sosial. Setiap aktivitas dirancang interaktif dan menyenangkan untuk menstimulasi keterampilan motorik dan sosial anak.</p>
            </div>
          </div><!-- End Card Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section dark-background">

      <img src="assets/img/halamandepan.jpg" alt="">

      <div class="container">
        <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-xl-10">
            <div class="text-center">
              <h3>DWP - Politeknik Negeri Jember</h3>
              <p>Cerita Bahagia Anak Dimulai Disini: Taman Penitipan Anak Daycare Pilihan!</p>
              <a class="cta-btn" href="https://www.tpadwppolije.com/">Lihat Selengkapnya</a>
            </div>
          </div>
        </div>
      </div>

    </section><!-- /Call To Action Section -->
  </main>
@endsection