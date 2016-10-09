<?php
if(isset($_GET) != false){
    if(isset($_GET['term'])){
        include_once('functions/FuzzySearch.php');
        $dataset = json_decode(file_get_contents('formattedterms.json'));
        $fuzzy_search = new Fuzzy\FuzzySearch;

        $definition = $fuzzy_search->search($dataset, $_GET['term']);
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title>MBETS - Single Definition</title>
    <link rel="stylesheet" type="text/css" href="dist/semantic.css">
    <style type="text/css">
        body {
            background-color: #FFFFFF;
        }
        .main.container {
            padding-top: 3em;
        }
        .spaced .button {
            margin-bottom: 0.75em;
        }
    </style>

    <script src="dist/jquery.min.js"></script>
    <script src="dist/semantic.js"></script>
    <script src="formattedterms.js"></script>

</head>
<body>

<div class="ui main container center aligned">
    <div class="ui top attached segment">
        <div class="ui large centered header">
            <div class="content">MBETS - Single Definition</div>
            <div class="sub header">Economic term search tool</div>
        </div>
        <?php
        if(isset($definition)){
            if($definition[0] != -1) {
                $percent = 0;
                if ($definition[0] == 0)
                    $percent = 100;
                else
                    $percent = 100 - round(100 * ($definition[0] / strlen($definition[1])));
                ?>
                <div class="ui relaxed divided items">
                    <div class="item">
                        <div class="content">
                            <p class="header"><?= $definition[1] ?></p>

                            <div class="meta">
                                <?= $percent ?>% match
                            </div>
                            <div class="description">
                                <?= $fuzzy_search->parse_link($definition[2]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }else{
                echo '<p style="text-align: center;">No definition found</p>';
            }
        }else {
            ?>
                <p>Enter search term below</p>
                <form action="" method="GET">
                    <div class="ui form">
                        <div class="ui action left icon input">
                            <i class="search icon"></i>
                            <input name="term" type="text" placeholder="Search...">
                            <button class="ui teal button" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            <?php
        }
        ?>
    </div>
    <div class="ui bottom attached segment center aligned">
        <p>© 2016 <a href="http://99leonchang.com">Leon Chang</a></p>
    </div>
</div>

</body>
</html>

