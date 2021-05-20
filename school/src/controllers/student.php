<?php
require_once __DIR__ . "/../data/repositories/student-repository.php";
require_once __DIR__ . "/../../../common/utils/field_checker.php";
require_once __DIR__ . "/../../../common/utils/random_int.php";

class StudentController
{

    private $studentRepository;

    public function __construct()
    {

        $this->studentRepository = new StudentRepository();
    }

    public function getStudent($requestData)
    {
        $errors = checkFields($requestData, array("id"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }

        $student = $this->studentRepository->findById($requestData['id']);

        if (!$student) {
            http_response_code(404);
            echo json_encode(array("status" => "fail", "message" => "STUDENT_NOT_FOUND"));
            die();
        }

        echo json_encode($student);
    }

    public function createStudent($requestData)
    {
        $errors = checkFields($requestData, array("name", "owedFees"));

        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }

        $student = new Student();
        $student->id = "050" . getRandomInts(0, 9, 10);
        $student->name = $requestData['name'];
        $student->owedFees = $requestData['owedFees'];
        $this->studentRepository->save($student);
        echo json_encode(array("status" => "success", "id" => $student->id));
    }

    public function saveStudent($requestData)
    {
        $errors = checkFields($requestData, array("name", "id", "owedFees"));
        if ($errors !== true) {
            echo json_encode($errors);
            die();
        }
        $student = $this->studentRepository->findById($requestData['id']);

        if (!$student) {
            http_response_code(404);
            echo json_encode(array("status" => "fail", "message" => "STUDENT_NOT_FOUND"));
            die();
        }

        $student->name = $requestData["name"];
        $student->owedFees = $requestData["owedFees"];
        $this->studentRepository->update($student);
    }
}
