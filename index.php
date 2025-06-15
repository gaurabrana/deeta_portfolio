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
<div class="question-container">
    <h1 class="heading-title">Deeta's Website</h1>
    <img src="assets/images/dream_big_logo_green.png" alt="Logo" class="splash-logo">        
    <h1 class="sub-heading">Kids For Kindness</h1>
    
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
</div>

  <div class="transition-overlay" id="transitionOverlay" style="display: none;"></div>

  <script>
    function showSplashScreen() {
      // After the splash screen, show the transition overlay before redirecting
      setTimeout(() => {
        document.getElementById('transitionOverlay').style.display = 'flex';

        setTimeout(() => {
          window.location.href = "home.php"; // Change "home.php" to your desired URL            
        }, 500); // Transition duration

      }, 3000); // Delay for splash screen animation
    }
    showSplashScreen();
  </script>
</body>

</html>