<?php
$question = "";
$msg = "سوال خود را بپرس!";
$file_of_msg = fopen("messages.txt", "r");
$jfile = file_get_contents('people.json');
$object = json_decode(file_get_contents('people.json'));
$array_of_names = array();
$j = 1;
foreach ($object as $key => $value) {
    $array_of_names[$j] = $key;
    $j++;
}
$the_array = array();
$i = 0;
while (!feof($file_of_msg)) {
    $the_array[$i] = fgets($file_of_msg);

    $i++;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $en_name = $_POST["person"];
    $question = $_POST["question"];
    $ramz = hash('crc32b', $question . " " . $en_name);
    $tedad = 16;
    $ramz = hexdec($ramz);
    $keynum = ($ramz % $tedad);
    $msg = $the_array[$keynum];
    foreach ($object as $key => $value) {
        if ($key == $en_name) {
            $fa_name = $value;
        }
    }
} else {
    $random = array_rand($array_of_names);
    $en_name = $array_of_names[$random];
    foreach ($object as $key => $value) {
        if ($key == $en_name) {
            $fa_name = $value;
        }
    }
}
$start = "/^آیا/iu";
$last1 = "/\?$/i";
$last2 = "/؟$/u";
if(! preg_match($start , $question) ) 
{
    $msg = "سوال درستی پرسیده نشده";
}
if(!(preg_match($last1 , $question) || preg_match($last2 , $question)))
{
    $msg = "سوال درستی پرسیده نشده";   
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>

<body>
    <p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
    <div id="wrapper">
        <div id="title">
            <span id="label">
                <?php
                if ($question != "") {
                    echo "پرسش:";
                }
                ?>
            </span>
            <span id="question"><?php echo $question ?></span>
        </div>
        <div id="container">
            <div id="message">
                <p><?php
                    if ($question == "") {
                        echo "سوال خود را بپرس!";
                    } else
                        echo $msg;
                    ?></p>
            </div>
            <div id="person">
                <div id="person">
                    <img src="images/people/<?php echo "$en_name.jpg" ?>" />
                    <p id="person-name"><?php echo $fa_name ?></p>
                </div>
            </div>
        </div>
        <div id="new-q">
            <form method="post">
                سوال
                <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..." />
                را از
                <select name="person" value="<?php echo $fa_name ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <?php
                    $object = json_decode($jfile);
                    foreach ($object as $key => $value) {
                        if ($en_name == $key) {

                            echo "<option value=$key selected> $value</option> ";
                        } else {
                            echo "<option value=$key > $value</option> ";
                        }
                    }
                    ?>
                </select>
                <input type="submit" value="بپرس" />
            </form>
        </div>
    </div>
</body>

</html>
