<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <?php
    include("base/header.php");

    // Get the tab parameter from the URL query string (if any)
    $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'application-review'; // Default to application-review
    ?>
    <link href="assets/css/services.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="preloader" id="preloader">
        <img src="assets/images/preloader.gif" alt="Loading..." />
    </div>
    <!-- Header Section -->

    <div class="container-fluid content-section">
        <div class="all-contents">
            <header class="text-center py-1">
                <div class="container">
                    <p class="paragraph mt-3">Dream Big for College takes a comprehensive approach to guiding
                        students,
                        equipping
                        them with the
                        skills, knowledge, and opportunities needed to thrive in higher education and beyond. </p>
                </div>
            </header>

            <div class="container py-3" id="College_Admission_Counselling">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">College Admission Counselling</h2>
                <div class="row align-items-center">
                    <!-- Image Section -->
                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/1.jpg" alt="Our Mission" class="img-fluid rounded shadow">
                        </div>
                    </div>

                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                College admission counselling provides students with personalized guidance throughout
                                the
                                application process. This includes helping them choose the right universities based on
                                their academic
                                goals, interests, and career aspirations. Counsellors assist with application
                                strategies, deadlines, and
                                requirements, ensuring students present a compelling application that increases their
                                chances of
                                acceptance.
                            </p>

                        </div>
                    </div>
                </div>
            </div>

            <div class="container py-3" id="Essays_Review">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">Essays Review</h2>
                <div class="row align-items-center">
                    <!-- Image Section -->


                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                College application essays play a crucial role in admissions. This service helps
                                students craft well-structured,
                                impactful essays that highlight their strengths, experiences, and aspirations.
                                Professional
                                guidance ensures clarity, coherence, and originality while refining grammar, structure,
                                and storytelling
                                to make applications stand out.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/2.jpg" alt="Our Mission" class="img-fluid rounded shadow">
                        </div>
                    </div>
                </div>
            </div>

            <div class="container py-3" id="Financial_Aid_Counselling">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">Scholarship & Financial Aid (FAFSA)
                    Counselling
                </h2>
                <div class="row align-items-center">
                    <!-- Image Section -->
                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/3.jpg" alt="Our Mission" class="img-fluid rounded shadow">
                        </div>
                    </div>

                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                Many students require financial aid to
                                afford higher education. FAFSA (Free
                                Application for Federal Student Aid)
                                counselling helps students and families
                                understand and navigate the process of
                                applying for federal and institutional
                                financial aid. Experts guide them in filling
                                out applications correctly, maximizing
                                eligibility for grants, scholarships, and loans.
                            </p>
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                Scholarships reduce the financial burden of college education. This service helps
                                students
                                identify and apply for relevant scholarships based on academic merit, extracurricular
                                achievements, leadership skills, or financial need. Guidance is provided on application
                                requirements, essays, and submission strategies to improve chances of securing funding.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container py-3" id="Extra_Curricular">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">Extra-Curricular activities Counselling
                </h2>
                <div class="row align-items-center">
                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                Our extra-curricular counselling helps students choose activities that align with their
                                interests, personal growth, and career goals. We guide you in balancing academics with
                                hobbies like sports, arts, or leadership, and help build a strong profile for college
                                and career advancement.
                            </p>
                        </div>
                    </div>
                    <!-- Image Section -->
                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/extra_curricular.jpg" alt="Our Mission"
                                class="img-fluid rounded shadow">
                        </div>
                    </div>

                </div>
            </div>

            <div class="container py-3" id="Interview_Counselling">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">Interview Counselling</h2>
                <div class="row align-items-center">
                    <!-- Image Section -->
                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/interview_counselling.jpg" alt="Our Mission"
                                class="img-fluid rounded shadow">
                        </div>
                    </div>
                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                We offer personalized interview coaching to help you build confidence and improve your
                                communication skills. Through mock interviews and tailored feedback, we prepare you to
                                present yourself effectively and answer tough questions, ensuring you succeed in job,
                                internship, or college interviews.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container py-3" id="Research_Paper_Counselling">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">Research Paper Counselling</h2>
                <div class="row align-items-center">
                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                Our research paper counselling provides expert advice on selecting topics, conducting
                                research, and structuring your paper. We ensure your work meets academic standards and
                                helps you develop skills to create a well-crafted, impactful research paper.
                            </p>
                        </div>
                    </div>
                    <!-- Image Section -->
                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/research_paper.jpg" alt="Our Mission"
                                class="img-fluid rounded shadow">
                        </div>
                    </div>

                </div>
            </div>

            <div class="container py-3" id="Co_Internship_Opportunities">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">Co-op and Internship Opportunities
                </h2>
                <div class="row align-items-center">
                    <!-- Image Section -->
                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/5.jpg" alt="Our Mission" class="img-fluid rounded shadow">
                        </div>
                    </div>

                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                Gaining practical experience before graduation is essential for career success. This
                                service
                                connects students with co-op programs and internships that align with their career
                                goals,
                                allowing them to gain hands-on experience, build professional networks, and enhance
                                their
                                resumes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container py-3" id="Career_Navigation_Counselling">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">Career Navigation Counselling</h2>
                <div class="row align-items-center">
                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                Choosing the right career path can be challenging. Career navigation counselling helps
                                students explore potential careers, understand job market trends, and align their
                                academic
                                choices with long-term professional goals. Counsellors provide insights into industries,
                                job
                                roles, and skills required for success in various fields.
                            </p>
                        </div>
                    </div>
                    <!-- Image Section -->
                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/6.jpg" alt="Our Mission" class="img-fluid rounded shadow">
                        </div>
                    </div>


                </div>
            </div>

            <div class="container py-3" id="SAT_ACT_Coaching">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">SAT/ACT Coaching</h2>
                <div class="row align-items-center">
                    <!-- Image Section -->
                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/7.jpg" alt="Our Mission" class="img-fluid rounded shadow">
                        </div>
                    </div>

                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                Standardized test scores are important for college admissions. SAT/ACT coaching provides
                                students with expert-led strategies, practice tests, and personalized study plans to
                                improve
                                their scores. This helps enhance college applications and eligibility for merit-based
                                scholarships.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container py-3" id="Building_Communication_Skills">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">Building Communication Skills [ Bonus
                    Program ]</h2>
                <div class="row align-items-center">

                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                Effective communication is key to academic and professional success. This service helps
                                students develop verbal and written communication skills, including public speaking,
                                interpersonal communication, and professional writing, to excel in interviews,
                                presentations,
                                and teamwork environments.
                            </p>
                        </div>
                    </div>
                    <!-- Image Section -->
                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/8.jpg" alt="Our Mission" class="img-fluid rounded shadow">
                        </div>
                    </div>
                </div>
            </div>

            <div class="container py-3" id="Leadership_Guidance">
                <h2 class="heading-3 text-center mb-5" data-aos="fade-up-right">Leadership Guidance [ Bonus Program ]
                </h2>
                <div class="row align-items-center">
                    <!-- Image Section -->
                    <div class="col-lg-4 mb-2 mb-lg-0" data-aos="zoom-in">
                        <div class="image-wrapper text-center">
                            <img src="assets/images/about_us/9.jpg" alt="Our Mission" class="img-fluid rounded shadow">
                        </div>
                    </div>

                    <!-- Text Section -->
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="p-4">
                            <p class="paragraph text-justify paragraph-1 mb-2">
                                Strong leadership skills set students apart in college and beyond. This service focuses
                                on
                                developing leadership qualities such as decision-making, teamwork, problem-solving, and
                                ethical responsibility. Through mentorship, workshops, and practical exercises, students
                                cultivate confidence, resilience, and the ability to lead with integrity.
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container my-4">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="alert disclaimer text-center border rounded">
                            <h5 class="alert-heading">Disclaimer</h5>
                            <p class="mb-1">
                                Please be advised that our services, policies, and terms are subject to modification at
                                the sole discretion of management to comply with new regulations, enhance user
                                experience, or optimize functionality.
                            </p>
                            <p class="mb-0">
                                We reserve the exclusive right to implement changes without prior notice, although we
                                will endeavor to notify users of any material alterations. By continuing to access and
                                use our services, you acknowledge and consent to any amendments, including updates to
                                our terms of service.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php
    include("base/footer.php");
    ?>
</body>

</html>