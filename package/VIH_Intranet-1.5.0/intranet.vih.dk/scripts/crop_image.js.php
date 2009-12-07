Event.observe( window, 'load', function() {
    document.getElementById('width').readOnly = true;
    document.getElementById('width').style.backgroundColor = '#EEEEEE';
    document.getElementById('height').readOnly = true;
    document.getElementById('height').style.backgroundColor = '#EEEEEE';
    document.getElementById('x').readOnly = true;
    document.getElementById('x').style.backgroundColor = '#EEEEEE';
    document.getElementById('y').readOnly = true;
    document.getElementById('y').style.backgroundColor = '#EEEEEE';

    new Cropper.Img(
        'image',
        {
            minWidth: <?php if(isset($_GET['max_width'])): echo intval($_GET['max_width']); else: echo 0; endif; ?>,
            minHeight: <?php if(isset($_GET['max_height'])): echo intval($_GET['max_height']); else: echo 0; endif; ?>,
            <?php if(isset($_GET['unlock_ratio']) && intval($_GET['unlock_ratio']) == 0): ?>
            ratioDim: {
                x: <?php if(isset($_GET['max_width'])): echo intval($_GET['max_width']); else: echo 0; endif; ?>,
                y: <?php if(isset($_GET['max_height'])): echo intval($_GET['max_height']); else: echo 0; endif; ?>
            },
            <?php endif; ?>
            displayOnInit: true,
            onEndCrop: onEndCrop,
            <?php if(isset($_GET['x1']) && intval($_GET['x1']) AND isset($_GET['x2']) && intval($_GET['x2']) AND isset($_GET['y1']) && intval($_GET['y1']) AND isset($_GET['y2']) && intval($_GET['y2'])): ?>
            onloadCoords: {
                x1: <?php echo $_GET['x1']; ?>,
                x2: <?php echo $_GET['x2']; ?>,
                y1: <?php echo $_GET['y1']; ?>,
                y2: <?php echo $_GET['y2']; ?>
            },
            <?php endif; ?>
         }
    );
} );

function onEndCrop(coords, dimensions) {
    document.getElementById('width').value = Math.floor(dimensions.width * <?php if(isset($_GET['size_ratio'])): echo doubleval($_GET['size_ratio']); else: echo 1; endif; ?>);
    document.getElementById('height').value = Math.floor(dimensions.height * <?php if(isset($_GET['size_ratio'])): echo doubleval($_GET['size_ratio']); else: echo 1; endif; ?>);
    document.getElementById('x').value = Math.round(coords.x1 * <?php if(isset($_GET['size_ratio'])): echo doubleval($_GET['size_ratio']); else: echo 1; endif; ?>);
    document.getElementById('y').value = Math.round(coords.y1 * <?php if(isset($_GET['size_ratio'])): echo doubleval($_GET['size_ratio']); else: echo 1; endif; ?>);
}