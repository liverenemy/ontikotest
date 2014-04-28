<?php
/** @var SiteController $this */
/** @var array $results */
/** @var string $address */
$this->menu = array(
    array('label' => 'Через сервер',    'url' => array('map')),
    array('label' => 'Только JS',       'url' => array('jsMap')),
);
?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
<h1>JSON API через PHP</h1>
<div>
    <p>Получение геокоординат объекта по передаваемому адресу с помощью Google Maps API.</p>
    <form method="post">
        <input
            type="text"
            name="address"
            style="width: 400px;"
            placeholder="Введите адрес"
            value="<?php if ($address) echo $address; ?>">
        <input type="submit" value="Найти">
    </form>
    <?php
    if ($results && count($results) && count($results) > 1)
    {
        ?>
        <p ng-if="params.tooMuchResults">
        <span style="background: #ff0000; color: #fff;">
            Нашлось несколько объектов. Пожалуйста, укажите адрес более подробно. Например, введите название города.
        </span>
        </p>
        <?php
    }
    elseif (count($results) && count($results) == 1)
    {
        $result = $results[0];
    }
    ?>
    <div id="map-canvas" style="width: 90%; height: 400px;"></div>
    <script>
        mapOptions = {
            zoom: <?php echo !empty($result) ? "15" : "2"; ?>,
            center: new google.maps.LatLng(<?php echo empty($result) ? "55.753676,37.619899" : ($result['lat'] . "," . $result['lng']); ?>)
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);
        <?php
        if (!empty($result) && is_array($result) && !empty($result['lat']) && !empty($result['lng']))
        {
            ?>
            singleMarker = new google.maps.Marker({
                map: map,
                position: mapOptions.center
            });
            <?php
        }
        ?>
    </script>
</div>