<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <link href="assets/css/faq.css" type="text/css" rel="stylesheet" />
    <?php
    include("base/header.php");
    ?>
</head>

<body>

    <div class="faq-container">
        <h3 class="mb-4 text-center">Frequently Asked Questions</h3>

        <div class="faq-item">
            <h3>What services does Dream Big for College offer?</h3>
            <p>We offer a range of services, including application review, essay development, leadership programs, and communication training. Our goal is to help individuals unlock their full potential and achieve academic and professional success.</p>
        </div>

        <div class="faq-item">
            <h3>How do I apply for your programs?</h3>
            <p>You can apply through our website by filling out the application form. Once submitted, a member of our team will contact you to guide you through the next steps and provide any additional information you may need.</p>
        </div>

        <div class="faq-item">
            <h3>Do I need any prior experience to join your programs?</h3>
            <p>No, our programs are designed for individuals at all levels. Whether you’re just starting or looking to enhance your skills, we tailor our approach to meet your needs.</p>
        </div>

        <div class="faq-item">
            <h3>Can I participate in multiple programs simultaneously?</h3>
            <p>Yes, you can join multiple programs depending on your interests and goals. Our team will ensure that the programs you choose complement each other for maximum benefit.</p>
        </div>

        <div class="faq-item">
            <h3>How can I contact Dream Big for College if I have more questions?</h3>
            <p>If you have further inquiries, feel free to contact us through the "Contact Us" page on our website or email us at support@dreambigforcollege.com. We’ll be happy to assist you!</p>
        </div>

        <div class="faq-item">
            <h3>What are the benefits of joining Dream Big for College?</h3>
            <p>Joining us gives you personalized support, expert mentorship, and an opportunity to enhance critical skills that will serve you throughout your personal and professional journey. We focus on long-term growth and success.</p>
        </div>

    </div>

    <script>
        // Toggle FAQ item visibility
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('h3');
            question.addEventListener('click', () => {
                item.classList.toggle('active');
            });
        });
    </script>

    <?php
    include("base/footer.php");
    ?>
</body>

</html>