<?php
//Convert images to webp
function convert_to_webp($upload)
{
    $image_path = $upload['file'];
    // % compression (0-100)
    $compression_quality = 80;
    $supported_mime_types = array(
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    );
    $image_info = getimagesize($image_path);
    if ($image_info !== false && array_key_exists($image_info['mime'], $supported_mime_types)) {
        $image = imagecreatefromstring(file_get_contents($image_path));
        if ($image) {
            if (imageistruecolor($image)) {
                $webp_path = preg_replace('/\.(jpg|jpeg|png)$/', '.webp', $image_path);
                imagewebp($image, $webp_path, $compression_quality);
                $upload['file'] = $webp_path;
                $upload['type'] = 'image/webp';
                // Delete corner image
                unlink($image_path);
            } else {
                // If is image 8-bit, doing uncompress
                $upload['file'] = $image_path;
                $upload['type'] = $image_info['mime'];
            }
        }
    }
    return $upload;
}
function convert_to_webp_upload($upload)
{
    $upload = convert_to_webp($upload);
    return $upload;
}
add_filter('wp_handle_upload', 'convert_to_webp_upload');

// end convert images to webp
?>