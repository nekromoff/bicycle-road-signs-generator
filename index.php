<!doctype html>
<html lang="sk">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dopravné značky IS 40 (smerová tabuľa pre cyklistov)</title>
  <meta name="description" content="Generátor dopravných značiek IS 40a, IS 40b, IS 40c, IS 40d, IS 40f, IS 40g, IS 40h pre tlač (smerová tabuľa pre cyklistov).">
  <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Generátor dopravných značiek pre tlač</h1>
                <div class="text-center">
                    <img class="img-fluid" src="sample.png" alt="Smerová tabuľa pre cyklistov" title="Smerová tabuľa pre cyklistov">
                </div>
                <h2>Smerová tabuľa pre cyklistov: IS 40a, IS 40b, IS 40c, IS 40d, IS 40f, IS 40g, IS 40h</h2>
<?php
error_reporting(0);
require_once __DIR__ . '/vendor/autoload.php';
setlocale(LC_ALL, 'sk_SK.UTF-8');

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

if (isset($_POST['generate'])) {
    $linenumber = substr_count($_POST['target'], "\n") + 1;
    if ($linenumber > 2) {
        echo '<div class="alert alert-warning" role="alert">Neviem vygenerovať značku s viac než dvoma cieľmi.</div>';
    } else {
        $targets = explode("\n", $_POST['target']);
        $distances = explode("\n", $_POST['distance']);
        if ($_POST['sign'] == 'route') {
            $size = array(300, 200);
        } else {
            $size = array(800, 178);
        }
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8',
            'format'                       => $size,
            'fontDir'                      => array_merge($fontDirs, [
                __DIR__ . '/fonts',
            ]),
            'fontdata'                     => $fontData + [
                'tern'    => [
                    'R' => 'Tern-Regular.ttf',
                ],
                'awesome' => [
                    'R' => 'fontawesome-webfont.ttf',
                ],
            ],
            'default_font'                 => 'tern',
        ]);
        $mpdf->SetAutoPageBreak(false);

        $mpdf->SetImportUse();

        $source = $_POST['sign'] . '-' . $_POST['type'] . '.pdf';
        $mpdf->SetSourceFile('sources/' . $source);
        $tplId = $mpdf->ImportPage(1);
        $mpdf->UseTemplate($tplId);

        $mpdf->SetDrawColor(28, 107, 61);
        $mpdf->SetTextColor(28, 107, 61);
        $mpdf->SetFillColor(28, 107, 61);
        $mpdf->SetFontSize($_POST['fontsize']);

        if ($_POST['sign'] == 'route') {
            $mpdf->SetXY(0, 10);
            $mpdf->WriteCell($size[0], $size[1] / 2, $_POST['number'], 0, 0, 'C');
            $normalized = trim(strtolower(preg_replace('/\s+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT', trim($_POST['number'])))));
        } else {
            if ($_POST['type'] == 'straight' or $_POST['type'] == 'right') {
                if ($linenumber == 1) {
                    $mpdf->SetXY(0, 10);
                    $mpdf->WriteCell(175, $size[1] / 2, $_POST['number'], 0, 0, 'C');
                    $mpdf->SetXY(200, 50);
                    $mpdf->WriteCell(600, $size[1] / 2, $targets[0], 0, 0, 'L');
                    $mpdf->SetXY(630, 50);
                    $mpdf->WriteCell(57, $size[1] / 2, $distances[0], 0, 0, 'R');
                } else {
                    $mpdf->SetXY(0, 10);
                    $mpdf->WriteCell(175, $size[1] / 2, $_POST['number'], 0, 0, 'C');
                    $mpdf->SetXY(200, 10);
                    $mpdf->WriteCell(600, $size[1] / 2, $targets[0], 0, 0, 'L');
                    $mpdf->SetXY(630, 10);
                    $mpdf->WriteCell(57, $size[1] / 2, $distances[0], 0, 0, 'R');
                    $mpdf->SetXY(200, 80);
                    $mpdf->WriteCell(600, $size[1] / 2, $targets[1], 0, 0, 'L');
                    $mpdf->SetXY(630, 80);
                    $mpdf->WriteCell(57, $size[1] / 2, $distances[1], 0, 0, 'R');
                }
            } elseif ($_POST['type'] == 'left') {
                if ($linenumber == 1) {
                    $mpdf->SetXY(113, 50);
                    $mpdf->WriteCell(70, $size[1] / 2, $distances[0], 0, 0, 'R');
                    $mpdf->SetXY(190, 50);
                    $mpdf->WriteCell(415, $size[1] / 2, $targets[0], 0, 0, 'R');
                    $mpdf->SetXY(625, 10);
                    $mpdf->WriteCell(175, $size[1] / 2, $_POST['number'], 0, 0, 'C');
                } else {
                    $mpdf->SetXY(113, 10);
                    $mpdf->WriteCell(70, $size[1] / 2, $distances[0], 0, 0, 'R');
                    $mpdf->SetXY(190, 10);
                    $mpdf->WriteCell(415, $size[1] / 2, $targets[0], 0, 0, 'R');
                    $mpdf->SetXY(113, 80);
                    $mpdf->WriteCell(70, $size[1] / 2, $distances[1], 0, 0, 'R');
                    $mpdf->SetXY(190, 80);
                    $mpdf->WriteCell(415, $size[1] / 2, $targets[1], 0, 0, 'R');
                    $mpdf->SetXY(625, 10);
                    $mpdf->WriteCell(175, $size[1] / 2, $_POST['number'], 0, 0, 'C');
                }
            }
            $normalized = trim(strtolower(preg_replace('/\s+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT', trim($_POST['target'])))));
        }

        $filename = $_POST['sign'] . '-' . $_POST['type'] . '-' . $normalized . '.pdf';

        $mpdf->Output('pdf/' . $filename, 'F');
        echo '<div class="alert alert-success" role="alert">Vygenerovaná značka <a href="pdf/' . $filename . '">' . $filename . '</a>.</div>';
        flush();
        ob_flush();
    }
}

