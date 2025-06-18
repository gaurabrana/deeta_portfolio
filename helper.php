<?php

function renderMediaUploadForm($conn, $pageSlug, $sectionSlug, $sectionId, $sectionLabel, $existingUpload = null)
{
    $existing = $existingUpload ?? [];

    $formId = "mediaUploadForm-{$pageSlug}-{$sectionSlug}";
    $captionId = "caption-{$pageSlug}-{$sectionSlug}";
    $fileInputId = "media-{$pageSlug}-{$sectionSlug}";
    $previewId = "media-preview-{$pageSlug}-{$sectionSlug}";
    $statusId = "upload-status-{$pageSlug}-{$sectionSlug}";
    $positionId = "media-position-{$pageSlug}-{$sectionSlug}";

    $existingCaption = htmlspecialchars($existing['caption'] ?? '');
    $existingPosition = $existing['position'] ?? '';
    $existingMediaPath = $existing['path'] ?? '';
    $existingMediaType = $existing['media_type'] ?? '';
    $isEdit = !empty($existing);

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

            <!-- Caption -->
            <div class="col-12">
                <label for="$captionId" class="form-label">Caption ($sectionLabel)</label>
                <textarea name="caption" id="$captionId" class="form-control" rows="2" placeholder="Write a caption...">$existingCaption</textarea>
            </div>

            <!-- Media Position -->
            <div class="col-md-6">
                <label for="$positionId" class="form-label">Media Position</label>
                <select name="position" id="$positionId" class="form-select">
                    <option value="left" $leftSelected>Left</option>
                    <option value="right" $rightSelected>Right</option>
                </select>
            </div>

            <!-- File Upload -->
            <div class="col-md-6">
                <label for="$fileInputId" class="form-label">Image or Video</label>
                <input type="file" name="media" id="$fileInputId"
                       class="form-control media-input"
                       data-form="$formId"
                       data-preview="$previewId"
                       data-status="$statusId"
                       accept="image/*,video/*">
                <small class="form-text text-muted">Supported: JPG, PNG, WEBP, GIF, MP4, WebM, MOV (max 50MB)</small>
                <div id="$previewId" class="mt-3">$previewHtml</div>
            </div>

            <!-- Submit and Delete Buttons -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary">$buttonLabel</button>
HTML;

    if ($isEdit) {
        echo <<<HTML
                <button type="button" class="btn btn-danger ms-2 delete-media-btn" data-upload-id="$sectionId">Delete</button>
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
    $stmt = $conn->prepare("SELECT path, caption, media_type, position FROM uploads WHERE section_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $sectionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $uploads = $result->fetch_all(MYSQLI_ASSOC);  // fetch all rows at once
    $existingUpload = count($uploads) > 0 ? $uploads[0] : null;


    echo <<<HTML
    <section id="{$sectionSlug}" class="py-5">
        <div class="container">
            <p class="heading-3 text-center mb-5" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="700">
                {$sectionTitle}
            </p>
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
    renderMediaUploadForm($conn, $pageSlug, $sectionSlug, $sectionId, $sectionTitle, $existingUpload);

    echo <<<HTML
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
HTML;

    foreach ($uploads as $media) {
        $mediaHtml = '';
        $mediaPath = htmlspecialchars($media['path']);
        $caption = htmlspecialchars($media['caption']);
        $position = ($media['position'] === 'right') ? 'order-lg-1' : 'order-lg-2';
        $inversePosition = ($media['position'] === 'right') ? 'order-lg-2' : 'order-lg-1';

        if ($media['media_type'] === 'image') {
            $mediaHtml = "<img src='assets/images/uploads/{$mediaPath}' alt='Media' class='img-fluid rounded shadow'>";
        } elseif ($media['media_type'] === 'video') {
            $mediaHtml = "<video src='assets/images/uploads/{$mediaPath}' controls class='img-fluid rounded shadow'></video>";
        }

        echo <<<HTML
        <div class="row justify-content-center align-items-center mb-4 new-media-item">
            <div class="col-lg-6 col-md-12 {$position}" data-aos="fade-left">
                <p class="paragraph text-justify mb-3">{$caption}</p>
            </div>
            <div class="col-lg-6 {$inversePosition} image-wrapper" data-aos="zoom-out-down">
                {$mediaHtml}
            </div>
        </div>
HTML;
    }

    echo "</div></section>";
}

function renderMediaSectionFromArray($sectionSlug, $sectionTitle, $mediaItems = [])
{
    echo <<<HTML
    <section id="{$sectionSlug}" class="py-5">
        <div class="container">        
HTML;

    // === RENDER PROVIDED MEDIA ITEMS ===
    foreach ($mediaItems as $media) {
        $mediaPath = htmlspecialchars($media['path']);
        $caption = htmlspecialchars($media['caption']);
        $position = ($media['position'] === 'left') ? 'order-lg-1' : 'order-lg-2';
        $inversePosition = ($media['position'] === 'left') ? 'order-lg-2' : 'order-lg-1';

        if ($media['media_type'] === 'image') {
            $mediaHtml = "<img src='assets/images/uploads/{$mediaPath}' alt='Media' class='img-fluid rounded shadow'>";
        } elseif ($media['media_type'] === 'video') {
            $mediaHtml = "<video src='assets/images/uploads/{$mediaPath}' controls class='img-fluid rounded shadow'></video>";
        } else {
            $mediaHtml = '';
        }

        echo <<<HTML
        <div class="row justify-content-center align-items-center mb-4">
            <div class="col-lg-6 col-md-12 {$position}" data-aos="fade-left">
                <p class="paragraph text-justify mb-3">{$caption}</p>
            </div>
            <div class="col-lg-6 {$inversePosition} image-wrapper" data-aos="zoom-out-down">
                {$mediaHtml}
            </div>
        </div>
HTML;
    }

    echo "</div></section>";
}


function renderSingleMediaItem($sectionSlug, $sectionTitle, $mediaItem)
{
    renderMediaSectionFromArray($sectionSlug, $sectionTitle, [$mediaItem]);
}

function getExistingUploadBySectionId($conn, $sectionId)
{

    if (!$sectionId || !is_numeric($sectionId)) {
        return false; // safeguard against invalid input
    }
    $stmt = $conn->prepare("SELECT * FROM uploads WHERE section_id = ? LIMIT 1");
    $stmt->execute([$sectionId]);
    return $stmt->fetch();
}

?>