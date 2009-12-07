<html>
    <head>
        <title><?php e($context->document()->title()); ?></title>
        <meta http-equiv="content-type" content="text/html; charset=<?php e($encoding); ?>" />
        <style type="text/css">
            html {
                font-size: 80%;
            }
            body {
                font-family: verdana, sans-serif;
                font-size: 1em;
            }
            table {
                margin: 1em 0;
                font-size: 1em;
            }
            caption {
                padding: 0.2em;h
            }
            th {
                text-align: left;
            }

            td.headline {
                background: #eeff33;
                font-size: bold;
            }

            caption {
                background: #ccc;
                font-weight: bold;
            }

            a:hover {
                background: #aaff66;
            }

            #container {
                padding: 0 2em;
            }
            #branding {
                display: none;
            }

            #navigation-main  {
                margin: 0;
                padding: 0.4em;
                list-style-type: none;
                background: green;
            }
            #navigation-main li {
                display: inline;
                margin: 0;
                padding: 0;
                margin-right: 1em;
            }

            #navigation-main a {
                text-decoration: none;
                padding: 0.2em;
                color: white;
            }

            #navigation-main a:hover {
                background: orange;
            }

            #navigation-sub, .navigation-sub {
                margin: 0 0 1em 0;
                padding: 0.3em;
                background: #ddd;

            }
            legend {
                font-weight: bold;
            }
            fieldset {
                padding: 0.5em;
                width: 99%;
            }
            #navigation-sub li, .navigation-sub li {
                display: inline;
                padding-right: 1em;
            }

            .navigation-frontpage {
                margin: 0;
                padding: 0;
            }

            .navigation-frontpage li {
                display: inline;
                margin:0;
                padding: 0;
            }

            .navigation-frontpage a {
                background: green;
                padding: 2em;
                margin: 2em 2em 2em 0;
                width: 10em;
                display: block;
                color: white;
                font-weight: bold;
                float: left;
                text-decoration: none;
            }

            .navigation-frontpage a:hover {
                text-decoration: underline;
            }

            #content h1 {
                font-size: 1.6em;

            }
            #content h1 span {
                font-size: 1em;
                color: #ccc;
            }
            td.fra {
                text-align: center;
                width: 1.8em;
                background: red;
                color: white;
            }
            td.syg {
                text-align: center;
                width: 1.8em;
                background: orange;
                color: white;
            }
            td.fri {
                text-align: center;
                width: 1.8em;
                background: green;
                color: white;
            }
            td.and {
                text-align: center;
                width: 1.8em;
                background: blue;
                color: white;
            }
            td.hje {
                text-align: center;
                width: 1.8em;
                background: black;
                color: white;
            }

            div#download_file {
                background-color: #99cc66;
                border-bottom: 1px solid black;
                margin: -9px -9px 10px -9px;
                padding: 6px;
            }

            #content-left {
                margin-top: 1em;
                width: 60%;
                padding-bottom: 40em;
                margin-bottom: -40em;
                float: left;
            }

            #content-right {
                margin-top: 1em;
                width: 33%;
                padding-bottom: 40em;
                margin-bottom: -40em;
                float: right;
            }

            #footer {
                clear: both;
                text-align: center;
                margin-top: 3em;
            }

            #content-right table, #content-left table {
                width: 98%;
            }


            table#betalingsoversigt td {
                text-align: right;
            }
            tr.forfalden td {
                color: red;
            }

            #content-right table {
                font-size: 0.9em;
            }

            #status {
                background: orange;
                padding: 0.3em;
                font-size: 1.5em;
                text-align: center;
            }

            table.calendar {
                border-collapse: collapse;
            }

            table.calendar td {
                border-bottom: 1px solid black;
            }

            @media print {
                #navigation-main {
                    display: none;
                }
                #navigation-sub {
                    display: none;
                }

                #status {
                    border: 1px solid black;
                }
                caption {
                    border: 1px solid #ccc;
                }

                #footer {
                    display: none;
                }

                form {
                    display: none;
                }

                .navigation-sub {
                    display: none;
                }

            #content-left {
                margin-top: 1em;
                width: 60%;
                padding-bottom: 0em;
                margin-bottom: 0em;
                float: left;
            }

            #content-right {
                margin-top: 1em;
                width: 33%;
                padding-bottom: 0em;
                margin-bottom: 0em;
                float: right;
            }
            }

        </style>
        <?php foreach ($context->document()->scripts() as $script): ?>
            <script type="text/javascript" src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>


    </head>

    <body>
        <div id="container">
            <h1 id="branding">Vejle Idrætshøjskoles Intranet</h1>

            <ul id="navigation-main">
                <?php foreach($context->document()->navigation() as $url => $name): ?>
                <li><a href="<?php e($url); ?>"><?php e($name); ?></a></li>
                <?php endforeach; ?>
            </ul>

            <div id="content">
                <h1><?php e($context->document()->title()); ?> <span><?php e($context->document()->help()); ?></span></h1>
                <ul id="navigation-sub">
                    <?php foreach($context->document()->options() as $url => $name): ?>
                    <li><a href="<?php e($url); ?>"><?php e($name); ?></a></li>
                    <?php endforeach; ?>
                </ul>

                <?php echo $content; ?>
            </div>

            <div id="footer">
                &copy; Vejle Idrætshøjskole
            </div>
        </div>
    </body>
</html>