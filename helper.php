<?php

function renderMediaUploadForm($pageSlug, $sectionSlug, $sectionId, $existingUpload = null)
{
    $existing = $existingUpload ?? [];

    $formId = "mediaUploadForm-{$pageSlug}-{$sectionSlug}";
    $captionId = "caption-{$pageSlug}-{$sectionSlug}";
    $fileInputId = "media-{$pageSlug}-{$sectionSlug}";
    $positionId = "media-position-{$pageSlug}-{$sectionSlug}";

    $existingCaption = htmlspecialchars($existing['caption'] ?? '');
    $existingPosition = $existing['position'] ?? '';
    $existingMediaPath = $existing['path'] ?? '';
    $existingMediaType = $existing['media_type'] ?? '';
    $existingId = $existing['upload_id'] ?? '';
    $isEdit = !empty($existing);
    $statusId = $isEdit ? "upload-status-{$pageSlug}-{$sectionSlug}-{$existingId}" : "upload-status-{$pageSlug}-{$sectionSlug}";
    $previewId = $isEdit ? "media-preview-{$pageSlug}-{$sectionSlug}-{$existingId}" : "media-preview-{$pageSlug}-{$sectionSlug}";

    $leftSelected = ($existingPosition === 'left') ? 'selected' : '';
    $rightSelected = ($existingPosition === 'right') ? 'selected' : '';
    $buttonLabel = $isEdit ? 'Update' : 'Upload';

    $previewHtml = '';
    if ($existingMediaPath) {
        $safeUrl = 'assets/images/uploads/' . htmlspecialchars($existingMediaPath);
        if ($existingMediaType === 'image') {
            $previewHtml = "<img src=\"$safeUrl\" style=\"max-width:150px; max-height:150px; border:1px solid #ccc; border-radius:6px;\" />";
        } else {
            $previewHtml = "<video controls style=\"max-width:300px; max-height:300px; border:1px solid #ccc; border-radius:6px;\" src=\"$safeUrl\"></video>";
        }
    }

    echo <<<HTML
    <form id="$formId" enctype="multipart/form-data" class="media-upload-form">
        <div class="row g-3">
            <!-- Hidden inputs to pass location -->
            <input type="hidden" name="page_slug" value="$pageSlug">
            <input type="hidden" name="section_slug" value="$sectionSlug">
            <input type="hidden" name="section_id" value="$sectionId">
            <input type="hidden" name="preview_id" value="$previewId">
            <input type="hidden" name="upload_id" value="$existingId">
            <input type="hidden" id="upload-form-container-$sectionId" name="form_id" value="$formId">

            <!-- Caption -->
            <div class="col-12">
                <label for="$captionId" class="form-label">Text</label>
                <textarea name="caption" required id="$captionId" class="form-control editable-field" rows="2" placeholder="Write some description...">$existingCaption</textarea>
            </div>

            <!-- Media Position -->
            <div class="col-md-6">
                <label for="$positionId" class="form-label">Media Position</label>
                <select name="position" id="$positionId" class="form-select editable-field">
                    <option value="left" $leftSelected>Left</option>
                    <option value="right" $rightSelected>Right</option>
                </select>
            </div>

            <!-- File Upload -->
            <div class="col-md-6">
                <label for="$fileInputId" class="form-label">Image or Video</label>
                <input type="file" name="media" id="$fileInputId"
                       class="form-control media-input editable-field"
                       data-form="$formId"
                       data-preview="$previewId"
                       data-status="$statusId"
                       accept="image/*,video/*"                       
                       >
                <small class="form-text text-muted">Supported: JPG, PNG, WEBP, GIF, MP4, WebM, MOV (max 50MB)</small>
                <div id="$previewId" class="mt-3">
HTML;
    if ($isEdit) {
        echo '<h6>Existing File :</h6>';
    }
    echo <<<HTML
                    $previewHtml
                </div>
            </div>

            <!-- Submit and Delete Buttons -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary">$buttonLabel</button>
HTML;

    if ($isEdit) {
        $delete_button_class = "visible-delete-button";
        echo <<<HTML
                <button type="button" class="btn btn-danger ms-2 delete-media-btn $delete_button_class" data-upload-id="$existingId">Delete</button>
                <div class="mt-2" id="delete-info-$existingId"></div>
HTML;
    }


    echo <<<HTML
                <div class="mt-2" id="$statusId"></div>
            </div>
        </div>
    </form>
HTML;
}

