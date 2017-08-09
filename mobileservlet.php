<?
include 'servletoperations.php';

header('Content-Type: application/json');

$method = $_GET['servletMethod'] ? $_GET['servletMethod'] : $_POST['servletMethod'];
$servletUser = $_SERVER['PHP_AUTH_USER'];
$servletPassword = $_SERVER['PHP_AUTH_PW'];

function echoAsJson($input){
	echo $_GET['callback'].json_encode($input);
}

function JsonDecodeToObject($json, $class){
	$data = json_decode($json, true);
	foreach ($data as $key => $value) $class->{$key} = $value;
	return $class;
}

/*****************************************************************/

if($method == 'userRegister'){
// kullanici kaydi yapan method
	$returnVal = userRegister(JsonDecodeToObject($_GET['user'], new User()));//gelen nesne json serialized
	echoAsJson($returnVal);

}else if ($method == 'updateUser'){
// kullanicinin bilgilerini gunceller.
	$returnVal = updateUser(JsonDecodeToObject($_GET['user'], new User()));//gelen nesne json serialized
	echoAsJson($returnVal);

}else if ($method == 'changePassword'){
// kullanicinin sifresini degistirir.
	$returnVal = changePassword(JsonDecodeToObject($_GET['user'], new User()));//gelen nesne json serialized
	echoAsJson($returnVal);

}else if ($method == 'loginControl'){
// kullanici login olur. kullaniciyi  doneriz.
	$returnVal = loginControl($_GET['email'], $_GET['password']);
	echoAsJson($returnVal);

}else if ($method == 'searchStudents'){
// ogrencileri isim soyisim veya tc kimlik numarasina gore arar.
	$returnVal = searchStudents(JsonDecodeToObject($_GET['student'], new User()));//gelen nesne json serialized
	echoAsJson($returnVal);

}else if ($method == 'getLessonList'){
// derslerin listesini doner.
	$returnVal = getLessonList();
	echoAsJson($returnVal);

}else if ($method == 'getDeneyapList'){
// Deneyap listesini doner.
	$returnVal = getDeneyapList();
	echoAsJson($returnVal);

}else if ($method == 'getStudentAttendenceList'){
// ilgili ogrencinin ilgili ders icin yoklama bilgilerini doner
	$student = JsonDecodeToObject($_GET['student'], new User());
	$lesson = JsonDecodeToObject($_GET['lesson'], new Lesson());
	$returnVal = getStudentAttendenceList($student, $lesson);
	echoAsJson($returnVal);

}else if ($method == 'updateAttendence'){
// ilgili ogrencinin ilgili ders icin yoklama bilgilerini doner
	$attendence = JsonDecodeToObject($_GET['attendence'], new Attendence());
	$returnVal = updateAttendence($attendence);
	echoAsJson($returnVal);

}else if ($method == 'getAttendenceList'){
// ilgili egitmenin belirtilen gune ait yoklamasini doner
	$instructor = JsonDecodeToObject($_GET['instructor'], new User());
	$returnVal = getAttendenceList($instructor, $_GET['date']);
	echoAsJson($returnVal);

}else {
	echoAsJson('wrong method name'.$servletUser);
}

/*****************************************************************/

?>
