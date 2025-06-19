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
            <input type="hidden" name="preview_id" value="$previewId">
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
    } else {
        $delete_button_class = "hidden-delete-button";
    }
    echo <<<HTML
                <button type="button" class="btn btn-danger ms-2 delete-media-btn $delete_button_class" data-upload-id="$sectionId">Delete</button>
                <div class="mt-2" id="delete-info-$sectionId"></div>
HTML;

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
    $stmt = $conn->prepare("SELECT path, caption, media_type, position FROM uploads WHERE section_id = ? ORDER BY id DESC LIMIT 1");
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

    if ($existingUpload) {
        $mediaHtml = '';
        $mediaPath = htmlspecialchars($existingUpload['path']);
        $caption = htmlspecialchars($existingUpload['caption']);
        $position = ($existingUpload['position'] === 'right') ? 'order-lg-1' : 'order-lg-2';
        $inversePosition = ($existingUpload['position'] === 'right') ? 'order-lg-2' : 'order-lg-1';

        if ($existingUpload['media_type'] === 'image') {
            $imgSrc = "assets/images/uploads/{$mediaPath}";
            $videoSrc = ''; // empty
            $imgClass = 'img-fluid rounded shadow';
            $videoClass = 'img-fluid rounded shadow hide-empty-asset';
        } elseif ($existingUpload['media_type'] === 'video') {
            $imgSrc = ''; // empty
            $videoSrc = "assets/images/uploads/{$mediaPath}";
            $imgClass = 'img-fluid rounded shadow hide-empty-asset';
            $videoClass = 'img-fluid rounded shadow';
        }

        $mediaHtml = "
    <img src='{$imgSrc}' alt='Media' class='{$imgClass}' />
    <video src='{$videoSrc}' controls class='{$videoClass}'></video>
";



        echo <<<HTML
    <div id="media-preview-container-$sectionId">
        <div class="row justify-content-center align-items-center mb-4">
            <div class="col-lg-6 col-md-12 {$position}" data-aos="fade-left">
                <p class="paragraph text-justify mb-3">{$caption}</p>
            </div>
            <div class="col-lg-6 {$inversePosition} image-wrapper" data-aos="zoom-out-down">
                {$mediaHtml}
            </div>
        </div>
    </div>
HTML;
    } else {
        $mediaHtml = "<img src='' alt='Media' class='img-fluid rounded shadow hide-empty-asset' /><video src='' controls class='img-fluid rounded shadow hide-empty-asset'></video>";
        echo <<<HTML
    <div id="media-preview-container-$sectionId">
        <div class="row justify-content-center align-items-center mb-4">
            <div class="col-lg-6 col-md-12" data-aos="fade-left">
                <p class="paragraph text-justify mb-3"></p>
            </div>
            <div class="col-lg-6 image-wrapper" data-aos="zoom-out-down">   
                $mediaHtml             
            </div>
        </div>
    </div>
HTML;
    }

    echo "</div></section>";
}

function renderMediaSectionFromArray($mediaItems = [])
{

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
}


function renderSingleMediaItem($mediaItem)
{
    renderMediaSectionFromArray([$mediaItem]);
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