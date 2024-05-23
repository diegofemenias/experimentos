<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>
    <div class="container" style="margin-top: 20px;">
        <div class="row">
            <div class="col-lg-12">

                <?php

                $dir    = 'captures';
                $files = scandir($dir);

                foreach ($files as $file) {
                    if (!is_dir($file)) {
                        $size = filesize('captures/' . $file);
                        if ($size < 10000) {
                            unlink('captures/' . $file);
                        }
                    }
                }

                foreach ($files as $file) {
                    if (!is_dir($file)) {
                        echo "<a href='" . "captures/" . $file . "' target='_blank'>";
                        echo "<img class='img-thumbnail' width='200px' height='200px' src='captures/" . $file . "'>";
                        echo "</a>";
                    }
                }

                //$r = exec("./ffmpeg -pattern_type glob -i 'captures/*.png' out.mp4 -y");

                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                &nbsp;
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <button type="button" id="seelist" class="btn btn-success btn-lg" onclick="window.location = 'index.html';" style="width: 200px; height: 100px;">VER CAM</button>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 20px;">
        <div class="row">
            &nbsp;
        </div>
    </div>
</body>

</html>