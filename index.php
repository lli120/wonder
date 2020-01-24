<?php
define('REGIONS', [
    'Auckland',
    'Christchurch',
    'Hamilton',
    'Invercargill',
    'New Plymouth',
    'Rotorua',
    'Wellington'
]
);
?>
<!doctype html>
<html lang="us">
<head>
    <meta charset="utf-8">
    <link href="jquery-ui/jquery-ui.css" rel="stylesheet">
    <script
      src="https://code.jquery.com/jquery-1.9.1.min.js"
      integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ="
      crossorigin="anonymous"></script>
    <script src="jquery-ui/external/jquery/jquery.js"></script>
    <script src="jquery-ui/jquery-ui.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){

        $.ajax({
            url: 'data.php',
            success: function(){
                $('.ui-widget-overlay').hide();
            }
        });

        $('#search').on('click', function(event){
            var specialist = $('#specialist').val();
            var region = [];
            $.each($("input[name='region']:checked"), function(){
                region.push($(this).val());
            });

            $.ajax({
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({ "specialist": specialist, "region" : region }),
                url: 'search.php',
                type:'post',
                success: function(data){ 
                    var tableContent = '<div id="accordion">';
                    for (var hos in data) {
                        tableContent += '<h3>' + hos + '</h3><div>';
                        for (var key in data[hos]) {
                            tableContent += '<h4>Services : ' + key + '</h4>';
                            tableContent += '<table><tbody><tr><th>Specialist name</th><th>Specialties</th></tr>';
                            $.each(data[hos][key], function(index, item){
                                var isArray = $.isArray(item);
                                if (isArray) {
                                    $.each(item, function(k, v){
                                        tableContent += '<tr>';
                                        tableContent += '<td>' + v['name'] + '</td>';
                                        tableContent += '<td>' + v['title'] + '</td>';
                                        tableContent += '</tr>';
                                    });
                                }
                                else {
                                    tableContent += '<tr>';
                                    tableContent += '<td>' + item['name'] + '</td>';
                                    tableContent += '<td>' + item['title'] + '</td>';
                                    tableContent += '</tr>';
                                }
                            });
                            tableContent += '</table></tbody>';
                        }
                        tableContent += '</div>';

                    }

                    tableContent += '</div>';
                    $('#result').html(tableContent);
                    $("#accordion" ).accordion();
                }
            });
        });
    });
    </script>
    <style>
    body{
        font-family: "Trebuchet MS", sans-serif;
        margin: 50px;
    }
    table {
        width: 100%;
        border: 1px solid #ccc;
    }
    td {
        border: 1px solid #ccc;
    }

    th {
        text-align: left;
    }
    input[type=text] {
        height: 30px;
        width: 200px;
    }

    button {
        font-family: "Trebuchet MS", sans-serif;
        padding: 10px 0;
        width: 200px;
        font-size: 14px;
        margin: 15px 0;
    }

    .red{
        font-size: small;
        color: #ff0000;
    }
    </style>
</head>
<title>Hospital specialist Seach</title>
    <body>
    <h1>Specialist / Service Search Form</h1>
    <div>
        <label>Specialist name/Service:</label>
        <input type="text" name="specialist" id="specialist"/>
        <span class="red">*Please Note: Specialist / Service is case sensetive </span>
        <br />
        <label>Region:</label>
        <?php
            foreach (REGIONS as $key => $value) {
                echo '<br /><input type="checkbox" name="region" value="' . $value . '"/>' . $value ;
            }
        ?>
        <br />
        <button id="search"/>Search</button>
    </div>

    <div id="result"></div>
    <div class="ui-widget-overlay" style="text-align:center;z-index: 1001;padding:25% 0;"><h3>Data Intializing In Progress</h3></div>
    </div>
    </body>
</html>
