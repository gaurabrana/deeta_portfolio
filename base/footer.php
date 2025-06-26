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
    max-height: 125px;
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

<?php echo renderFooter($conn); ?>



<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/file_upload.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"
  integrity="sha512-q+4liFwdPC/bNdhUpZx6aXDx/h77yEQtn4I1slHydcbZK34nLaR3cAeYSJshoxIOq3mjEf7xJE8YWIUHMn+oCQ=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!--CUSTOM SCRIPT-->
<script src="assets/js/aos.js"></script>


<?php
function renderFooter($conn)
{
    $query = "
        SELECT p.id AS page_id, p.slug AS page_slug, p.title AS page_title, p.page_url,
               s.slug AS section_slug, s.title AS section_title, s.section_url
        FROM pages p
        LEFT JOIN sections s ON p.id = s.page_id
        WHERE s.visible = 1
        ORDER BY p.id, s.id
    ";

    $result = mysqli_query($conn, $query);

    $pages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $pageId = $row['page_id'];
        $pages[$pageId]['slug'] = $row['page_slug'];
        $pages[$pageId]['url'] = $row['page_url'];
        $pages[$pageId]['title'] = $row['page_title'];

        if ($row['section_slug']) {
            $pages[$pageId]['sections'][] = [
                'slug' => $row['section_slug'],
                'title' => $row['section_title'],
                'page_url' => $row['page_url'],
                'section_url' => $row['section_url']
            ];
        }
    }

    echo <<<HTML
<footer class="bg-green text-white pt-5 pb-4">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-md-3 col-sm-6 footer-column logo-section">
        <a href="about_me.php?page=who_am_i">
        <img src="assets/images/logo.png" alt="Deeta Gurung" class="footer-logo mb-3">
        </a>
      </div>
HTML;

    // Define footer groups by page_slug
    $groups = [
        'schools' => 'Schools',
        'sports' => 'Sports',
        'about_me' => 'Explore',
        'research' => 'Explore',
        'moment_of_truth' => 'Explore',
        'scouting' => 'Community',
        'givingback' => 'Community',
        'gallery_image' => 'Community',
        'gallery_video' => 'Community',
    ];

    $columns = [];
    foreach ($pages as $page) {
        $slug = $page['slug'];
        $group = $groups[$slug] ?? null;
        if (!$group) continue;

        if (!isset($columns[$group])) $columns[$group] = [];

        if (!empty($page['sections'])) {
            foreach ($page['sections'] as $section) {
                $columns[$group][] = [
                    'label' => $section['title'],
                    'href' => getSectionUrl($section['slug'], $section['page_url'], $section['section_url'])
                ];
            }
        } else {
            $columns[$group][] = [
                'label' => $page['title'],
                'href' => $page['url']
            ];
        }
    }

    // Ensure static entries like Photos and Videos
    $columns['Community'][] = ['label' => 'Photos', 'href' => 'gallery_image.php'];
    $columns['Community'][] = ['label' => 'Videos', 'href' => 'gallery_video.php'];

    // Render columns
    foreach ($columns as $heading => $links) {
        echo <<<HTML
      <div class="col-md-2 col-sm-6 footer-column">
        <div class="footer-heading">$heading</div>
        <div class="footer-links">
HTML;
        foreach ($links as $link) {
            $label = htmlspecialchars($link['label']);
            $href = htmlspecialchars($link['href']);
            echo <<<HTML
          <a href="$href">$label</a>
HTML;
        }
        echo <<<HTML
        </div>
      </div>
HTML;
    }

    echo <<<HTML
    </div>
  </div>
</footer>
HTML;
}