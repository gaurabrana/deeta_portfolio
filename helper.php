<?php

function renderMediaUploadForm($pageSlug, $sectionSlug, $sectionId, $existingUpload = null)
{
    $existing = $existingUpload ?? [];

    $formId = "mediaUploadForm-{$pageSlug}-{$sectionSlug}";
    $captionId = "caption-{$pageSlug}-{$sectionSlug}";
    $fileInputId = "media-{$pageSlug}-{$sectionSlug}";
    $previewId = "media-preview-{$pageSlug}-{$sectionSlug}";    
    $positionId = "media-position-{$pageSlug}-{$sectionSlug}";

    $existingCaption = htmlspecialchars($existing['caption'] ?? '');
    $existingPosition = $existing['position'] ?? '';
    $existingMediaPath = $existing['path'] ?? '';
    $existingMediaType = $existing['media_type'] ?? '';
    $existingId = $existing['upload_id'] ?? '';
    $isEdit = !empty($existing);
    $statusId = $isEdit ? "upload-status-{$pageSlug}-{$sectionSlug}-{$existingId}" : "upload-status-{$pageSlug}-{$sectionSlug}";

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
    } else {
        $delete_button_class = "hidden-delete-button";
    }
    echo <<<HTML
                <button type="button" class="btn btn-danger ms-2 delete-media-btn $delete_button_class" data-upload-id="$existingId">Delete</button>
                <div class="mt-2" id="delete-info-$existingId"></div>
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
    $isAdminEditing = true; // this will flag whether to show editing options or not

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
        renderMediaUploadForm($pageSlug, $sectionSlug, $sectionId);

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
            <div class="col-lg-6 col-md-12 {$position}">
                <p class="paragraph text-justify mb-3">{$caption}</p>
            </div>
            <div class="col-lg-6 {$inversePosition} image-wrapper">
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
?>