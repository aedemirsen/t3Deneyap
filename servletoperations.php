<?
include 'wsconnect.php';
include 'objects.php';

// kullanicinin email, password ve isim bilgilerini valide eden metod
function validateUserInfo($email){

	if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email))
		return -2;// email formati hatali

	$control  = mysql_fetch_object(mysql_query("select count(id) as cnt from users where email='$email' "));
	if($control->cnt > 0){
		return -1;// Bu email adresi sistemimizde daha önceden kullanılmış...
	}

	return 1;
}

// kullanici kaydi yapan method
function userRegister(User $user){

	$email = mysql_real_escape_string($user->email);
	if(validateUserInfo($email) == 1){
		mysql_query("insert into users(name,surname, gender,email,password,phoneNumber,userType,schoolName,section,class,grade)
						values('".mysql_real_escape_string($user->name)."',
										'".mysql_real_escape_string($user->surname)."',
										'".mysql_real_escape_string($user->citizenId)."',
										'".$user->gender."',
										'".$email."',
										'".md5(mysql_real_escape_string($user->password))."',
										'".mysql_real_escape_string($user->phoneNumber)."',
										'".$user->userType."',
										'".mysql_real_escape_string($user->schoolName)."',
										'".mysql_real_escape_string($user->section)."',
										'".mysql_real_escape_string($user->class)."',
										'".mysql_real_escape_string($user->grade)."'
										)") or die("Hata:servletoperations:userRegister:Kullanici kaydi sırasında hata oluştu");
		return mysql_affected_rows() > 0 ? 1 : 0;

	}else{
		return 0;
	}
}

// kullanici guncelleme yapan method
function updateUser(User $user){
				mysql_query("update users
	 												set name = '".mysql_real_escape_string($user->name)."',
															surname = '".mysql_real_escape_string($user->surname)."',
															citizenId = '".mysql_real_escape_string($user->citizenId)."',
															phoneNumber = '".md5(mysql_real_escape_string($user->phoneNumber))."',
															schoolName = '".md5(mysql_real_escape_string($user->schoolName))."',
															section = '".md5(mysql_real_escape_string($user->section))."',
															class = '".md5(mysql_real_escape_string($user->class))."',
															grade = '".md5(mysql_real_escape_string($user->grade))."'
												where id = ".mysql_real_escape_string($user->id));
	return mysql_affected_rows() > 0 ? 1 : 0;
}

// kullanici sifresini guncelleme yapan method
function changePassword(User $user){
				mysql_query("update users
	 												set password = '".mysql_real_escape_string($user->password)."'
												where id = ".mysql_real_escape_string($user->id));
	return mysql_affected_rows() > 0 ? 1 : 0;
}

// kullanici login olur. kullaniciyi  doneriz.
function loginControl($email, $password){

	$query = mysql_query("select * from users where email = '".mysql_real_escape_string($email)."' and password='".md5($password)."' ");
	return getUserFromSQLQuery($query);
}

// ogrencileri isim soyisim veya tc kimlik numarasina gore arar.
function searchStudents(User $student){

	$query = mysql_query("select * from users
												where (
																(name like '%".mysql_real_escape_string($student->name)."%'
																		and surname like '%".mysql_real_escape_string($student->surname)."%')
																and
																(citizenId like '%".mysql_real_escape_string($student->citizenId)."%')
															)
															and userType = 0 ");
	return getUserFromSQLQuery($query);
}

function getUserFromSQLQuery($mysqlQuery){
	$users = array();
	while($userRow = mysql_fetch_object($mysqlQuery)){
		$user = new User();
		$user->id = $userRow->id;
		$user->name = $userRow->name;
		$user->surname = $userRow->surname;
		$user->citizenId = $userRow->citizenId;
		$user->gender = $userRow->gender;
		$user->email = $userRow->email;
		$user->password = $userRow->password;
		$user->phoneNumber = $userRow->phoneNumber;
		$user->userType = $userRow->userType;
		$user->schoolName = $userRow->schoolName;
		$user->section = $userRow->section;
		$user->class = $userRow->class;
		$user->grade = $userRow->grade;
		$users[] = $user;
	}
	return $users;
}

// Derslerin listesini doneriz.
function getLessonList(){

	$query = mysql_query("select * from lessons");
	return getLessonFromSQLQuery($query);
}

function getLessonFromSQLQuery($mysqlQuery){
	$lessons = array();
	while($lessonRow = mysql_fetch_object($mysqlQuery)){
		$lesson = new Lesson();
		$lesson->id = $lessonRow->id;
		$lesson->name = $lessonRow->name;
		$lessons[] = $lesson;
	}
	return $lessons;
}

// Deneyap listesini doneriz.
function getDeneyapList(){

	$query = mysql_query("select * from deneyap");
	return getDeneyapFromSQLQuery($query);
}

function getDeneyapFromSQLQuery($mysqlQuery){
	$deneyaps = array();
	while($deneyapRow = mysql_fetch_object($mysqlQuery)){
		$deneyap = new Deneyap();
		$deneyap->id = $deneyapRow->id;
		$deneyap->name = $deneyapRow->name;
		$deneyaps[] = $deneyap;
	}
	return $deneyaps;
}

function getDeneyap($id){
	$deneyapRow = mysql_fetch_object(mysql_query("select * from deneyap where id = ".mysql_real_escape_string($id)));
	$deneyap = new Deneyap();
	$deneyap->id = $deneyapRow->id;
	$deneyap->name = $deneyapRow->name;
	return $deneyap;
}

function deneyapDuzenle($id, $name){
	 mysql_query("update deneyap set name = '".mysql_real_escape_string($name)."' where id = ".mysql_real_escape_string($id));
}

function deneyapEkle($name){
  mysql_query("select * from deneyap where name = '".mysql_real_escape_string($name)."'");
	if(mysql_affected_rows() < 1){
		mysql_query("insert into deneyap (name) values ('".mysql_real_escape_string($name)."')");
		return 1;
	}else {
		return 0;
	}
}

// ilgili ogrencinin ilgili ders icin yoklama bilgilerini doner
function getStudentAttendenceList(User $student, Lesson $lesson){

	$query = mysql_query("select a.id, a.scheduleId, a.instructorId, a.studentId, a.presence, concat(u.name, ' ', u.surname) as studentNameSurname, s.date lessonDate
												from attendance a
												join users u on u.id = a.studentid
												join schedule s on s.id = a.scheduleId
												where a.studentid = ".$student->id." and s.lessonId = '".$lesson->id."'");
	return getAttendenceFromSQLQuery($query);
}

function updateAttendence(Attendence $attendence){

				mysql_query("update attendance
													set presence = '".mysql_real_escape_string($attendence->presence)."'
												where id = ".mysql_real_escape_string($attendence->id));
	return mysql_affected_rows() > 0 ? 1 : 0;
}

// Attendece listesini doneriz.
function getAttendenceList(User $instructor, $date){

	$date = mysql_real_escape_string($date);
	$query = mysql_query("select a.id, a.scheduleId, a.instructorId, a.studentId, a.presence, concat(u.name, ' ', u.surname) as studentNameSurname, s.date lessonDate
												from attendance a
												join users u on u.id = a.studentid
												join schedule s on s.id = a.scheduleId
												where a.instructorid = ".$instructor->id." and s.date = '".$date."'");
	return getAttendenceFromSQLQuery($query);
}

function getAttendenceFromSQLQuery($mysqlQuery){
	$attendences = array();
	while($attendenceRow = mysql_fetch_object($mysqlQuery)){
		$attendence = new Attendence();
		$attendence->id = $attendenceRow->id;
		$attendence->scheduleId = $attendenceRow->scheduleId;
		$attendence->instructorId = $attendenceRow->instructorId;
		$attendence->studentId = $attendenceRow->studentId;
		$attendence->presence = $attendenceRow->presence;
		$attendence->studentNameSurname = $attendenceRow->studentNameSurname;
		$attendence->lessonDate = $attendenceRow->lessonDate;
		$attendences[] = $attendence;
	}
	return $attendences;
}
?>