function renderMediaSection($conn, $pageSlug, $sectionId, $sectionSlug, $sectionTitle)
{
    $accordionId = "accordion-" . $sectionSlug;
    $collapseId = "collapse-" . $sectionSlug;
    $headingId = "heading-" . $sectionSlug;
    // === FETCH MEDIA CONTENT FROM uploads TABLE ===
    $stmt = $conn->prepare("
    SELECT u.path, u.caption, u.media_type, u.position, su.upload_id 
    FROM section_upload su
    JOIN uploads u ON u.id = su.upload_id
    WHERE su.section_id = ?
    ORDER BY su.id DESC    
");
    $stmt->bind_param("i", $sectionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $uploads = $result->fetch_all(MYSQLI_ASSOC);  // fetch all rows at once    
    $isAdminEditing = (isset($_SESSION) && isset($_SESSION['logged_in'])); // this will flag whether to show editing options or not


    echo <<<HTML
    <section id="{$sectionSlug}" class="py-5">
        <div class="container">
            <p class="heading-3 text-center mb-5" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="700">
                {$sectionTitle}
            </p>
HTML;
    if ($isAdminEditing) {
        echo <<<HTML
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="accordion" id="{$accordionId}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="{$headingId}">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#{$collapseId}"
                                            aria-expanded="false" aria-controls="{$collapseId}">
                                        Manage - {$sectionTitle}
                                    </button>
                                </h2>
                                <div id="{$collapseId}" class="accordion-collapse collapse"
                                     aria-labelledby="{$headingId}" data-bs-parent="#{$accordionId}">
                                    <div class="accordion-body">
    HTML;
        if ($pageSlug == 'about_me' && $sectionSlug == 'resume') {
            $isEdit = !empty($uploads);
            $previewId = "resume-media-preview";
            $statusId = 'resume-upload-status';
            $uploadId = $isEdit ? $uploads[0]['upload_id'] : '';
            echo <<<HTML
    <form id="resumeUploadForm" enctype="multipart/form-data" method="post">
        <div class="mb-3">
            <label for="resumeFile" class="form-label">Upload Resume (PDF only):</label>
            <input type="file" name="media" data-preview="$previewId"
                       data-status="$statusId" class="form-control" id="resumeFile" name="resume" accept=".pdf" required>
        </div>
        <input type="hidden" name="page_slug" value="$pageSlug">
        <input type="hidden" name="section_slug" value="$sectionSlug">
        <input type="hidden" name="section_id" value="$sectionId">
        <input type="hidden" name="upload_id" value="$uploadId">
        <div class="mt-2" id="$statusId"></div>
    HTML;
            if ($isEdit) {

                echo '<button type="submit" class="btn btn-primary">Update</button>
                <button type="button" id="remove-upload-resume-' . $uploadId . '" class="btn btn-danger delete-resume-btn visible-delete-button">Delete Existing</button>';
            } else {
                echo '<button type="submit" class="btn btn-primary">Upload</button>';
            }

            echo <<<HTML
        <div id="$previewId" class="mt-3">        
    </form>
    HTML;
        } else {

            renderMediaUploadForm($pageSlug, $sectionSlug, $sectionId);
        }

        echo <<<HTML
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    HTML;
    }

    echo "<div id='section-media-container-$sectionId'>";
    foreach ($uploads as $existingUpload) {
        renderSingleMediaItem($sectionId, $existingUpload, $pageSlug, $sectionSlug, $isAdminEditing);
    }
    echo '</div>';

    echo "</div></section>";
}

function renderSingleMediaItem($sectionId, $existingUpload, $pageSlug = null, $sectionSlug = null, $isAdminEditing)
{
    $uploadId = $existingUpload['upload_id'];
    $uniqueId = "accordion-{$sectionId}-{$uploadId}";
    $mediaHtml = '';
    $mediaPath = htmlspecialchars($existingUpload['path']);
    $caption = htmlspecialchars($existingUpload['caption']);
    $position = ($existingUpload['position'] === 'right') ? 'order-lg-1' : 'order-lg-2';
    $inversePosition = ($existingUpload['position'] === 'right') ? 'order-lg-2' : 'order-lg-1';

    if ($existingUpload['media_type'] === 'pdf') {
        $path = "assets/images/uploads/{$mediaPath}";

        echo <<<HTML
        <div class="row">
        <div class="col-md-12">
        <input type="hidden" id="pdfPath" value="$path">
        <div id="pdf-loading" style="display: none; font-style: italic;" class="my-2">Loading PDF preview...</div>
        <div id="pdf-preview-container" class="mt-3">            
            </div>            
        </div>
        <div class="mt-2" style="display:none;" id="resume-download-button">
                <a href="{$path}" download class="btn btn-secondary">Download PDF</a>
        </div>
    </div>
HTML;
        return;
    }

    if ($existingUpload['media_type'] === 'image') {
        $imgSrc = "assets/images/uploads/{$mediaPath}";
        $videoSrc = '';
        $imgClass = 'img-fluid rounded shadow';
        $videoClass = 'img-fluid rounded shadow hide-empty-asset';
    } elseif ($existingUpload['media_type'] === 'video') {
        $imgSrc = '';
        $videoSrc = "assets/images/uploads/{$mediaPath}";
        $imgClass = 'img-fluid rounded shadow hide-empty-asset';
        $videoClass = 'img-fluid rounded shadow';
    }

    $mediaHtml = "
        <img src='{$imgSrc}' alt='Media' class='{$imgClass}' />
        <video src='{$videoSrc}' controls class='{$videoClass}'></video>
    ";

    echo <<<HTML
    <div id="media-preview-container-$uploadId" class="uploaded-asset-container">
        <div class="row justify-content-center align-items-center mb-4">
            <div class="col-lg-8 col-md-12 {$position}">
                <p class="paragraph text-justify mb-3">{$caption}</p>
            </div>
            <div class="col-lg-4 {$inversePosition} image-wrapper d-flex">
                {$mediaHtml}
            </div>            
        </div>
HTML;

    // Only render edit section if connection details are provided    
    echo <<<HTML
        <div class="row justify-content-end align-items-center mb-2">
            <div class="col-lg-11">
                <div class="collapse" id="$uniqueId">
HTML;
    renderMediaUploadForm($pageSlug, $sectionSlug, $sectionId, $existingUpload);
    echo <<<HTML
                </div>
            </div>
HTML;
    if ($isAdminEditing) {
        echo <<<HTML
            <div class="col-lg-1 text-end">
                <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#$uniqueId" aria-expanded="false" aria-controls="$uniqueId">
                    Edit
                </button>
            </div>
HTML;
    }
    echo <<<HTML
        </div>
    </div>
HTML;
}

// Function to generate accordion form for adding new gallery items
function generateGalleryAccordionForm($section_id, $type = 'image')
{


    $isVideo = ($type === 'video');
    $title = $isVideo ? 'Add New Video' : 'Add New Image';
    $formId = $isVideo ? 'videoGalleryForm' : 'imageGalleryForm';
    $typeCapitalized = ucfirst($type);

    ob_start();

    echo <<<HTML
    <div class="accordion mb-4" id="galleryAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse{$typeCapitalized}" aria-expanded="false"
                    aria-controls="collapse{$typeCapitalized}">
                    {$title}
                </button>
            </h2>
            <div id="collapse{$typeCapitalized}" class="accordion-collapse collapse"
                data-bs-parent="#galleryAccordion">
                <div class="accordion-body">
                    <form id="{$formId}" enctype="multipart/form-data">
                        <input type="hidden" name="section_slug" value="gallery_{$type}">
                        <input type="hidden" name="page_slug" value="gallery">
                        <input type="hidden" name="section_id" value="$section_id">
                        <div class="mb-3">
    HTML;

    if ($isVideo) {
        echo <<<HTML
                            <div class="mb-3">
                                <label class="form-label">Video Source</label>
                                <div class="form-check">
                                    <input class="form-check-input video-source" type="radio" name="videoSource" id="youtubeSource" value="youtube" checked>
                                    <label class="form-check-label" for="youtubeSource">
                                        YouTube URL
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input video-source" type="radio" name="videoSource" id="uploadSource" value="upload">
                                    <label class="form-check-label" for="uploadSource">
                                        Upload Video File
                                    </label>
                                </div>
                            </div>
                            
                            <div id="youtubeSourceContainer">
                                <label for="videoUrl" class="form-label">YouTube Video URL</label>
                                <div class="input-group">
                                    <input type="url" class="form-control" id="videoUrl" name="videoUrl"
                                        placeholder="https://www.youtube.com/watch?v=..." required>
                                    <button class="btn btn-outline-secondary" type="button" id="validateYoutubeBtn">
                                        <i class="bi bi-check-circle"></i> Validate
                                    </button>
                                </div>
                                <div id="youtubePreview" class="mt-2" style="display:none;">
                                    <div class="alert alert-success mb-2">Valid YouTube URL</div>
                                    <img id="youtubeThumbnail" src="" class="img-thumbnail" style="max-width: 200px;">
                                    <input type="hidden" id="youtubeId" name="youtubeId">
                                </div>
                                <div id="youtubeError" class="alert alert-danger mt-2" style="display:none;"></div>
                            </div>
                            
                            <div id="uploadSourceContainer" style="display:none;">
                                <label for="videoUpload" class="form-label">Select Video File</label>
                                <input class="form-control" type="file" id="videoUpload" name="videoUpload[]" accept="video/*" multiple>
                                <div class="form-text">Supported formats: MP4, WebM, OGG (Max 40MB)</div>
                                <div id="videoUploadError" class="text-danger mt-2"></div>
                            </div>
        HTML;
    } else {
        echo <<<HTML
                            <label for="imageUpload" class="form-label">Select Image(s)</label>
                            <input class="form-control" type="file" id="imageUpload" name="imageUpload[]" accept="image/*"
                                multiple required>
                            <div class="form-text">Supported formats: JPG, PNG, GIF</div>
        HTML;
    }

    echo <<<HTML
                        </div>                      
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                    <div id="{$formId}Preview" class="mt-3 preview-container"></div>
                    <div id="{$formId}Response" class="mt-3"></div>
                    <div id="imageResizeProgress" class="text-info my-2" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
    HTML;
    return ob_get_clean();
}

// Function to get gallery items from database
function getGalleryItems($conn, $type = 'image')
{
    $isVideo = ($type === 'video');
    $sectionSlug = $isVideo ? 'gallery_video' : 'gallery_image';

    $query = "SELECT u.path, u.caption, u.media_type, su.upload_id, s.id, s.slug, p.slug as pageSlug 
              FROM sections s
              JOIN section_upload su ON s.id = su.section_id
              JOIN uploads u ON u.id = su.upload_id
              JOIN pages p ON s.page_id = p.id
              WHERE s.slug = ? 
              AND p.slug = 'gallery'";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $sectionSlug);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        if ($isVideo && strpos($row['path'], 'https') !== false) {
            $youtubeId = $row['caption'];

            if ($youtubeId) {
                $items[] = [
                    'id' => $row['upload_id'],
                    'path' => $row['path'],
                    'youtube_id' => $youtubeId,
                    'caption' => $row['caption']
                ];
            }
        } else {
            // For images, path is the full URL to the image
            $items[] = [
                'id' => $row['upload_id'],
                'path' => $row['path'],
                'thumbnail' => $row['path'], // Assuming same image for thumbnail
                'caption' => $row['caption']
            ];
        }
    }

    return $items;
}

