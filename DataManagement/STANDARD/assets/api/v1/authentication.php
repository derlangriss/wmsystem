<?php
$app->get('/session', function () {
    $db                        = new DbHandler();
    $session                   = $db->getSession();
    $response["idlogin_user"]        = $session['idlogin_user'];
    $response["firstname"]     = $session['firstname'];
    $response["lastname"]      = $session['lastname'];
    $response["email"]         = $session['email'];
    $response["avatar"]       = $session['avatar'];
    $response['user_status']   = $session['user_status'];
    $response['iduser_status'] = $session['iduser_status'];
    $response['idsection'] = $session['idsection']; 

    echoResponse(200, $session);
});

$app->post('/login', function () use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'password'), $r->formdata);
    $response = array();
    $db       = new DbHandler();
    $password = $r->formdata->password;
    $email    = $r->formdata->email;

    $user = $db->getOneRecord("select idlogin_user,firstname,lastname,password,email,avatar,user_status,iduser_status,idsection from login_user
    left join user_status on user_status.iduser_status =  login_user.user_status_iduser_status 
    left join section on section.idsection =  login_user.section_idsection where email ILIKE '$email'");
    if ($user != null) {
        if (passwordHash::check_password($user['password'], $password)) {
            $response['status']      = "success";
            $response['message']     = 'Logged in successfully.';
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['idlogin_user']         = $user['idlogin_user'];
            $_SESSION['firstname']   = $user['firstname'];
            $_SESSION['lastname']    = $user['lastname'];
            $_SESSION['email']       = $user['email'];
            $_SESSION['avatar']     = $user['avatar'];
            $_SESSION['user_status']   = $user['user_status'];
            $_SESSION['iduser_status'] = $user['iduser_status']; 
            $_SESSION['idsection'] = $user['idsection']; 

            /*
            $_SESSION['idlogin_user']         = $user['idlogin_user'];
            $_SESSION['firstname']   = $user['firstname'];
            $_SESSION['lastname']    = $user['lastname'];
            $_SESSION['email']       = $user['email'];
            $_SESSION['avatar']     = $user['avatar'];
            $_SESSION['user_status']   = $user['user_status'];
            $_SESSION['idlogin_user_status'] = $user['idlogin_user_status']; */

        } else {
            $response['status']  = "error";
            $response['message'] = 'Login failed. Incorrect credentials';
        }
    } else {
        $response['status']  = "error";
        $response['message'] = 'No such user is registered';
    }
    echoResponse(200, $response);
});
$app->post('/signUp', function () use ($app) {

    $response = array();
    $r        = json_decode($app->request->getBody());

    verifyRequiredParams(array('email', 'firstname', 'lastname', 'password'), $r->formdata);

    require_once 'passwordHash.php';
    $db            = new DbHandler();
    $firstname     = $r->formdata->firstname;
    $lastname      = $r->formdata->lastname;
    $email         = $r->formdata->email;
    $password      = $r->formdata->password;

    $isUserExists = $db->getOneRecord("select 1 from login_user where email ILIKE '$email'");
    if (!$isUserExists) {
        $r->formdata->password = passwordHash::hash($password);
        $table_name            = "login_user";
        $column_names          = array('firstname', 'lastname', 'email', 'password');
        $result                = $db->insertIntoTable($r->formdata, $column_names, $table_name);

        if ($result != null) {
            $response["status"]  = "success";
            $response["message"] = "User account created successfully";
            $response["idlogin_user"]     = $result;
            if (!isset($_SESSION)) {
                session_start();
            }
          
            echoResponse(200, $response);
        } else {
            $response["status"]  = "error";
            $response["message"] = "Failed to create formdata. Please try again";
            echoResponse(201, $response);
        }
    } else {;
        $response["status"]  = "error";
        $response["message"] = "An user with the provided phone or email exists!";
        echoResponse(201, $response);
    }
});
$app->get('/logout', function () {
    $db                  = new DbHandler();
    $session             = $db->destroySession();
    $response["status"]  = "info";
    $response["message"] = "Logged out successfully";
    echoResponse(200, $response);
});
