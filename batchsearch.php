<?php
if(isset($_POST) != false){
    if(isset($_POST['batch_terms'])){
        include_once('functions/FuzzySearch.php');
        $definitions = array();
        $terms = preg_split('/\n|\r/', $_POST['batch_terms'], -1, PREG_SPLIT_NO_EMPTY);
        $dataset = json_decode(file_get_contents('formattedterms.json'));
        $fuzzy_search = new Fuzzy\FuzzySearch;

        foreach($terms as $term){
            array_push($definitions, $fuzzy_search->search($dataset, $term));
        }
        //print_r($definitions);
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
    <title>MBETS - Batch Search</title>
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

<div class="ui main container">
    <div class="ui top attached segment">
        <div class="ui large centered header">
            <div class="content">MBETS - Batch Search</div>
            <div class="sub header">Economic term search tool</div>
        </div>
        <?php
        if(isset($definitions)){
            ?>
            <div class="ui relaxed divided items">
                <?php
                foreach($definitions as $single_def) {
                    if($single_def[0] != -1) {
                        $percent = 0;
                        if ($single_def[0] == 0)
                            $percent = 100;
                        else
                            $percent = 100 - round(100 * ($single_def[0] / strlen($single_def[1])));
                        ?>
                        <div class="item">
                            <div class="content">
                                <p class="header"><?= $single_def[1] ?></p>

                                <div class="meta">
                                    <?= $percent ?>% match
                                </div>
                                <div class="description">
                                    <?= $fuzzy_search->parse_link($single_def[2]) ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }else{
                        ?>
                <div class="item">
                    <div class="content">
                        <p class="header">No definition found</p>
                    </div>
                </div>
                <?php
                    }
                }
                ?>

            </div>
            <?php
        }else {
            ?>
            <div class="ui icon message">
                <i class="exclamation icon"></i>
                <div class="content">
                    <div class="header">
                        Heads up! This service is in beta
                    </div>
                    <p>Batch search utilizes a newly written Fuzzy search algorithm. Results may therefore be unreliable.</p>
                </div>
            </div>
            <form action="" method="POST">
                <div class="ui form">
                    <div class="field">
                        <label>Search Terms, one term per line.</label>
                        <textarea name="batch_terms"></textarea>
                    </div>
                    <button class="ui button" type="submit">Search</button>
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

