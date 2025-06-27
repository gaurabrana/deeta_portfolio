<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Deeta's Website</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/landing.css">
</head>

<body>
  <?php
  require_once('database/connect.php');
  if (!isset($_SESSION)) {
    session_start();
  }

  $heading = '';
  $quote = '';
  $imagePath = '';

  if (!$conn->connect_error) {
    $result = $conn->query("SELECT heading, quote, path FROM index_info WHERE id = 1");
    if ($row = $result->fetch_assoc()) {
      $heading = htmlspecialchars($row['heading']);
      $quote = htmlspecialchars($row['quote']);
      $imagePath = $row['path'];
    }
    $conn->close();
  }

  $words = preg_split('/\s+/', $heading, -1, PREG_SPLIT_NO_EMPTY);
  $totalWords = count($words);

  $half = (int) ceil($totalWords / 2);

  $part1 = implode(' ', array_slice($words, 0, $half));
  $part2 = implode(' ', array_slice($words, $half));

  ?>
  <div class="transition-overlay" id="transitionOverlay" style="display: none;"></div>

  <div class="container-fluid content-section">
    <div class="all-contents">
      <div class="row align-items-center text-center pt-5 landing-container">
        <div class="col-lg-12 pb-2">
          <section id="our-mission" class="pt-3">
            <div class="container">
              <div class="row align-items-center">
                <!-- Image Column -->
                <div class="col-md-5 text-center">
                  <div class="image-wrapper text-center">
                    <img src="assets/images/uploads/index/<?= $imagePath ?>" alt="Welcome"
                      class="img-fluid rounded shadow">
                  </div>
                </div>

                <!-- Spacer -->
                <div class="col-md-2"></div>

                <!-- Text Column -->
                <div class="col-md-5 text-center">
                  <div class="p-4">
                    <h2 class="text-uppercase fw-bold mb-1">Welcome</h2>
                    <h2 class="text-uppercase fw-bold mb-1">To</h2>
                    <h2 class="text-uppercase fw-bold mb-3">Deeta's World Of Vision</h2>
                    <p class="quote-text lead mb-2">
                      "<?= $quote ?>"
                    </p>
                    <span>- Deeta Gurung</span>
                  </div>
                </div>
              </div>

              <!-- Conditional Buttons for Logged In User -->
              <?php if (isset($_SESSION['logged_in'])): ?>
                <div class="text-center mt-5">
                  <a href="about_me.php?page=who_am_i" class="btn btn-primary me-2">Go to Home</a>
                  <button class="btn btn-warning me-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#updateQuoteAccordion">
                    Update Info
                  </button>
                  <form method="POST" action="database/index_logout.php" class="d-inline">
                    <button class="btn btn-danger" type="submit">Logout</button>
                  </form>
                </div>

                <!-- Accordion Form -->
                <div class="accordion my-3" id="updateAccordion">
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#updateQuoteAccordion">
                        Update Information
                      </button>
                    </h2>
                    <div id="updateQuoteAccordion" class="accordion-collapse collapse" data-bs-parent="#updateAccordion">
                      <div class="accordion-body">
                        <form id="infoUpdateForm" enctype="multipart/form-data" method="POST">
                          <div class="row">                          

                            <!-- Quote -->
                            <div class="col-md-12 mb-3">
                              <label for="quote" class="form-label">Quote</label>
                              <input type="text" class="form-control" name="quote" id="quote" value="<?= $quote ?>"
                                required>
                            </div>
                          </div>

                          <!-- Image Upload + Preview -->
                          <div class="row">
                            <div class="col-md-6 mb-3">
                              <label for="image" class="form-label">Upload Image</label>
                              <input type="file" class="form-control" name="image" id="image" accept="image/*">
                            </div>
                            <div class="col-md-6 mb-3">
                              <div class="mt-2">
                                <p class="mb-1">Current Image:</p>
                                <img id="imagePreview" src="assets/images/uploads/index/<?= $imagePath ?>" alt="Current"
                                  style="max-width: 200px;" class="rounded shadow">
                              </div>
                            </div>
                          </div>

                          <!-- Submit Button -->
                          <div class="row">
                            <div class="col-md-12 text-center">
                              <button type="submit" class="btn btn-success">Update Info</button>
                              <div id="updateStatus" class="mt-2 alert alert-success" style="display:none;"></div>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/jquery-3.7.1.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/landing.js"></script>
  <script>
    function showSplashScreen() {
      setTimeout(() => {
        const overlay = document.getElementById('transitionOverlay');
        overlay.style.display = 'flex';
        overlay.classList.add('show');
        setTimeout(() => {
          window.location.href = "about_me.php?page=who_am_i";
        }, 500);

      }, 6000);
    }
    <?php if (!isset($_SESSION['logged_in'])): ?>
      showSplashScreen();
    <?php endif; ?>
  </script>
</body>

</html>