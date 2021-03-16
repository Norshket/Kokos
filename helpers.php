<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d'; 
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name; 
    $result = ''; 

    if (!is_readable($name)) { //если $name не доступен для чтения то вернёт путоту
        return $result; //
    }

    ob_start(); // Включение буферизации вывода
    extract($data); // переводит значения масива в строку 
    require $name; // подключает файл

    $result = ob_get_clean(); // Получить содержимое текущего буфера а затем удаляет его
    return $result;
}


/////////////////////////////////////////////

/**
 *Считает количество задач в проекте
 * @param array $arr массив с проектами  
 * @param string $projekt_name имя проекта
 * @return int $i колличсетво заданий в проекте 
 */
 
function counting($arr, $projekt_name)
{
    $i = 0;
    if($arr){
        foreach ($arr as $value) { 
            if ($value['project_id'] === $projekt_name) {
                $i++; 
            }
        }
    }
    return $i;
}

/**
 *Считает количество задач в проекте
 * @param int $date дата проеката  
 * @return int $hours_count возвращает дату в фомате 24 часов 
 */
function date_overdue($date)
{
    if ($date !== null) {
        $diff = strtotime($date) - strtotime("now");
        $hours_count = floor($diff / 3600);
        return $hours_count; 
    
    }
}
/*
    Подключает к базе данных
    'localhost','r917996y_oot','96y_oot','r917996y_oot' 
*/
function connect_db()
{
    $conn = mysqli_connect('localhost', 'root', 'root', 'r917996y_oot'); 
    mysqli_set_charset($conn, "utf8");
    if (!$conn) {
        die("Ошибка" . mysqli_connect_error());
    }
    return $conn;
}
/**
 * запрос проектов из базы данных
 * @param $conn подключение к базе данных
 * @param $id идентификатор пользователя
 * @param $projects возвращает проекты для пользователя с уданным id
 */

function select_projects($conn, $id)
{
    $sql = "SELECT * FROM projects WHERE user_id=" . $id; //выбираем всё из таблицы projects где user_id=2
    $result = mysqli_query($conn, $sql); // отправил запрос 
    if ($result) {
        if (mysqli_num_rows($result) > 0) {  // если строк в отправленном запросе борльше 0 то 
            $projects = [];  // создаём массив 
            while ($row = mysqli_fetch_assoc($result)) {  // запускаем цикл который преобразует массив $result в строку 
                $projects[] = $row; //запиисыввем в массив 
            }
        }
    }
    if (isset($projects)) {
        return $projects;
    }
}
/**
 * запрос задач из базы данных
 * @param $conn подключение к базе данных
 * @param $id идентификатор пользователя
 * @param $tasks возвращает задачи для пользователя с уданным id
 */
function select_tasks($conn, $id)
{
    $sql = "SELECT * FROM tasks WHERE user_id= " . $id;
    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $tasks = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $tasks[] = $row;
            }
        }
    }
    if (isset($tasks)) {
        return $tasks;
    }
}
/**
 * закрытие базы данных
 * @param $conn подключение к базе данных
 */
function close_db($conn)
{
    mysqli_close($conn);
}

/**
 * фильтрует задачи по дням
 * @param array $all_task массив с задачами 
 * @param string $word проверочное слово
 * @param array $tuday фильтрованный массив 
 */
function filter_task(array $all_task, $word)
{
    $tomorrow = [];
    $expired = [];
    $tuday = [];

    $tu = strtotime(date('Y-m-d'));
    $tom = strtotime(date('Y-m-d')) + 86400;
    $ex = strtotime(date('Y-m-d')) - 86400;

    switch (true) {
        case ($word == 'tuday'):

            foreach ($all_task as $task) {
                if (strtotime($task['task_deadline']) == $tu) {
                    $tuday[] = $task;
                }
            }
            
            return $tuday;
            break;
        case ($word == 'tomorrow'):

            foreach ($all_task as $task) {
                if ($tu < strtotime($task['task_deadline'])  && strtotime($task['task_deadline']) <= $tom) {
                    $tomorrow[] = $task;
                }
            }

            return $tomorrow;
            break;

        case ($word == 'expired'):
            foreach ($all_task as $task) {
                if (strtotime($task['task_deadline']) <= $ex  &&  strtotime($task['task_deadline']) != NULL) {
                    $expired[] = $task;
                }
            }

            return  $expired;
            break;
        default:
        
            return $all_task;
    }
}

/**
 * ищет задачи и пользователее у которых у которых срок здачи заданий (сегодня)
 * @param $conn подключение к базе данных
 * @param $user_tasks_today массив с пользователями и задачами срок которых сегодня 
 */
function user_tasks_today($conn)
{

    $user_tasks_today = [];
    $sql = "SELECT users.id , users.user_name, users.email, tasks.task_name,tasks.task_deadline 
    FROM users JOIN tasks ON tasks.user_id = users.id 
    WHERE tasks.task_done ='0' AND tasks.task_deadline = CURRENT_DATE() OR  tasks.task_done ='0' AND  tasks.task_deadline = NULL";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $user_tasks_today[] = $row;
        }
    }
    return $user_tasks_today;
}

/**
 * ищет задачи по проектам 
 * @param array $all_tasks список всез задач
 * @param array $tasks массив c задачами 
 * @param string $get_tag числовое значение проекта  
 */
function filtering_by_projects($all_tasks, $get_tag)
{
    $tasks = [];
    if (isset($all_tasks)) {
        foreach ($all_tasks as $value) {
            if ($value['project_id'] == $get_tag) {
                $tasks[] = $value;
            }
        }
    }
    return $tasks;
}