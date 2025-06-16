<?php
function renderMediaUploadForm($pageSlug, $sectionSlug, $sectionLabel)
{
    $formId = "mediaUploadForm-{$pageSlug}-{$sectionSlug}";
    $captionId = "caption-{$pageSlug}-{$sectionSlug}";
    $fileInputId = "media-{$pageSlug}-{$sectionSlug}";
    $previewId = "media-preview-{$pageSlug}-{$sectionSlug}";
    $statusId = "upload-status-{$pageSlug}-{$sectionSlug}";
    $positionId = "media-position-{$pageSlug}-{$sectionSlug}";

    echo <<<HTML
    <form id="$formId" enctype="multipart/form-data" class="media-upload-form">
        <div class="row g-3">
            <!-- Hidden inputs to pass location -->
            <input type="hidden" name="page_slug" value="$pageSlug">
            <input type="hidden" name="section_slug" value="$sectionSlug">

            <!-- Caption -->
            <div class="col-12">
                <label for="$captionId" class="form-label">Caption ($sectionLabel)</label>
                <textarea name="caption" id="$captionId" class="form-control" rows="2" placeholder="Write a caption..."></textarea>
            </div>

            <!-- Media Position -->
            <div class="col-md-6">
                <label for="$positionId" class="form-label">Media Position</label>
                <select name="position" id="$positionId" class="form-select">
                    <option value="left">Left</option>
                    <option value="right">Right</option>
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
                <div id="$previewId" class="mt-3"></div>
            </div>

            <!-- Submit Button -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Upload</button>
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

    renderMediaUploadForm($pageSlug, $sectionSlug, $sectionTitle);

    echo <<<HTML
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
HTML;

    // === FETCH MEDIA CONTENT FROM uploads TABLE ===
    $stmt = $conn->prepare("SELECT path, caption, media_type, position FROM uploads WHERE section_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $sectionId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($media = $result->fetch_assoc()) {
        $mediaHtml = '';
        $mediaPath = htmlspecialchars($media['path']);
        $caption = htmlspecialchars($media['caption']);
        $position = ($media['position'] === 'left') ? 'order-lg-1' : 'order-lg-2';
        $inversePosition = ($media['position'] === 'left') ? 'order-lg-2' : 'order-lg-1';

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

?>