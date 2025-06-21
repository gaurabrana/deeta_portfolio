<footer class="modern-footer pt-5">
  <div class="container footer-content">
    <div class="row g-4 mb-5">
      <!-- Company Info -->
      <div class="col-lg-4 col-md-6">
        <a href="#" class="footer-logo d-block mb-4">
          <img src="assets/images/dream_big_logo_green.png" alt="Logo" class="mb-3 mx-auto" style="max-width: 120px;">
        </a>
        <p class="mb-4 text-center">The Bridge For Excellence</p>
      </div>

      <!-- Quick Links -->
      <div class="col-lg-4 col-md-6">
        <h3 class="footer-title">Quick Links</h3>
        <ul class="quick-links">
          <li><a href="home.php" class="footer-link">Home</a></li>
          <li><a href="services.php" class="footer-link">Services</a></li>
          <!-- <li><a href="reviews.php" class="footer-link">Reviews</a></li> -->
          <li><a href="about_us.php" class="footer-link">About Us</a></li>
          <li><a href="givingback.php" class="footer-link">Giving Back</a></li>
          <li><a type="button" data-bs-toggle="modal" data-bs-target="#privacyPolicy" class="footer-link">Privacy
              Policy</a></li>
          <li><a type="button" data-bs-toggle="modal" data-bs-target="#termsAndCondition" class="footer-link">Terms and
              Conditions</a></li>
          <li><a href="faq.php" class="footer-link">FAQ</a></li>
          <li><a type="button" data-bs-toggle="modal" data-bs-target="#contactUs" class="footer-link">Contact Us</a>
          </li>
        </ul>
      </div>

      <!-- Newsletter -->
      <div class="col-lg-4 col-md-12">
        <h3 class="footer-title">Stay Connected</h3>
        <ul class="contact-info mb-4">
          <li>
            <i class="fas fa-map-marker-alt"></i>
            <span>
            P.O. Box - 6193 , Frisco Texas, 75035
            </span>
          </li>
          <li>
            <i class="fas fa-envelope"></i>
            <span><a href="mailto:us@dreambigforcollege.com">us@dreambigforcollege.com</a></span>
          </li>
        </ul>

      </div>

    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <div class="container">
      <div class="row py-4">
        <div class="col-md-12 text-md-center">
          <p class="small mb-0">&copy; <span id="current-year"></span> Dream Big For College. All Rights Reserved.</p>
        </div>
      </div>
    </div>
  </div>
</footer>


<!--------Terms and Condition Dialog------->
<div class="modal fade" id="termsAndCondition" tabindex="-1" aria-labelledby="termsAndConditionLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TermsConditionLabel">Terms And Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        include('./terms_and_condition.php')
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!--------DIALOGS------->
<div class="modal fade" id="privacyPolicy" tabindex="-1" aria-labelledby="privacyPolicyLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="PrivacyPolicyLabel">Privacy Policy</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        include('./privacy_policy.php')
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!--------DIALOGS------->
<div class="modal fade" id="contactUs" tabindex="-1" aria-labelledby="contactUsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ContactUsLabel">Contact Us</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        include('./contact_us.php')
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/file_upload.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js" integrity="sha512-q+4liFwdPC/bNdhUpZx6aXDx/h77yEQtn4I1slHydcbZK34nLaR3cAeYSJshoxIOq3mjEf7xJE8YWIUHMn+oCQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!--CUSTOM SCRIPT-->
<script src="assets/js/aos.js"></script>