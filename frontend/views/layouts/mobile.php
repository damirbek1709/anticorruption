<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<html>
<head>
    <title>
        Антикоррупционный портал Кыргызской Республики
    </title>
</head>
<body>
<div class="parent">
    <div class="child-container">
        <div id="child"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
            the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
            scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into
            electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of
            Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like
            Aldus PageMaker including versions of Lorem Ipsum
        </div>
        <div class="clear"></div>
    </div>
</div>
</body>
</html>
<style>
    .parent{width:300px; border:5px solid red; overflow:hidden; left:20px}
    .child-container{width:1730px;left:-710px;position:relative;}
    #child{ width:1000px; float:left; font-size:15px; font-family:arial; padding:10px 5px 10px 0;left:720px; border-right:4px solid red}
    .clear {clear:both;}
</style>

<script type="text/javascript">
    jQuery("#child").draggable({
        cursor: "move",
        containment: "parent",
        stop: function() {
            if(jQuery("#child").position().left < 1)
                jQuery("#child").css("left", "720px");
        }
    });
</script>