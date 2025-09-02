<!-- <div class="d-flex justify-content-between gap-5 py-5 overflow-auto">
      <img
        src="img/image.png"
        alt="WURI logo"
        height="100"
        width="250"
        class="grayscale-img"
      />
      <img
        src="img/socotec.png"
        alt="SOCOTEC logo"
        height="100"
        width="180"
        class="grayscale-img"
      />
      <img
        src="img/bagong-pilipinas.png"
        alt="Bagong Pilipinas logo"
        height="100"
        width="110"
        class="grayscale-img"
      />
      <img
        src="img/oip.png"
        alt="OIP logo"
        height="100"
        width="150"
        class="grayscale-img"
      />

      <img
        src="img/ph-seal.png"
        alt="Bagong Pilipinas logo"
        height="100"
        width="250"
        class="grayscale-img"
      />
      <img
        src="img/freedom.png"
        alt="Bagong Pilipinas logo"
        height="100"
        width="110"
        class="grayscale-img"
      />
</div> -->

<footer style="position: relative; background: #1c1c1c; color: #ffc107;" class="mt-4 py-4">
    <!-- Background Image -->
    <div style="
        /* background: url('img/nlpsc ispsc (1).png') no-repeat center center/cover; */
        opacity: 0.2;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    "></div>

    <div class="container position-relative" style="z-index: 2;">
        <div class="d-lg-flex align-items-lg-center flex-lg-row mb-5 d-md-flex flex-md-column">
            <div>
                <img src="img/ispsc.png" alt="ISPSC Logo" width="100" height="100" class="me-3" />
            </div>
            <div>
                <h1 class="ispsc-logo mb-0">REPUBLIC OF THE PHILIPPINES</h1>
                <hr class="my-2 border-white" />
                <h1 class="ispsc-logo mb-0">ILOCOS SUR POLYTECHNIC STATE COLLEGE</h1>
                <h2 class="ispsc-logo mb-0">ILOCOS SUR, PHILIPPINES</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <h5>Contact Us</h5>
                <p>
                Santa Maria,<br>Ilocos Sur, Philippines
                <br>Email: jpsaldua24@gmail.com
                </p>
            </div>
            <div class="col-md-4">
                <h5>Follow Us</h5>
                <p>
                    <a href="#" class="text-decoration-none me-2 text-warning">Facebook</a>
                    <a href="#" class="text-decoration-none me-2 text-warning">Twitter</a>
                    <a href="#" class="text-decoration-none text-warning">Instagram</a>
                </p>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-decoration-none text-warning">Privacy Policy</a></li>
                    <li><a href="#" class="text-decoration-none text-warning">Terms of Use</a></li>
                    <li><a href="https://maps.app.goo.gl/yWgZqEHoQvnuhuB37" class="text-decoration-none text-warning">Site Map</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Bottom Copyright Section with Dynamic Year -->
<div style="background: #4a0000; color: white; padding: 10px 0;">

    <p class="text-center mb-0">
        &copy; <span id="year"></span> Developed by Johnpaul Saldua Baño. All rights reserved.
    </p>
</div>

<!-- JavaScript to Update Year -->
<script>
    document.getElementById("year").textContent = new Date().getFullYear();
</script>
