<?
	include('./db_conn.php'); //Подключение к БД
	include('./class/telegrambot.php'); //Подключение бота

	$key="6306012062:AAE_aHdk9Xjt1G_GHjoh5flOTCgkQnl0BXU";
	
    
    
	$bot = new TelegramBot($key);
    $bot->registerHook(); //After registrate hook you can comment this line
    $data = $bot->getData();
    
	if($bot->readCmd()=="/start"){
        //$bot->sendMessage($bot->sendButton(1, "test"));

        $bot->sendButton([["💡 Инструкция"],["🍦 Прайс","🍿 Магазин","♾️ Вопросы"],["🎮 Чат","💎 Статьи","🏆 Отзывы"]],"<b>Здравствуйте, я бот от компании BAHUR. 

Я могу искать для вас в описании ароматов: ноты, бренд, пол, страну. Просто напишите мне, что вам нужно найти. Примеры использования в инструкции.</b>");
        //$bot->sendMessage("");
        exit;
    }
    elseif($bot->readCmd()=="💡 Инструкция"){
        $bot->sendMessage("Напиши мне любую ноту, пол, бренд, страну или всё вместе и я пришлю, что найду.

Пример 1: Амбра, Мускус - [Нота, Нота]

Пример 2: Женский, Роза - [Пол, Нота]

Пример 3: ОАЭ, Манго, Мужской - [Cтрана, Нота, Пол]

Пример 4: Сreed, Мужской, Мандарин - [Бренд, Пол, Нота]");
        exit;
    }
    elseif($bot->readCmd()=="🍦 Прайс"){
        $btns = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '🚀 Перейти к прайсу', 'url' => "https://drive.google.com/file/d/1J70LlZwh6g7JOryDG2br-weQrYfv6zTc/view?usp=sharing"]
                ]
            ]
        ]);
        $bot->sendMessage("Вот! ☺️",null, $btns);
    }
    elseif($bot->readCmd()=="🎮 Чат"){
        $btns = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '🚀 Перейти в чат', 'url' => "https://t.me/+VYDZEvbp1pce4KeT"]
                ]
            ]
        ]);
        $bot->sendMessage("Вот! ☺️",null, $btns);
    }
    elseif($bot->readCmd()=="♾️ Вопросы"){
        $btns = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '🚀 Перейти к вопросам', 'url' => "https://vk.com/@bahur_store-optovye-praisy-ot-bahur"]
                ]
            ]
        ]);
        $bot->sendMessage("Вот! ☺️",null, $btns);
    }
    elseif($bot->readCmd()=="🏆 Отзывы"){
        $btns = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '🚀 Перейти к отзывам', 'url' => "https://vk.com/bahur_store?w=app6326142_-133936126%2523w%253Dapp6326142_-133936126"]
                ]
            ]
        ]);
        $bot->sendMessage("Вот! ☺️",null, $btns);
    }
    elseif($bot->readCmd()=="🍿 Магазин"){
        $btns = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '🚀 Перейти в магазин', 'url' => "www.bahur.store/m/"]
                ]
            ]
        ]);
        $bot->sendMessage("Вот! ☺️",null, $btns);
    }
    elseif($bot->readCmd()=="💎 Статьи"){
        $btns = json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '🚀 Перейти', 'url' => "https://vk.com/@bahur_store"]
                ]
            ]
        ]);
        $bot->sendMessage("Статьи можно найти здесь",null, $btns);
    }
    elseif(isset($data['callback_query']['message']['reply_markup']['inline_keyboard'][0][1]['callback_data'])){
        $callbackData= $data['callback_query']['message']['reply_markup']['inline_keyboard'][0][1]['callback_data'];
        $callbackData = explode("_",$callbackData)[1];

        $findVals = $mysqli->query("SELECT * FROM `finds` WHERE `ID` = $callbackData")->fetch_array(MYSQLI_ASSOC);
        if($db_val = $mysqli->query($findVals['search_string'])->fetch_array(MYSQLI_ASSOC)){

            if (preg_match_all($findVals['patterns'], ($db_val['description']), $matches)) {
                // Если были найдены вхождения, то заменяем их на выделенный текст с использованием обратных кавычек
                foreach ($matches[0] as $match) {
                    $db_val['description'] = str_ireplace($match, "<u><b>{$match}</b></u>", ($db_val['description']));
                }

                $btns = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => '🚀 Подробнее', 'url' => $db_val['URL']],
                            ['text' => '♾️ Повторить', 'callback_data' => "ID_".$callbackData]
                        ]
                    ]
                ]);
                $bot->sendMessage("✨ {$db_val['brand']} {$db_val['aroma']}\n\n".$db_val['description'],$data['callback_query']['from']['id'], $btns);
            } else {
                $bot->sendMessage("✨ {$db_val['brand']} {$db_val['aroma']}\n\n".$db_val['description'],$data['callback_query']['from']['id']);
            }
        }
        else {
            $bot->sendMessage("К сожалению, я не смог ничего найти! Попробуйте поискать что-то другое! 🙈",$data['callback_query']['from']['id']);
        }
    }
    /*elseif(strncmp($bot->readCmd(),"/ноты",5)==0){
        $bot->readCmd("/ноты %[^\n]",true, $args);//todo
        if(!isset($args[0])) $bot->sendMessage("Используйте '/ноты ваши_ноты'");
        else{
            //$db->query("UPDATE `users` SET `last_cmd` = 'file', `file1` = '{$args[0]}' WHERE `user_id` = $user_id");

            $search_string = implode("%' AND `description` LIKE '%", explode(',',str_replace(" ","",$args[0])));
            $query = "SELECT * FROM `aromas` WHERE `description` LIKE '%$search_string%' ORDER BY RAND()";
            $db_val = $mysqli->query($query)->fetch_array(MYSQLI_ASSOC);
            $bot->sendMessage("{$db_val['description']}");
        }

        exit;
    }*/
    else {
        $search_vals = explode(",",str_ireplace(" ","",mb_strtolower($bot->getData()['message']['text']))); //Массив с искомыми значениями
        $search_pattern = '/' . implode('|', $search_vals) . '/i';

        $search_string = implode("%' AND `description` LIKE '%", $search_vals); //Формирование и отправка запроса
        $query = "SELECT * FROM `aromas` WHERE `description` LIKE '%$search_string%' ORDER BY RAND()";
        if($db_val = $mysqli->query($query)->fetch_array(MYSQLI_ASSOC)){

            if (preg_match_all($search_pattern, ($db_val['description']), $matches)) {
                // Если были найдены вхождения, то заменяем их на выделенный текст с использованием обратных кавычек
                foreach ($matches[0] as $match) {
                    $db_val['description'] = str_ireplace($match, "<u><b>{$match}</b></u>", ($db_val['description']));
                }

                $mysqli->query("INSERT INTO `finds`(`search_string`,`patterns`) VALUES(\"$query\",\"$search_pattern\")");
                $btns = json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => '🚀 Подробнее', 'url' => $db_val['URL']],
                            ['text' => '♾️ Повторить', 'callback_data' => "ID_".$mysqli->insert_id]
                        ]
                    ]
                ]);
                $bot->sendMessage("✨ {$db_val['brand']} {$db_val['aroma']}\n\n".$db_val['description'],null, $btns);
            } else {
                $bot->sendMessage("✨ {$db_val['brand']} {$db_val['aroma']}\n\n".$db_val['description']);
            }
        } else {
            $bot->sendMessage("К сожалению, я не смог ничего найти! 🙈");
        }
        exit;
    }
?>