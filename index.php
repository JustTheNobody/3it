<!DOCTYPE html>
<html>
<head>
    <?php
        session_start();
        //Autoload the Classes
        require 'Classes/Autoloader.php';
        // Register the autoloader
        Autoloader::register();

        //define the DB
        $host     = "localhost";
        $user     = "root";
        $password = "test";

        //define the URL with Json data
        $url = "https://www.3it.cz/test/data/json";

        $data = new Classes\Data( $host, $user, $password);
        //get sorted data if there are any
        $dataArray = $data->getData();

        //if there is a import parametr in URL and the dataArray is not empty
        if( isset($_GET['import']) && !empty($dataArray) ){
            //import has been already done
            $_SESSION['message'] = ["false", "If you want to import data again, delete the old first. <a class='button_delete' href='?delete'>Delete</a>"];

        }elseif( isset($_GET['import']) && empty($dataArray) ){
            //connect to DB & import data
            $import = new Classes\Import( $host, $user, $password);
            $_SESSION['message'] = $import->saveToDb($url);
            //save to session that we have done the import so it not repeat
            $_SESSION['import'] = true;
            header("Location: /");
            exit;
        }

        //to delete all records from table - we can add AUTO_INCREMENT=1 if we need to
        if(isset($_GET['delete'])){
            $_SESSION['message'] = $data->deleteData();
            $dataArray = [];
            if( $_SESSION['message'][0] == "success" )
                unset($_SESSION['import']);
        }


    ?>
    <!--DOC css-->
    <link rel="stylesheet" href="css/doc.css" />
    <!--Favicon why not-->
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.16.26/dist/css/uikit.min.css" />
    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.16.26/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.16.26/dist/js/uikit-icons.min.js"></script>
    <!--Jquery-->
    <script src="js/jquery-3.6.3.slim.min.js"></script>
    <!--DataTable-->
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script>

    <title>3it.cz TEST</title>
</head>

<body>
    <div class="uk-container uk-margin-large-top uk-margin-large-bottom">
        <div class="">
            <p>Dobrý den,</p>
            <p>Toto je moje zpracování zadání s URL <a href="https://www.3it.cz/test/" target="_blank">https://www.3it.cz/test/</a> </p>

            <ul class="uk-list">
                <li>
                    Použil jsem data ve formátu JSON, data se po kliku na tlačítko 'Import' naimportují do databáze, pro vytvoření tabulky byl použit Váš přiložený MySQL kód
                </li>
                <li>
                    Tyto data do tabulky posléze načítám z databáze, mohl jsem použít array, které jsem dostal po json_decode, ale to by nemuselo obsahovat stejná data, která jsme importovali do databáze.
                    <span class="uk-text-warning">To v případě ňáke chyby importu.</span>
                </li>
                <li>
                    Funkce <span class="uk-text-primary">getData</span> v <span class="uk-text-primary">class Data</span> příjmá parametr sort, jelikož jsem použil DataTable tak jsem tuto možnost řazení záznamů nevyužil, ale funkčnost byla ponechána.
                </li>
                <li>
                    Klikem na řádek, se řádek označí a označený řádek se odznačí.
                </li>
                <li>
                    <h4 class="uk-margin-large-top">Použité knihovny</h4>
                    <ul>
                        <li>Uikit</li>
                        <li>DataTables</li>
                        <li>Jquery</li>
                    </ul>
                    <p class="uk-text-muted uk-margin-top">knihovny jsem nestahoval, ale pro použití na projektu bych je umístil ve složce např. js</p>
                </li>
                <li>
                    Čas jsem překročil o 32minut.
                </li>
            </ul>
        </div>
        <hr class="uk-divider-icon">
        <!--get the json data BUTTON-->
        <div class="uk-text-center">
            <a href="?import" class="button_primary uk-margin-right">Import</a>
            <p>Json data from https://www.3it.cz/test/data/json</p>
        </div>
        <hr class="uk-divider-icon">
        <!--display data + sort-->
        <div class="uk-margin-large-top">

            <!--Show the table if there are some data-->
            <?php
                if(isset($dataArray) && is_array($dataArray) && !empty($dataArray)){
            ?>
                <table id="myTable" class="uk-table uk-table-striped uk-margin-largr-top">
                    <caption>Data from DB</caption>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Jméno</th>
                            <th>Příjmení</th>
                            <th>Datum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach( $dataArray as $key => $row ){
                        ?>
                        <tr id="row-<?php echo $key; ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['jmeno']; ?></td>
                            <td><?php echo $row['prijmeni']; ?></td>
                            <td><?php echo date("d.m.Y", strtotime($row['datum'])); ?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            <?php
                }
            ?>

        </div>

        <footer class="uk-margin-large-top uk-text-right">
            Developed by Martin Malý  @2023
        </footer>
    </div>

    <?php

        if( isset($_SESSION['message']) ){
    ?>
        <script>
            $(document).ready(function(){
                UIkit.modal('#my-id').show();
            });
        </script>
    <?php
        }
    ?>

    <!--This is the modal for some messages-->
    <div id="my-id" uk-modal>
        <div class="uk-modal-dialog uk-modal-body">
            <button class="uk-modal-close" type="button">X</button>
            <h2 class="uk-modal-title uk-text-center uk-width uk-text-<?php echo $_SESSION['message'][0]; ?>">
                <?php
                    //display & destroy
                    echo $_SESSION['message'][1];
                    unset($_SESSION['message']);
                ?>
            </h2>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            //DataTable init
            new DataTable('#myTable');

            //highlight row, don't highlight the thead
            $('tr').on('click', function(e){
                if( $(this).hasClass('highlight'))
                    $(this).removeClass('highlight');
                else if( $(this).closest('thead').length === 0 )
                    $(this).addClass('highlight');
            });
        });
    </script>
</body>

</html>