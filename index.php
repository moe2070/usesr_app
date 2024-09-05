<?php
session_start();
include_once('db.php');

// عرض الأخطاء للتأكد من عدم وجود أخطاء مخفية
error_reporting(E_ALL);
ini_set('display_errors', 1);

// جلب بيانات المستخدمين من قاعدة البيانات
$users_sql = "SELECT * FROM users";
$all_user = mysqli_query($con, $users_sql);

if (!$all_user) {
    die('Error fetching users: ' . mysqli_error($con));
}

// تنفيذ عملية الحذف إذا تم إرسالها
if (isset($_GET['action']) && $_GET['action'] == 'del') {
    $id = $_GET['id'];
    $del_sql = "DELETE FROM users WHERE id = $id";
    $res_del = mysqli_query($con, $del_sql);
    if (!$res_del) {
        die('Error deleting user: ' . mysqli_error($con));
    } else {
        $_SESSION['message'] = "User deleted successfully!";
        header("Location: index.php");
        exit();
    }
}

// تنفيذ عملية الإضافة أو التعديل
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    if ($_POST['save'] == "Save") {
        $save_sql = "INSERT INTO users (name, email, mobile, password) 
                     VALUES ('$name', '$email', '$mobile', '$password')";
        $res_save = mysqli_query($con, $save_sql);

        if (!$res_save) {
            die('Error inserting user: ' . mysqli_error($con));
        } else {
            $_SESSION['message'] = "User added successfully!";
            header("Location: index.php");
            exit();
        }
    } elseif ($_POST['save'] == "Update") {
        $id = $_POST['id'];
        $update_sql = "UPDATE users SET name='$name', email='$email', mobile='$mobile', password='$password' WHERE id = $id";
        $res_update = mysqli_query($con, $update_sql);

        if (!$res_update) {
            die('Error updating user: ' . mysqli_error($con));
        } else {
            $_SESSION['message'] = "User updated successfully!";
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/toster.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <title>Users App</title>
</head>

<body>
    <div class="container">
        <div class="wrapper p-5 m-5">
            <div class="d-flex p-2 justify-content-between mb-2">
                <h2>All Users</h2>
                <div><a href="add_user.php"><i data-feather="user-plus"></i></a></div>
            </div>
            <hr>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($all_user) > 0) {
                        while ($user = $all_user->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['name']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['mobile']; ?></td>
                                <td>
                                    <div class="d-flex p-2 justify-content-evenly mb-2">
                                        <a href="index.php?action=del&id=<?php echo $user['id']; ?>">
                                            <i class="text-danger" data-feather="trash-2"></i>
                                        </a>
                                        <a href="add_user.php?action=edit&id=<?php echo $user['id']; ?>">
                                            <i class="text-success" data-feather="edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                    <?php }
                    } else {
                        echo "<tr><td colspan='5'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="js/jq.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/icons.js"></script>
    <script src="js/toster.js"></script>
    <script>
        feather.replace();
    </script>

    <!-- Script لإظهار الإشعارات -->
    <script>
        <?php if (isset($_SESSION['message'])) { ?>
            toastr.success("<?php echo $_SESSION['message']; ?>");
            <?php unset($_SESSION['message']); ?>
        <?php } ?>
    </script>

</body>

</html>
