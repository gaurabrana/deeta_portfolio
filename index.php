<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dream Big For College</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/landing.css">
</head>

<body>
<div class="question-container">
    <h1 class="heading-title">Dream Big For College</h1>
    <img src="assets/images/dream_big_logo_green.png" alt="Logo" class="splash-logo">        
    <h1 class="sub-heading">The Bridge For Excellence</h1>
    
    <br>
    <h2 class="sub-heading title">Our Core Values</h2>
    <div class="button-list-main">
    <button class="button">Education</button>
    <button class="button">Communication</button>
    <button class="button">Leadership</button>    
    </div>
    
    <div class="button-list">
    <br>
    <button class="button">Excellence</button>
    <button class="button">Affordability</button>
    <button class="button">Flexibility (Pay-As-You-Pick)</button>
    <button class="button">Collaboration</button>
    <button class="button">Integrity</button>
    </div>

    <br><br>  
    <h2 class="sub-heading">"Are you ready to bridge with us to pursue your biggest dreams?"</h2>
    <button class="question-button" onclick="showSplashScreen()">Yes I am</button>
</div>


  <div class="splash-container">
    <img src="assets/images/dream_big_logo_green.png" alt="Logo" class="splash-logo">
    <h1 class="heading-title">Dream Big For College</h1>
    <h2 class="description">Preparing your path...</h2>
    <div class="loading-bar"></div>
  </div>

  <div class="transition-overlay" id="transitionOverlay" style="display: none;"></div>

  <script>
    function showSplashScreen() {
      document.querySelector('.question-container').style.display = 'none';
      document.querySelector('.splash-container').style.display = 'block';
      document.querySelector('body').style.alignItems = 'center';

      // After the splash screen, show the transition overlay before redirecting
      setTimeout(() => {
        document.getElementById('transitionOverlay').style.display = 'flex';

        setTimeout(() => {
          window.location.href = "home.php"; // Change "home.php" to your desired URL
          document.querySelector('.question-container').style.display = 'flex';
          document.getElementById('transitionOverlay').style.display = 'none';
        document.querySelector('.splash-container').style.display = 'none';        
        }, 500); // Transition duration

      }, 3000); // Delay for splash screen animation
    }
  </script>
</body>

</html>