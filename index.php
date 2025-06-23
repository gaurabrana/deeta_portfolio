<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Deeta's Website</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/landing.css">
</head>

<body>
<div class="transition-overlay" id="transitionOverlay" style="display: none;"></div>

<div class="container-fluid content-section">
  <div class="all-contents">
    <div class="row align-items-center text-center pt-5 landing-container">
      <div class="col-lg-12 pb-2">
        <section id="our-mission" class="pt-3">
          <div class="container">
<br>
<br>
            <div class="table-responsive">
              <table class="table borderless w-100" style="border-spacing: 2rem; border-collapse: separate;">
                <tr>
                  <!-- Image Section (Left) -->
                  <td class="align-middle w-45" data-aos="zoom-in">
                    <div class="image-wrapper text-center">
                      <img src="assets/images/Deeta_Single.jpg" alt="Welcome" class="img-fluid rounded shadow">
                    </div>
                  </td>

                  <!-- Spacer Column -->
                  <td class="w-10"></td>

                  <!-- Text Section (Right) -->
                  <td class="align-middle w-45 text-start" data-aos="fade-up">
                    <div class="p-4">
                                  <h2 class="text-center text-uppercase fw-bold mb-5" data-aos="fade-down">
              Welcome to Deeta's Website
            </h2>
                      <p class="lead mb-2" data-aos="zoom-out-left">
                        Bringing clarity to the world—one vision at a time.
                      </p>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>


      
  <script>
    function showSplashScreen() {
      // After the splash screen, show the transition overlay before redirecting
      setTimeout(() => {
        document.getElementById('transitionOverlay').style.display = 'flex';

        setTimeout(() => {
          window.location.href = "about_me.php"; // Change "home.php" to your desired URL            
        }, 500); // Transition duration

      }, 3000); // Delay for splash screen animation
    }
    showSplashScreen();
  </script>
</body>
    <?php
    include("base/footer.php");
    ?>
</html>