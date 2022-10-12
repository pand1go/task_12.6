<?php
// функция разбиения
function getPartsFromFullname($persons) {
	$keys = ['surname', 'name', 'patronomyc'];
	$values = explode(' ', $persons);
	return array_combine($keys, $values);
}

print_r(getPartsFromFullname('Поттер Гарри Джеймсович'));
?>
<br>
<?php
// функция объединения
function getFullnameFromParts($surname, $name, $patronomyc) {
	return $surname.' '.$name.' '.$patronomyc;
}

echo getFullnameFromParts('Поттер', 'Гарри', 'Джеймсович');
?>
<br>
<?php
// функция сокращения ФИО 
function getShortName($persons) {
	$arr_persons = getPartsFromFullname($persons);
	$surname = mb_substr($arr_persons['surname'], 0, 1);
	return $arr_persons['name'].' '.$surname.'.';
}
echo getShortName('Поттер Гарри Джеймсович');
?>
<br>
<?php
// функция определения пола по ФИО
function getGenderFromName($persons) {
    // способ решения из условия того, что булевые выражения принимают значения единицы в случае true
	$arr_persons = getPartsFromFullname($persons);
    $sum_gender = (mb_substr($arr_persons['name'], mb_strlen($arr_persons['name'])-1, 1) === 'н' or
	mb_substr($arr_persons['name'], mb_strlen($arr_persons['name'])-1, 1) === 'й') +
	(mb_substr($arr_persons['surname'], mb_strlen($arr_persons['surname'])-1, 1) === 'в') +
	(mb_substr($arr_persons['patronomyc'], mb_strlen($arr_persons['patronomyc'])-2, 2) === 'ич') -
	(mb_substr($arr_persons['name'], mb_strlen($arr_persons['name'])-1, 1) === 'а') -
	(mb_substr($arr_persons['surname'], mb_strlen($arr_persons['surname'])-2, 2) === 'ва') -
	(mb_substr($arr_persons['patronomyc'], mb_strlen($arr_persons['patronomyc'])-3, 3) === 'вна');
	/* решение с условиями
    $sum_gender = 0;
	if (mb_substr($arr_persons['name'], mb_strlen($arr_persons['name'])-1, 1) === 'а') {
		$sum_gender -= 1;
	}
	if (mb_substr($arr_persons['surname'], mb_strlen($arr_persons['surname'])-2, 2) === 'ва') {
		$sum_gender -= 1;
	}
	if (mb_substr($arr_persons['patronomyc'], mb_strlen($arr_persons['patronomyc'])-3, 3) === 'вна') {
		$sum_gender -= 1;
	}
	if (mb_substr($arr_persons['name'], mb_strlen($arr_persons['name'])-1, 1) === 'н' or
	mb_substr($arr_persons['name'], mb_strlen($arr_persons['name'])-1, 1) === 'й') {
		$sum_gender += 1;
	}
	if (mb_substr($arr_persons['surname'], mb_strlen($arr_persons['surname'])-1, 1) === 'в') {
		$sum_gender += 1;
	}
	if (mb_substr($arr_persons['patronomyc'], mb_strlen($arr_persons['patronomyc'])-2, 2) === 'ич') {
		$sum_gender += 1;
	}*/
	
	return $sum_gender <=> 0;
}
echo getGenderFromName('Скайокер Люк Энакинович');
?>
<br>
<?php
// массив имен
$persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];
?>

<?php
// функция определения полового состава
function getGenderDescription($persons_array) {
	$arr_man = array_filter($persons_array, function($person) {
		return (getGenderFromName($person['fullname']) == 1);
	});
	$arr_woman = array_filter($persons_array, function($person) {
		return (getGenderFromName($person['fullname']) == -1);
	});
	$arr_undefined = array_filter($persons_array, function($person) {
		return (getGenderFromName($person['fullname']) == 0);
	});
	$percents_man = round(count($arr_man) / count($persons_array) * 100, 1);
	$percents_woman = round(count($arr_woman) / count($persons_array) * 100, 1);
	$percents_undefined = round(count($arr_undefined) / count($persons_array) * 100, 1);
    $output_inform = <<<HEREDOCTEXT
	Гендерный состав аудитории:
	---------------------------
	Мужчины - $percents_man %
	Женщины - $percents_woman %
	Не удалось определить - $percents_undefined %
HEREDOCTEXT;
	echo $output_inform;
}
print_r(getGenderDescription($persons_array));
?>
<br>
<?php
// функция подбора пары
function getPerfectPartner($surname, $name, $patronomyc, $persons_array) {
	$fullname = mb_convert_case(getFullnameFromParts($surname, $name, $patronomyc), MB_CASE_TITLE_SIMPLE);
	$gender_input_person = getGenderFromName($fullname);
	do {
		$person_from_arr = $persons_array[rand(0, count($persons_array)-1)]['fullname'];
	} while ($gender_input_person === getGenderFromName($person_from_arr) or getGenderFromName($person_from_arr) === 0);
	$percent = rand(5000, 10000) / 100;
	$short_name1 = getShortName($fullname);
	$short_name2 = getShortName($person_from_arr);
	$output_inform = <<<HEREDOCTEXT
	$short_name1 + $short_name2 =
	♡ Идеально на $percent % ♡
HEREDOCTEXT;
	return $output_inform;
}
print_r(getPerfectPartner('лОмАнОсов', 'мИхаИл', 'ваСиЛьевич', $persons_array));
?>