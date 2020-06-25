<?php

// @todo Да се добави в базата колона thumbnail_updated_at
// @todo Когато прави thumbnails програмата да не заключва
// @todo В процедурите за взимане и обработка на изображението да се обработват грешките
// @todo Да се изчистват излишните файлове
// @todo В thumbnail_url да се поставя пълния път на картинката

define('DIR_LP_THUMBNAILS', "/var/www/o2r/images/lp_thumbnails");

// Правим картинка
function createThumbnail($pId, $pUrl) {
    exec('xvfb-run --server-args="-screen 0, 400x300x24" /usr/bin/CutyCapt --url='.$pUrl.' --out='.DIR_LP_THUMBNAILS.'/'.$pId.'.png');
}

// Обработваме картинката
function processThumbnail($pId) {
    exec('/usr/bin/convert -scale 400x300 '.DIR_LP_THUMBNAILS.'/'.$pId.'.png '.DIR_LP_THUMBNAILS.'/'.$pId.'-crop.png');
}

function getThumbnailRelativeFileName($pId) {
    $fn = DIR_LP_THUMBNAILS.'/'.$pId.'-crop.png';
    if (file_exists($fn))
        return $pId.'-crop.png';
    return $pId.'.png';
}

// Изтрива излишните файлове
function cleanup($pId) {
    
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dbhost = 'localhost:3306';
$dbuser = 'georgi';
$dbpass = 'Proba_1234';
$dbname = 'o2r';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

//if (! $conn)
//    die('Could not connect: ' . mysqli_error());
echo "Connected successfully\n";

$result = mysqli_query($conn, 'START TRANSACTION');

$sql = 'SELECT id,preview_url,thumbnail_url
        FROM jc_ofrs_offer
        WHERE preview_url IS NOT NULL
          AND length(preview_url) > 0
          AND thumbnail_url IS NULL
        FOR UPDATE';
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row["id"] . ": " . $row['preview_url'] . "\n";
        $id = $row['id'];
        createThumbnail($id, $row['preview_url']);
        processThumbnail($id);
        
        mysqli_query($conn,
            "UPDATE jc_ofrs_offer
                SET thumbnail_url = '" . getThumbnailRelativeFileName($id) . "'
              WHERE id = ".$id);
    }
} else
    echo "0 results";

mysqli_query($conn, "COMMIT");

mysqli_close($conn);

?>