function isSelected($field, $value)
{
    if (isset($_POST[$field]) and $_POST[$field] == $value) {
        echo ' selected';
    }
}

function isValue($field, $type = 'text', $defaultvalue = '')
{
    if (!isset($_POST[$field]) and $type == 'text' and $defaultvalue) {
        echo ' value="' . $defaultvalue . '"';
    } else {
        echo $defaultvalue;
    }
    if (isset($_POST[$field]) and $type == 'text') {
        echo ' value="' . $_POST[$field] . '"';
    } else {
        echo $_POST[$field];
    }
}

?>
                <form action="./" method="POST">
                    <div class="form-group">

        <?php /*
Pre symboly použite tieto znaky:</p>
<ul>
<li>Vlak: [V]</li>
<li>Bus: [B]</li>
<li>Letisko: [L]</li>
<li>Centrum: [C]</li>
</ul>
 */
;?>
                        <input type="hidden" name="generate" value="1">
                        <label for="sign">Typ:</label>
                        <select class="form-control" id="sign" name="sign">
                            <option value="route"<?php isSelected('sign', 'route');?>>Označenie trasy</option>
                            <option value="direction"<?php isSelected('sign', 'direction');?>>Smer trasy</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Variant:</label>
                        <select class="form-control" id="type" name="type">
                            <option value="straight"<?php isSelected('type', 'straight');?>>Rovno</option>
                            <option value="left"<?php isSelected('type', 'left');?>>Vľavo</option>
                            <option value="right"<?php isSelected('type', 'right');?>>Vpravo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="target">Číslo trasy:</label>
                        <input class="form-control" type="text" name="number" id="number"<?php isValue('number');?>>
                    </div>
                    <div class="form-group">
                        <label for="target">Cieľ:</label>
                        <textarea class="form-control" type="textarea" name="target" id="target"><?php isValue('target', 'textarea');?></textarea>
                        <small class="form-text text-muted">Uveďte maximálne dva ciele (každý na novom riadku).</small>
                    </div>
                    <div class="form-group">
                        <label for="distance">Vzdialenosť:</label>
                        <textarea class="form-control" name="distance" id="distance"><?php isValue('distance', 'textarea');?></textarea>
                        <small class="form-text text-muted">Uveďte maximálne dve vzdialenosti (každú na novom riadku).</small>
                    </div>
                    <div class="form-group">
                        <hr>
                        <label for="target">Veľkosť fontu v px:</label>
                        <input class="form-control" type="text" name="fontsize" id="fontsize"<?php isValue('fontsize', 'text', 200);?>>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg" type="submit">Vygenerovať</button>
                </form>
                <br>
                <p class="lead">Službu prevádzkuje Cyklokoalícia (c) 2018+</p>
            </div>
        </div>
    </div>
</body>
</html>
