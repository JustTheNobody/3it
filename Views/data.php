<!DOCTYPE html>
<html>

<head>

    <link rel="stylesheet" href="css/doc.css" />
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.16.26/dist/css/uikit.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.16.26/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.16.26/dist/js/uikit-icons.min.js"></script>
    <script src="js/ajax.3.6.1.js"></script>
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script>

    <title>Home Page</title>
</head>

<body>
    <div class="uk-container uk-margin-large-top uk-margin-large-bottom">
        <h1>Welcome to the Import Page</h1>

        <div class="uk-align-right">
            <a href="/" class="uk-button uk-button-primary">Homepage</a>
        </div>

        <div>
            <?php

            if ( !empty($flash)){

                foreach ($flash as $key => $msg) {
            ?>
                <script>
                    $(document).ready(function() {
                        UIkit.notification({
                            message: '<?php echo $key . '<br> ' . addslashes($msg); ?>'
                        });
                    });
                </script>
            <?php
                }
            }
            ?>
        </div>

        <?php
        if (isset($result) && is_array($result) && !empty($result)) {
        ?>
            <div class="">
                <a href="/?view=data&delete" class="uk-button uk-button-danger">Delete table data</a>
            </div>
            <div class="uk-margin-large-top">
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
                        foreach ($result as $key => $row) {
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
            </div>
        <?php
        } else {
        ?>
            <div class="">
                <a href="/?view=data&import" class="uk-button uk-button-default">Import data</a>
            </div>
        <?php
        }
        ?>


    </div>

    <script>
        $(document).ready(function() {
            //DataTable init
            new DataTable('#myTable');

            //highlight row, don't highlight the thead
            $('tr').on('click', function(e) {
                if ($(this).hasClass('highlight'))
                    $(this).removeClass('highlight');
                else if ($(this).closest('thead').length === 0)
                    $(this).addClass('highlight');
            });
        });
    </script>

</body>

</html>