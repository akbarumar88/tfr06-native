<?php

session_start();
// session_unset();
require_once('./config/db.php');
require_once('./helper/function.php');

// session_unset();
// dd($_SESSION);
if (!empty($_POST)) {
    // User Submit form, masuk sini
    $username = $_POST['username'];
    $password = $_POST['password'];
    try {
        $sql = "SELECT * from user where username='$username'";
        $user = $conn->query($sql)->fetch();

        // dd($user);
        if (!empty($user)) {
            // Username ditemukan, cocokkan password user.
            $password_match = password_verify($password, $user['password']);
            // dd([
            //     'password' => $password,
            //     'password_db' => $user['password'],
            //     'password_match' => $password_match
            // ]);
            if (!$password_match) {
                flash("res", "Password yang anda masukkan salah", "error");
                header("location: index.php");
                return;
            }
            // Set Session
            $_SESSION['userid'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];

            
        } else {
            // dd('masuk tidak ditemukanbroi');die;
            // User tidak ditemukan, set flash message
            flash("res", "Username tidak ditemukan dalam database", "error");
            // $_SESSION['error'] = 'Username tidak ditemukan dalam datae';
            header("location: index.php");
            return;
        }
        
        // dd($user);
    } catch (Exception $th) {
        flash("res", "Terjadi kesalahan saat melakukan Login " . $th->getMessage(), "error");
        header("location: index.php");
        return;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="./asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="./asset/css/style.css">
    <link rel="stylesheet" href="./asset/css/bootstrap-select.min.css">
    <link rel="shortcut icon" href="./asset/img/app-logo.jpg" type="image/x-icon">
    <title>POS</title>
    <!-- Font Awesome Script -->
    <script src="https://kit.fontawesome.com/bc14fa0285.js" crossorigin="anonymous"></script>
    <script src="./asset/js/jquery-3.6.0.min.js"></script>

    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login_form_wrapper {
            float: left;
            width: 100%;
            padding-top: 40px;
            padding-bottom: 100px;
            background-color: grey
        }

        .login_wrapper {
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e4e4e4;
            float: left;
            width: 100%;
            background: #fff;
            padding: 50px
        }

        .login_wrapper a.btn {
            color: #fff;
            width: 100%;
            height: 50px;
            padding: 6px 25px;
            line-height: 36px;
            margin-bottom: 20px;
            text-align: left;
            border-radius: 5px;
            background: #4385f5;
            font-size: 16px;
            border: 1px solid #4385f5
        }

        .login_wrapper a i {
            float: right;
            margin: 0;
            line-height: 35px
        }

        .login_wrapper a.google-plus {
            background: #db4c3e;
            border: 1px solid #db4c3e
        }

        .login_wrapper h2 {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #111;
            line-height: 20px;
            text-transform: uppercase;
            text-align: center;
            position: relative
        }

        .login_wrapper .formsix-pos,
        .formsix-e {
            position: relative
        }

        .form-group {
            margin-bottom: 15px
        }

        .login_wrapper .form-control {
            height: 53px;
            padding: 15px 20px;
            font-size: 14px;
            line-height: 24px;
            border: 1px solid #fafafa;
            border-radius: 3px;
            box-shadow: none;
            /* font-family: 'Roboto'; */
            -webkit-transition: all 0.3s ease 0s;
            -moz-transition: all 0.3s ease 0s;
            -o-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
            background-color: #fafafa
        }

        .login_wrapper .form-control:focus {
            color: #999;
            background-color: fafafa;
            border: 1px solid #4285f4 !important
        }

        .login_remember_box {
            margin-top: 30px;
            margin-bottom: 30px;
            color: #999
        }

        .login_remember_box .control {
            position: relative;
            padding-left: 20px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            margin: 0
        }

        .login_remember_box .control input {
            position: absolute;
            z-index: -1;
            opacity: 0
        }

        .login_remember_box .control__indicator {
            position: absolute;
            top: 0;
            left: 0;
            width: 15px;
            height: 15px;
            background: #fff;
            border: 1px solid #999
        }

        .login_remember_box .forget_password {
            float: right;
            color: #db4c3e;
            line-height: 12px;
            text-decoration: underline
        }

        .login_btn_wrapper {
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e4e4e4
        }

        .login_btn_wrapper .login_btn {
            text-align: center;
            text-transform: uppercase
        }

        .login_message p {
            text-align: center;
            font-size: 16px;
            margin: 0
        }

        p {
            color: #999999;
            font-size: 14px;
            line-height: 24px
        }

        .login_wrapper .login_btn:hover {
            background-color: #2c6ad4;
            border-color: #2c6ad4
        }

        .login_wrapper a.btn:hover {
            background-color: #2c6ad4;
            border-color: #2c6ad4
        }

        .login_wrapper a.google-plus:hover {
            background: #bd4033;
            border-color: #bd4033
        }
    </style>
</head>

<body>
    <div class="login_form_wrapper">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 col-md-offset-2">

                    <?php flash("res"); ?>
                    <!-- login_wrapper -->
                    <form class="login_wrapper" method="POST" action="">

                        <h2>Login</h2>
                        <div class="formsix-pos">
                            <div class="form-group i-username">
                                <input type="text" class="form-control" required="" id="username2" placeholder="Username *" name="username" value="<?= $_POST['username'] ?>">

                            </div>
                        </div>
                        <div class="formsix-e">
                            <div class="form-group i-password">
                                <input type="password" class="form-control" required="" id="password2" placeholder="Password *" name="password" <?= $_POST['password'] ?>>

                            </div>
                        </div>

                        <div class="login_btn_wrapper">
                            <button type="submit" class="btn btn-primary login_btn btn-block"> Login </button>
                        </div>

                    </form> <!-- /.login_wrapper-->
                </div>

                <div class="col-md-4">
                    <img style="width: 100%" src="./asset/img/user.png" alt="user-icon" class="d-block m-auto">
                </div>
            </div>
        </div>
    </div>
</body>

</html>