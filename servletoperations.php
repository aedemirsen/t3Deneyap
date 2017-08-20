<?
include 'wsconnect.php';
include 'objects.php';

// kullanicinin email, password ve isim bilgilerini valide eden metod
function validateUserInfo($email){

	if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email))
		return -2;// email formati hatali

	$control  = mysqli_fetch_object(mysqli_query($connection, "select count(id) as cnt from users where email='$email' "));
	if($control->cnt > 0){
		return -1;// Bu email adresi sistemimizde daha önceden kullanılmış...
	}

	return 1;
}
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
// kullanici kaydi yapan method
function userRegister(User $user){
  global $connection;
	$email = mysqli_real_escape_string($connection, $user->email);
	if(validateUserInfo($email) == 1){
		mysqli_query($connection, "insert into users(name,surname, gender,email,password,phoneNumber,userType,schoolName,section,class,grade)
						values('".mysqli_real_escape_string($connection, $user->name)."',
										'".mysqli_real_escape_string($connection, $user->surname)."',
										'".mysqli_real_escape_string($connection, $user->citizenId)."',
										'".$user->gender."',
										'".$email."',
										'".md5(mysqli_real_escape_string($connection, $user->password))."',
										'".mysqli_real_escape_string($connection, $user->phoneNumber)."',
										'".$user->userType."',
										'".mysqli_real_escape_string($connection, $user->schoolName)."',
										'".mysqli_real_escape_string($connection, $user->section)."',
										'".mysqli_real_escape_string($connection, $user->class)."',
										'".mysqli_real_escape_string($connection, $user->grade)."'
										)") or die("Hata:servletoperations:userRegister:Kullanici kaydi sırasında hata oluştu");
		return mysqli_affected_rows($connection) > 0 ? 1 : 0;

	}else{
		return 0;
	}
}

// kullanici guncelleme yapan method
function updateUser(User $user){
				global $connection;
				mysqli_query($connection, "update users
	 												set name = '".mysqli_real_escape_string($connection, $user->name)."',
															surname = '".mysqli_real_escape_string($connection, $user->surname)."',
															citizenId = '".mysqli_real_escape_string($connection, $user->citizenId)."',
															phoneNumber = '".md5(mysqli_real_escape_string($connection, $user->phoneNumber))."',
															schoolName = '".md5(mysqli_real_escape_string($connection, $user->schoolName))."',
															section = '".md5(mysqli_real_escape_string($connection, $user->section))."',
															class = '".md5(mysqli_real_escape_string($connection, $user->class))."',
															grade = '".md5(mysqli_real_escape_string($connection, $user->grade))."'
												where id = ".mysqli_real_escape_string($connection, $user->id));
	return mysqli_affected_rows($connection) > 0 ? 1 : 0;
}

// kullanici sifresini guncelleme yapan method
function changePassword(User $user){
				global $connection;
				mysqli_query($connection, "update users
	 												set password = '".mysqli_real_escape_string($connection, $user->password)."'
												where id = ".mysqli_real_escape_string($connection, $user->id));
	return mysqli_affected_rows($connection) > 0 ? 1 : 0;
}

// kullanici login olur. kullaniciyi  doneriz.
function loginControl($email, $password){
	global $connection;
	$query = mysqli_query($connection, "select * from users where email = '".mysqli_real_escape_string($connection, $email)."' and password='".md5($password)."' ");
	return getUserFromSQLQuery($query);
}

// ogrencileri isim soyisim veya tc kimlik numarasina gore arar.
function searchStudents(User $student){
  global $connection;
	$query = mysqli_query($connection, "select * from users
												where (
																(name like '%".mysqli_real_escape_string($connection, $student->name)."%'
																		and surname like '%".mysqli_real_escape_string($connection, $student->surname)."%')
																and
																(citizenId like '%".mysqli_real_escape_string($connection, $student->citizenId)."%')
															)
															and userType = 0 ");
	return getUserFromSQLQuery($query);
}

function getUserFromSQLQuery($mysqlQuery){
	$users = array();
	while($userRow = mysqli_fetch_object($mysqlQuery)){
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
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
// Derslerin listesini doneriz.
function getLessonList(){
  global $connection;
	$query = mysqli_query($connection, "select * from lessons");
	return getLessonFromSQLQuery($query);
}

function getLessonFromSQLQuery($mysqlQuery){
	$lessons = array();
	while($lessonRow = mysqli_fetch_object($mysqlQuery)){
		$lesson = new Lesson();
		$lesson->id = $lessonRow->id;
		$lesson->name = $lessonRow->name;
		$lessons[] = $lesson;
	}
	return $lessons;
}

function getLesson($id){
	global $connection;
	$lessonRow = mysqli_fetch_object(mysqli_query($connection, "select * from lessons where id = ".mysqli_real_escape_string($connection, $id)));
	$lesson = new Lesson();
	$lesson->id = $lessonRow->id;
	$lesson->name = $lessonRow->name;
	return $lesson;
}

function lessonDuzenle($id, $name){
	 global $connection;
	 mysqli_query($connection, "update lessons set name = '".mysqli_real_escape_string($connection, $name)."' where id = ".mysqli_real_escape_string($connection, $id));
}

function lessonEkle($name){
	global $connection;
  mysqli_query($connection, "select * from lessons where name = '".mysqli_real_escape_string($connection, $name)."'");
	if(mysqli_affected_rows($connection) < 1){
		mysqli_query($connection, "insert into lessons (name) values ('".mysqli_real_escape_string($connection, $name)."')");
		return 1;
	}else {
		return 0;
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
// Ders Programlarinin listesini doneriz.
function getScheduleList($deneyapId, $lessonId, $date){
  global $connection;
	$query = mysqli_query($connection, "select s.*, l.name lessonName, d.name deneyapName
	 																		from schedule s
																			join lessons l on l.id = s.lessonId
																			join deneyap d on d.id = s.deneyapId
																			where (deneyapId = '".mysqli_real_escape_string($connection, $deneyapId)."' or '".mysqli_real_escape_string($connection, $deneyapId)."' = '')
																			and (lessonId = '".mysqli_real_escape_string($connection, $lessonId)."' or '".mysqli_real_escape_string($connection, $lessonId)."' = '')
																			and (date = '".mysqli_real_escape_string($connection, $date)."' or '".mysqli_real_escape_string($connection, $date)."' = '')
																			order by s.id desc");
	return getScheduleFromSQLQuery($query);
}

function getScheduleFromSQLQuery($mysqlQuery){
	$schedules = array();
	while($scheduleRow = mysqli_fetch_object($mysqlQuery)){
		$schedule = new Schedule();
		$schedule->id = $scheduleRow->id;
		$schedule->lessonId = $scheduleRow->lessonId;
		$schedule->deneyapId = $scheduleRow->deneyapId;
		$schedule->date = $scheduleRow->date;
		$schedule->lessonName = $scheduleRow->lessonName;
		$schedule->deneyapName = $scheduleRow->deneyapName;
		$schedules[] = $schedule;
	}
	return $schedules;
}

function getSchedule($id){
	global $connection;
	$scheduleRow = mysqli_fetch_object(mysqli_query($connection, "select s.*, l.name lessonName, d.name deneyapName
			 																		from schedule s
																					join lessons l on l.id = s.lessonId
																					join deneyap d on d.id = s.deneyapId
																					 where s.id = ".mysqli_real_escape_string($connection, $id)));
	$schedule = new Schedule();
	$schedule->id = $scheduleRow->id;
	$schedule->lessonId = $scheduleRow->lessonId;
	$schedule->deneyapId = $scheduleRow->deneyapId;
	$schedule->date = $scheduleRow->date;
	$schedule->lessonName = $scheduleRow->lessonName;
	$schedule->deneyapName = $scheduleRow->deneyapName;
	return $schedule;
}

function scheduleDuzenle($id, $deneyapId, $lessonId, $date){
	 global $connection;
	 mysqli_query($connection, "update schedule set
	 																deneyapId = '".mysqli_real_escape_string($connection, $deneyapId)."' ,
																	lessonId = '".mysqli_real_escape_string($connection, $lessonId)."' ,
																	date = '".mysqli_real_escape_string($connection, $date)."'
															where id = ".mysqli_real_escape_string($connection, $id));
}

function scheduleEkle($deneyapId, $lessonId, $date){
	global $connection;
  mysqli_query($connection, "select * from schedule
														where deneyapId = '".mysqli_real_escape_string($connection, $deneyapId)."'
														and lessonId = '".mysqli_real_escape_string($connection, $lessonId)."'
														and date = '".mysqli_real_escape_string($connection, $date)."' ");
	if(mysqli_affected_rows($connection) < 1){
		mysqli_query($connection, "insert into schedule (deneyapId, lessonId, date)
											values ('".mysqli_real_escape_string($connection, $deneyapId)."',
											'".mysqli_real_escape_string($connection, $lessonId)."',
											'".mysqli_real_escape_string($connection, $date)."')");
		return 1;
	}else {
		return 0;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
// Deneyap listesini doneriz.
function getDeneyapCount(){
	global $connection;
	$row = mysqli_fetch_array(mysqli_query($connection, "select count(*) deneyapSayisi from deneyap"));
	return $row['deneyapSayisi'];
}
function getDeneyapList(){
  global $connection;
	$query = mysqli_query($connection, "select * from deneyap");
	return getDeneyapFromSQLQuery($query);
}

function getDeneyapFromSQLQuery($mysqlQuery){
	$deneyaps = array();
	while($deneyapRow = mysqli_fetch_object($mysqlQuery)){
		$deneyap = new Deneyap();
		$deneyap->id = $deneyapRow->id;
		$deneyap->name = $deneyapRow->name;
		$deneyaps[] = $deneyap;
	}
	return $deneyaps;
}

function getDeneyap($id){
	global $connection;
	$deneyapRow = mysqli_fetch_object(mysqli_query($connection, "select * from deneyap where id = ".mysqli_real_escape_string($connection, $id)));
	$deneyap = new Deneyap();
	$deneyap->id = $deneyapRow->id;
	$deneyap->name = $deneyapRow->name;
	return $deneyap;
}

function deneyapDuzenle($id, $name){
	 global $connection;
	 mysqli_query($connection, "update deneyap set name = '".mysqli_real_escape_string($connection, $name)."' where id = ".mysqli_real_escape_string($connection, $id));
}

function deneyapEkle($name){
	global $connection;
  mysqli_query($connection, "select * from deneyap where name = '".mysqli_real_escape_string($connection, $name)."'");
	if(mysqli_affected_rows($connection) < 1){
		mysqli_query($connection, "insert into deneyap (name) values ('".mysqli_real_escape_string($connection, $name)."')");
		return 1;
	}else {
		return 0;
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
// ilgili ogrencinin ilgili ders icin yoklama bilgilerini doner
function getStudentAttendenceList(User $student, Lesson $lesson){
  global $connection;
	$query = mysqli_query($connection, "select a.id, a.scheduleId, a.instructorId, a.studentId, a.presence, concat(u.name, ' ', u.surname) as studentNameSurname, s.date lessonDate
												from attendance a
												join users u on u.id = a.studentid
												join schedule s on s.id = a.scheduleId
												where a.studentid = ".$student->id." and s.lessonId = '".$lesson->id."'");
	return getAttendenceFromSQLQuery($query);
}

function updateAttendence(Attendence $attendence){
  global $connection;
				mysqli_query($connection, "update attendance
																		set presence = '".mysqli_real_escape_string($connection, $attendence->presence)."'
																	where id = ".mysqli_real_escape_string($connection, $attendence->id));
	return mysqli_affected_rows($connection) > 0 ? 1 : 0;
}

// Attendece listesini doneriz.
function getAttendenceList(User $instructor, $date){
  global $connection;
	$date = mysqli_real_escape_string($connection, $date);
	$query = mysqli_query($connection, "select a.id, a.scheduleId, a.instructorId, a.studentId, a.presence, concat(u.name, ' ', u.surname) as studentNameSurname, s.date lessonDate
												from attendance a
												join users u on u.id = a.studentid
												join schedule s on s.id = a.scheduleId
												where a.instructorid = ".$instructor->id." and s.date = '".$date."'");
	return getAttendenceFromSQLQuery($query);
}

function getAttendenceFromSQLQuery($mysqlQuery){
	$attendences = array();
	while($attendenceRow = mysqli_fetch_object($mysqlQuery)){
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
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
?>
