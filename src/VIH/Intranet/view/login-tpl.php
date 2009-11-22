<html>
    <head>
        <title><?php e($this->document->title); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php e($this->document->encoding); ?>" />
        <style type="text/css">
            html {
                font-size: 80%;
            }
            body {
                font-family: garamond, sans-serif;
                font-size: 1em;
                text-align: center;
            }

            #container {
                border: 8px solid #ccc;
                padding: 100px 3em 3em 3em;
                width: 40%;
                margin: auto;
                text-align: left;
                margin-top: 15%;
                background: url(<?php e(url('/images/logo.jpg')); ?>) no-repeat center 1em;
            }

            #container table {
                margin: auto;
            }

        </style>

    </head>

    <body>
        <div id="container">
            <div id="content_main">
                <?php echo $content_main; ?>
            </div>
        </div>
    </body>
</html>