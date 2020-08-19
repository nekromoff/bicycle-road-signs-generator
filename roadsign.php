<?php
if (!isset($_GET['sign']) or !isset($_GET['type']) or !isset($_GET['number']) or ($_GET['sign'] != 'route' and $_GET['sign'] != 'direction') or ($_GET['type'] != 'straight' and $_GET['type'] != 'left' and $_GET['type'] != 'right')) {
    $image = imagecreatefrompng('sources/route-straight.png');
    $transparent = imagecolorallocate($image, 0, 0, 0);
    imagecolortransparent($image, $transparent);
    $color = imagecolorallocate($image, 170, 0, 0);
    imagettftext($image, 200, 0, 550, 400, $color, 'fonts/Tern-Regular.ttf', 'chyba');
    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
}
if ($_GET['sign'] == 'route') {
    $image = imagecreatefrompng('sources/route-' . $_GET['type'] . '.png');
    $transparent = imagecolorallocate($image, 0, 0, 0);
    imagecolortransparent($image, $transparent);
    $color = imagecolorallocate($image, 28, 107, 61);
    $image_width = 1770;
    $image_height = 1180;
    $font_size = 300;
    $box = imagettfbbox($font_size, 0, 'fonts/Tern-Regular.ttf', $_GET['number']);
    $left = $box[6];
    $right = $box[4];
    $width = $right - $left;
    $x = ($image_width / 2) - $width / 2;
    // compensate for left/right arrow
    if ($_GET['type'] == 'left' or $_GET['type'] == 'right') {
        $x = ($image_width / 2) - $width / 1.3;
    }
    imagettftext($image, $font_size, 0, $x, 500, $color, 'fonts/Tern-Regular.ttf', $_GET['number']);
} elseif ($_GET['sign'] == 'direction') {
    $image = imagecreatefrompng('sources/direction-' . $_GET['type'] . '.png');
    $transparent = imagecolorallocate($image, 0, 0, 0);
    imagecolortransparent($image, $transparent);
    $color = imagecolorallocate($image, 28, 107, 61);
    $image_width = 2000;
    $image_height = 444;
    $font_size = 120;
    $box = imagettfbbox($font_size, 0, 'fonts/Tern-Regular.ttf', $_GET['number']);
    $left = $box[6];
    $right = $box[4];
    $width = $right - $left;
    $x_number = 210 - $width / 2;
    imagettftext($image, $font_size, 0, $x_number, 180, $color, 'fonts/Tern-Regular.ttf', $_GET['number']);
    $targets = explode('|', $_GET['targets']);
    if (count($targets) == 2) {
        $x_target = 500;
        $box = imagettfbbox($font_size, 0, 'fonts/Tern-Regular.ttf', $targets[1]);
        $left = $box[6];
        $right = $box[4];
        $width = $right - $left;
        $x_distance = 1730 - $width;
        $y = 285;
        imagettftext($image, $font_size, 0, $x_target, $y, $color, 'fonts/Tern-Regular.ttf', $targets[0]);
        imagettftext($image, $font_size, 0, $x_distance, $y, $color, 'fonts/Tern-Regular.ttf', $targets[1]);
    } else {
        $x_target = 500;
        $box = imagettfbbox($font_size, 0, 'fonts/Tern-Regular.ttf', $targets[1]);
        $left = $box[6];
        $right = $box[4];
        $width = $right - $left;
        $x_distance = 1730 - $width;
        $y = 175;
        imagettftext($image, $font_size, 0, $x_target, $y, $color, 'fonts/Tern-Regular.ttf', $targets[0]);
        imagettftext($image, $font_size, 0, $x_distance, $y, $color, 'fonts/Tern-Regular.ttf', $targets[1]);
        $box = imagettfbbox($font_size, 0, 'fonts/Tern-Regular.ttf', $targets[3]);
        $left = $box[6];
        $right = $box[4];
        $width = $right - $left;
        $x_distance = 1730 - $width;
        $y = 360;
        imagettftext($image, $font_size, 0, $x_target, $y, $color, 'fonts/Tern-Regular.ttf', $targets[2]);
        imagettftext($image, $font_size, 0, $x_distance, $y, $color, 'fonts/Tern-Regular.ttf', $targets[3]);
    }
}
if (isset($_GET['width'])) {
    $resize_width = (int) $_GET['width'];
    $resize_ratio = $resize_width / $image_width;
    $resize_height = $image_height * $resize_ratio;
    $resize_image = imagecreate($resize_width, $resize_height);
    $transparent = imagecolorallocate($resize_image, 0, 0, 0);
    imagecolortransparent($resize_image, $transparent);
    imagecopyresampled($resize_image, $image, 0, 0, 0, 0, $resize_width, $resize_height, $image_width, $image_height);
    $image = $resize_image;
}

header('Content-Type: image/png');
imagepng($image, null, 9);
