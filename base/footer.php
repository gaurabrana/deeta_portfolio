<style>
  .bg-green {
    background-color: rgb(8, 151, 30) !important;
  }

  .footer-links a {
    color: white;
    text-decoration: none;
    display: block;
    margin-bottom: 0.6rem;
    font-size: 1rem;
    transition: all 0.3s ease;
  }

  .footer-links a:hover {
    text-decoration: underline;
    margin-left: 5px;
  }

  .footer-heading {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    font-weight: bold;
    text-transform: uppercase;
  }

  .footer-logo {
    max-height: 150px;
  }

  .footer-column {
    padding: 1rem;
  }


  @media (max-width: 576px) {
    .footer-links a {
      font-size: 0.95rem;
    }

    .footer-logo {
      max-height: 90px;
    }

    .footer-column.logo-section {
      text-align: center;
    }

    .footer-heading {
      font-size: 1rem;
    }
  }
</style>

<footer class="bg-green text-white pt-5 pb-4">
  <div class="container">
    <div class="row justify-content-between">

      <!-- Column 1: Logo -->
      <div class="col-md-3 col-sm-6 footer-column logo-section">
        <img src="assets/images/logo.png" alt="Deeta Gurung" class="footer-logo mb-3">
      </div>

      <!-- Column 2: Schools -->
      <div class="col-md-2 col-sm-6 footer-column">
        <div class="footer-heading">Schools</div>
        <div class="footer-links">
          <a href="schools.php#morning_side_elementary_school">Morning Side Elementary</a>
          <a href="schools.php#pearson_middle_school">Pearson Middle School</a>
          <a href="schools.php#reedy_high_school">Reedy High School</a>
        </div>
      </div>

      <!-- Column 3: Sports -->
      <div class="col-md-2 col-sm-6 footer-column">
        <div class="footer-heading">Sports</div>
        <div class="footer-links">
          <a href="sports.php#soccer">Soccer</a>
          <a href="sports.php#swimming">Swimming</a>
          <a href="sports.php#basketball">Basketball</a>
          <a href="sports.php#volleyball">Volleyball</a>
          <a href="sports.php#track">Track</a>
        </div>
      </div>

      <!-- Column 4: Explore -->
      <div class="col-md-2 col-sm-6 footer-column">
        <div class="footer-heading">Explore</div>
        <div class="footer-links">
          <a href="about_me.php#introduction">Who Am I</a>
          <a href="about_me.php#resume">Resume</a>
          <a href="research.php">Research</a>
          <a href="moment_of_truth.php">Moment of Truth</a>
        </div>
      </div>

      <!-- Column 5: Community -->
      <div class="col-md-3 col-sm-6 footer-column">
        <div class="footer-heading">Community</div>
        <div class="footer-links">
          <a href="scouting.php#girls_scount">Girls Scout</a>
          <a href="scouting.php#boys_scout">Boys Scout</a>
          <a href="givingback.php#giving_back_to_my_school">Giving Back to School</a>
          <a href="givingback.php#giving_back_to_my_community">Giving Back to Community</a>
          <a href="gallery_image.php">Photos</a>
          <a href="gallery_video.php">Videos</a>
        </div>
      </div>

    </div>
  </div>
</footer>


<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/file_upload.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"
  integrity="sha512-q+4liFwdPC/bNdhUpZx6aXDx/h77yEQtn4I1slHydcbZK34nLaR3cAeYSJshoxIOq3mjEf7xJE8YWIUHMn+oCQ=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!--CUSTOM SCRIPT-->
<script src="assets/js/aos.js"></script>