// Function to generate gallery items list
function generateGalleryItems($conn, $type = 'image')
{
    $items = getGalleryItems($conn, $type);
    $isVideo = ($type === 'video');
    $containerClass = $isVideo ? 'video-gallery-container' : 'gallery-container';
    echo <<<HTML
    
    <div class="{$containerClass}">
        <ul class="gallery-list list-unstyled row">
    HTML;

    foreach ($items as $item) {
        $id = htmlspecialchars($item['id']);
        $caption = htmlspecialchars($item['caption']);

        if ($isVideo) {
            if (!empty($item['youtube_id'])) {
                $youtubeId = htmlspecialchars($item['youtube_id']);
                $url = htmlspecialchars($item['path']);

                echo <<<HTML
                <li>
                    <div class="video-delete-overlay" data-id="{$id}" data-type="video">
                            <button class="btn btn-danger btn-sm">Delete</button>                            
                        </div>
                    <a href="{$url}" data-fancybox="video-gallery">
                        <img src="https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg" 
                            class="img-fluid" alt="YouTube video thumbnail">                        
                    </a>
                </li>
                HTML;
            } else {
                $videoPath = htmlspecialchars($item['path']);

                echo <<<HTML
                <li>
                     <div class="video-delete-overlay" data-id="{$id}" data-type="video">
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </div>
                    <a href="assets/images/gallery_uploads/video/{$videoPath}" data-fancybox="video-gallery">
                        <video class="img-fluid" controls>
                            <source src="assets/images/gallery_uploads/video/{$videoPath}" type="video/mp4">
                        </video>
                       
                    </a>
                </li>
                HTML;
            }
        } else {
            $imagePath = htmlspecialchars($item['path']);

            echo <<<HTML
            <li class="position-relative">
                <a href="assets/images/gallery_uploads/image/{$imagePath}" data-fancybox="gallery" 
                    data-caption="{$caption}">
                    <figure>
                        <img src="assets/images/gallery_uploads/image/{$imagePath}" 
                            class="img-fluid" alt="{$caption}">                        
                    </figure>                    
                </a>
                <div class="delete-overlay" data-id="{$id}" data-type="image">
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </div>
            </li>
            HTML;
        }
    }

    echo <<<HTML
        </ul>
    </div>
    HTML;
}

