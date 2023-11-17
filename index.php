<?
    include $_SERVER["DOCUMENT_ROOT"] . "/php/config.php";
    include $_SERVER["DOCUMENT_ROOT"] . "/php/functions.php";
    require 'vendor/autoload.php';
    require 'smarty/smarty.init.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>TestDB</title>
    <meta name="viewport" content="width=device-width">
</head>
<body class="testdb">
    <h1>HELLO WORLD</h1>
    <?
        $query = "SELECT ".
            "v.NL_VIEW_SHORT, ".
            "h.NL_HOUSES_SHORT, ".
            "m.NL_MATERIAL_SHORT, ".
            "NL_PROP_RESALE_FLOOR, ".
            "NL_PROP_RESALE_AREA_FULL, ".
            "NL_PROP_RESALE_PHOTO_URLS, ".
            "NL_PROP_RESALE_COST_TOTAL, ".
            "NL_PROP_RESALE_ADDRESS, ".
            "NL_PROP_RESALE_DESCRIPTION, ".
            "u.NL_USER_SHORT, ".
            "NL_PROP_RESALE_PHONE, ".
            "NL_PROP_RESALE_PHONE_OWNER, ".
            "NL_PROP_RESALE_DATE_INSERT, ".
            "NL_PROP_RESALE_DATE_UPDATE ".
        "FROM ".
            "testdb.NL_PROP_RESALE ".
        "LEFT JOIN testdb.NL_VIEW v USING(ID_NL_VIEW) ".
        "LEFT JOIN testdb.NL_HOUSES h USING(ID_NL_HOUSES) ".
        "LEFT JOIN testdb.NL_MATERIAL m USING(ID_NL_MATERIAL) ".
        "LEFT JOIN testdb.NL_USER u USING(ID_NL_USER);";
    $tableCols = Array("NL_VIEW_SHORT", "NL_HOUSES_SHORT",
        "NL_MATERIAL_SHORT", "NL_PROP_RESALE_FLOOR",
        "NL_PROP_RESALE_AREA_FULL", "NL_PROP_RESALE_PHOTO_URLS",
        "NL_PROP_RESALE_COST_TOTAL", "NL_PROP_RESALE_ADDRESS",
        "NL_PROP_RESALE_DESCRIPTION", "NL_USER_SHORT",
        "NL_PROP_RESALE_PHONE", "NL_PROP_RESALE_PHONE_OWNER",
        "NL_PROP_RESALE_DATE_INSERT", "NL_PROP_RESALE_DATE_UPDATE");
    $tableHead = Array( "NL_VIEW_SHORT" => "Вид из окна",
        "NL_HOUSES_SHORT" => "Тип дома",
        "NL_MATERIAL_SHORT" => "Материал",
        "NL_PROP_RESALE_FLOOR" => "Этаж",
        "NL_PROP_RESALE_AREA_FULL" => "Общая площадь",
        "NL_PROP_RESALE_PHOTO_URLS" => "Фото",
        "NL_PROP_RESALE_COST_TOTAL" => "Стоимость",
        "NL_PROP_RESALE_ADDRESS" => "Адрес",
        "NL_PROP_RESALE_DESCRIPTION" => "Описание",
        "NL_USER_SHORT" => "Ответственный",
        "NL_PROP_RESALE_PHONE" => "Телефон",
        "NL_PROP_RESALE_PHONE_OWNER" =>"Телефон собственника",
        "NL_PROP_RESALE_DATE_INSERT" => "Добавлено",
        "NL_PROP_RESALE_DATE_UPDATE" => "Обновлено");

        db_connect();

        $res = db_query($query) or die(db_error($query));
        $tableData = Array();
        while ($row = db_fetch_assoc($res)) {
            for ($i = 0; $i < count($tableCols); $i++){
                if ($tableCols[$i] == "NL_PROP_RESALE_PHOTO_URLS"){
                    if($row[$tableCols[$i]] != ""){
                        $row[$tableCols[$i]] = str_replace("[", "<img src=", $row[$tableCols[$i]]);
                        $row[$tableCols[$i]] = str_replace("]", "width=\"80\" height=\"60\" />", $row[$tableCols[$i]]);
                    }
                }
                if ($tableCols[$i] == "NL_PROP_RESALE_DESCRIPTION"){
                    $lexer = new \nadar\quill\Lexer(urldecode($row[$tableCols[$i]]));
                    $row[$tableCols[$i]] = $lexer->render();
                }
                $tableData[] = $row[$tableCols[$i]];
            }
        }

        $tableResHeads = Array();
        for ($i = 0; $i < count($tableCols); $i++){
            $tableResHeads[] = $tableHead[$tableCols[$i]];
        }

        db_disconnect();

        $smarty = new SmartyTestdb();
//        $smarty->testInstall();
        $smarty->assign('cols', $tableResHeads);
        $smarty->assign('data', $tableData);
        $smarty->display('table.tpl');
    ?>
</body>
</html>
