<?php
require_once "./auth.php";
require_once "./conn.php";
require_once "./models/User.php";

class ProfileController {

    private $userModel;
    private $uploadDir = "./uploadsPP/";

    public function __construct($mysqli) {
        $this->userModel = new User($mysqli);

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function index() {
        $email = $_SESSION['user'];
        $user = $this->userModel->getByEmail($email);

        require "./views/profile.php";
    }

    public function update() {
        $email = $_SESSION['user'];
        $user = $this->userModel->getByEmail($email);

        $name = addslashes(trim($_POST['name']));
        $about = addslashes(trim($_POST['aboutme']));
        $address = addslashes(trim($_POST['address']));

        $image = $user['imagepath'];

        if (isset($_POST['deletephoto'])) {
            $image = $this->uploadDir . "defaultprofile.jpg";

        } elseif (isset($_FILES['profilepicture']) && $_FILES['profilepicture']['error'] === UPLOAD_ERR_OK) {

            $tmp = $_FILES['profilepicture']['tmp_name'];
            $fileName = time() . "_" . $_FILES['profilepicture']['name'];
            $path = $this->uploadDir . $fileName;

            move_uploaded_file($tmp, $path);
            $image = $path;
        }

        $this->userModel->update($name, $about, $address, $image, $email);

        header("Location: profil.php");
        exit();
    }

    public function deleteAccount() {
        $email = $_SESSION['user'];
        $user = $this->userModel->getByEmail($email);

        if ($user['imagepath'] !== $this->uploadDir . "defaultprofile.jpg" &&
            file_exists($user['imagepath'])) {
            unlink($user['imagepath']);
        }

        $this->userModel->deleteProducts($user['user_id']);

        $this->userModel->deleteUser($email);

        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>