function getSectionId($conn, $type = 'image')
{
    // Validate the type parameter
    $validTypes = ['image', 'video'];
    if (!in_array($type, $validTypes)) {
        throw new InvalidArgumentException("Invalid section type. Must be 'image' or 'video'.");
    }

    // Prepare the query with parameterized input
    $query = "SELECT id FROM sections WHERE slug = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new RuntimeException("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameters and execute
    $slug = 'gallery_' . $type;
    $stmt->bind_param("s", $slug);

    if (!$stmt->execute()) {
        throw new RuntimeException("Execution failed: " . $stmt->error);
    }

    // Get result
    $result = $stmt->get_result();

    if (!$result) {
        throw new RuntimeException("Failed to get result: " . $stmt->error);
    }

    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        throw new RuntimeException("No section found with slug: gallery_$type");
    }

    return $row['id'];
}

function renderNavbar($conn)
{
    $current_page = basename($_SERVER['PHP_SELF']);

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
        $pages[$pageId]['slug'] = $row['page_slug'];        // e.g. 'sports'
        $pages[$pageId]['url'] = $row['page_url'];          // e.g. 'sports.php'
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
<div class="navbar-collapse justify-content-center mt-3">
    <ul class="navbar-nav">
HTML;

    foreach ($pages as $page) {
        $pageSlug = $page['slug'];           // e.g. 'sports'
        $pageUrl = $page['url'];             // e.g. 'sports.php'
        $pageTitle = htmlspecialchars($page['title']);
        $navClass = "{$pageSlug}-nav-item";
        $dropdownClass = "{$pageSlug}-dropdown";
        $dropdownCommonClass = "nav-item-dropdown";
        $isActive = ($current_page === basename($pageUrl)) ? 'active-link' : '';

        $sectionCount = isset($page['sections']) ? count($page['sections']) : 0;

        // Case 1: No sections
        if ($sectionCount === 0) {
            echo <<<HTML
        <li class="nav-item mx-2 $navClass $isActive">
            <a class="nav-link" href="$pageUrl">$pageTitle</a>
        </li>
HTML;
        }
        // Case 2: One section — direct link to section
        elseif ($sectionCount === 1) {
            $section = $page['sections'][0];
            $href = getSectionUrl($section['slug'], $section['page_url'], $section['section_url']);
            echo <<<HTML
        <li class="nav-item mx-2 $navClass $isActive">
            <a class="nav-link" href="$href">$pageTitle</a>
        </li>
HTML;
        }
        // Case 3: Multiple sections
        else {
            echo <<<HTML
        <li class="nav-item mx-2 dropdown $dropdownCommonClass $navClass $isActive">
            <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                $pageTitle
            </a>
            <ul class="$dropdownClass dropdown-menu">
HTML;

            // Special handling for about_me
            if ($pageUrl === 'about_me.php') {
                $whoAmISections = [];
                $otherSections = [];

                foreach ($page['sections'] as $section) {
                    if ($section['slug'] === 'resume') {
                        $otherSections[] = $section;
                    } else {
                        $whoAmISections[] = $section;
                    }
                }

                // Who am I submenu
                if (!empty($whoAmISections)) {
                    $parentHref = getSectionUrl('who-am-i', $whoAmISections[0]['page_url'], $whoAmISections[0]['section_url']);
                    echo <<<HTML
                <li class="dropdown-submenu position-relative dropend">
                    <a class="dropdown-item dropdown-toggle" href="$parentHref">Who am I</a>
                    <ul class="dropdown-menu sub-dropdown">
HTML;
                    foreach ($whoAmISections as $section) {
                        $href = getSectionUrl($section['slug'], $section['page_url'], $section['section_url']);
                        $title = htmlspecialchars($section['title']);
                        echo <<<HTML
                        <li><a class="dropdown-item" href="$href">$title</a></li>
HTML;
                    }
                    echo <<<HTML
                    </ul>
                </li>
HTML;
                }

                // Other sections like resume
                foreach ($otherSections as $section) {
                    $href = getSectionUrl($section['slug'], $section['page_url'], $section['section_url']);
                    $title = htmlspecialchars($section['title']);
                    echo <<<HTML
                <li><a class="dropdown-item" href="$href">$title</a></li>
HTML;
                }
            } else {
                // Default dropdown list
                foreach ($page['sections'] as $section) {
                    $href = getSectionUrl($section['slug'], $section['page_url'], $section['section_url']);
                    $title = htmlspecialchars($section['title']);
                    echo <<<HTML
                <li><a class="dropdown-item" href="$href">$title</a></li>
HTML;
                }
            }

            echo <<<HTML
            </ul>
        </li>
HTML;
        }
    }

    // Logout if logged in
    if (isset($_SESSION['logged_in'])) {
        echo <<<HTML
        <li class="nav-item mx-2">
            <a class="nav-link" href="database/admin_logout.php">Logout</a>
        </li>
HTML;
    }

    echo <<<HTML
    </ul>
</div>
HTML;
}


function getSectionUrl($sectionSlug, $page_url, $section_url)
{
    if (!empty($page_url)) {
        return $page_url . '#' . $sectionSlug;
    }

    if (!empty($section_url)) {
        return $section_url;
    }

    // fallback
    return 'index.php';
}

?>