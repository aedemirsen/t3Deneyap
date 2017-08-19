<?
abstract class UserType{  /* Enum */
    const Student = 0;
    const Instructor = 1;
    const Administrator = 2;
}


abstract class Gender{  /* Enum */
    const MALE = 0;
    const FEMALE = 1;
}

class User{
	public $id;
	public $name;//m
	public $surname;//m
  public $citizenId;//m
	public $gender;//m
	public $email;//m
	public $password;//m
	public $phoneNumber;
	public $userType;//m
	public $schoolName;
	public $section;
	public $class;
	public $grade;
}

class Lesson{
	public $id;
	public $name;//m
}

class Deneyap{
	public $id;
	public $name;//m
}

class Schedule{
	public $id;
	public $lessonId;//m
  public $deneyapId;//m
  public $date;
  public $lessonName;//just for Data Transfer
  public $deneyapName;//just for Data Transfer
}

class Attendence{
	public $id;
	public $scheduleId;//m
  public $instructorId;//m
  public $studentId;//m
  public $presence;//m
  public $studentNameSurname;//just for Data Transfer
  public $lessonDate;//just for Data Transfer
}
?>
