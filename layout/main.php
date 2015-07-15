<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include $_SERVER['DOCUMENT_ROOT']."/layout/initialize.php"; ?>
        <title>Venetian Bazaar : store</title>
    </head>
    <body>
        <div id="world" class="world">
            <div id="contents">
                <?php echo $CONTENTS; ?>
            </div>
        </div>
        <div id="menu" class="menu">
            <div id="b1" class="bubble"></div>
                <div id="mtext_b1" class="bubble_text">Venet Glass</div>
            <div id="b2" class="bubble"></div>
                <div id="mtext_b2" class="bubble_text">Other Items</div>
            <div id="b3" class="bubble"></div>
                <div id="mtext_b3" class="bubble_text">History</div>
            <div id="b4" class="bubble"></div>
                <div id="mtext_b4" class="bubble_text">Access</div>
        
            <div id="b1m0" class="bubble_m" style="z-index:5;background-color:white;"></div>
                <div id="mtext_b1m0" class="bubble_text">Close</div>
            <div id="b1m1" class="bubble_m" onclick="goto_('contents/c1.php','contents1')"></div>
                <div id="mtext_b1m1" class="bubble_text" onclick="goto_('contents/c1.php','contents1')">Neckless</div>
            <div id="b1m2" class="bubble_m" onclick="goto_('contents/c2.php','contents2')"></div>
                <div id="mtext_b1m2" class="bubble_text" onclick="goto_('contents/c2.php','contents2')">Bottole</div>
            <div id="b1m3" class="bubble_m" onclick="goto_('contents/c3.php','contents3')"></div>
                <div id="mtext_b1m3" class="bubble_text" onclick="goto_('contents/c3.php','contents3')">Maske</div>
            <div id="b1m4" class="bubble_m" onclick="goto_('contents/beads.php','Venetian Beads')"></div>
                <div id="mtext_b1m4" class="bubble_text" onclick="goto_('contents/beads.php','Venetian Beads')">Beads</div>
        </div>
        <div class="ground">
            <div id="overlay" class="overlay"></div>
            <img id="ground" style="display:none;" />
            <div class="top_title">
                <h1>Venetian Bazaar</h1>
            </div>
            <div class="goto_title">
                <h3 id="goto" class="goto"></h3>
            </div>
        </div>
    </body>
</html>