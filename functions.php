<?php
///////start: general functions//////
function nameRoleGreeting($dbh, $userfullname, $role_no)
{
    echo "Hi, " . $userfullname . "<br />" . "Role: " . getrole_names($dbh, $role_no);
}

function getrole_names($dbh, $role_no)
{
    $data = ["role_no" => $role_no];
    $sql = "SELECT role_name FROM myra.user_role WHERE role_no = :role_no";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $role_name = $d['role_name'];
    }
    else
        $role_name = "LECTURER";
    
    return $role_name;
}

function generateToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $codeAlphabet.= "-_.~";  // Special characters allowed in url
    $max = strlen($codeAlphabet);

    for ($i=0; $i < $length; $i++) 
    {
        $token .= $codeAlphabet[random_int(0, $max-1)]; //random_int() - php7, rand() - php5
    }

    return $token;
}

function password_generate($chars) 
{
    $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
    
    return substr(str_shuffle($data), 0, $chars);
}

function changeCaseToUpper($val)
{
    return strtoupper($val);
}

function getTimestamp()
{
    $timestamp = date("Y-m-d H:i:s");

    return $timestamp;
}

function getIPAddress()
{
    /*if(!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return $ip;*/
    $ipAddress = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
    {
        // to get shared ISP IP address
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } 
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    {
        // check for IPs passing through proxy servers
        // check if multiple IP addresses are set and take the first one
        $ipAddressList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ipAddressList as $ip) 
        {
            if (!empty($ip)) 
            {
                // if you prefer, you can check for valid IP address here
                $ipAddress = $ip;
                break;
            }
        }
    } 
    else if (!empty($_SERVER['HTTP_X_FORWARDED'])) 
    {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    } 
    else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) 
    {
        $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } 
    else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) 
    {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } 
    else if (!empty($_SERVER['HTTP_FORWARDED'])) 
    {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    } 
    else if (!empty($_SERVER['REMOTE_ADDR'])) 
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    else if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) 
    {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }

    return $ipAddress;
}

function trimValue($val)
{
    return trim($val);
}

function displayfiletypeicon($filetype)
{
    $icon = "";
    switch($filetype)
    {
        case "image/png":
        case "image/PNG":
        case "image/jpg":
        case "image/JPG":
        case "image/jpeg":
        case "image/JPEG":
            $icon = "far fa-file-image";
            break;
        case "application/pdf":
        case "application/PDF":
            $icon = "far fa-file-pdf";
            break;
        default:
            $icon = "far fa-file";
    }
    
    return $icon;
}

function checkReportToken($dbh, $USER_ID, $token)
{
    $found = false;
    $data = ["USER_ID" => $USER_ID, "token" => $token];
    $sql = "SELECT reporttoken FROM myra.reports WHERE USER_ID = :USER_ID AND BINARY reporttoken = :token";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $found = true;    
    }
    
    return $found;
}

function checkReportExist($dbh, $token)
{
    $found = false;
    $data = ["token" => $token];
    $sql = "SELECT reporttoken FROM myra.reports WHERE BINARY reporttoken = :token";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $found = true;    
    }
    
    return $found;
}

function saveAuditLogin($dbh, $USER_ID, $role_no, $ipaddress, $loginlocation, $logintimestamp, $logintoken)
{
    $data = ["USER_ID" => $USER_ID, "role_no" => $role_no, "audit_ip" => $ipaddress, "audit_location" => $loginlocation, "audit_login" => $logintimestamp, "token" => $logintoken];
    $sql = "INSERT INTO myra.auditlogin (USER_ID, role_no, audit_ip, audit_location, audit_login, token) VALUES (:USER_ID, :role_no, :audit_ip, :audit_location, :audit_login, :token)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
}

function saveAuditLogout($dbh, $logintoken, $logouttime)
{
    /* $updated_at = getTimestamp();
    $data = ["token" => $logintoken, "audit_logout" => $logouttime, "updatedat" => $updated_at]; */
    $data = ["token" => $logintoken, "audit_logout" => $logouttime];
    $sql = "UPDATE myra.auditlogin SET audit_logout = :audit_logout WHERE token = :token";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
}


function getfaculties($dbh3)
{
    $sql = "SELECT * FROM myra";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<option value='".$d['JA_KOD_JABATAN']."'>".$d['JABATAN']."</option>";    
        }
    }
}

function getselectedfaculties($dbh3, $facultyid)
{
    $sql = "SELECT * FROM myra";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            if($d['JA_KOD_JABATAN'] == $facultyid)
                echo "<option value='".$d['JA_KOD_JABATAN']."' selected>".$d['JABATAN']."</option>";
            else
                echo "<option value='".$d['JA_KOD_JABATAN']."'>".$d['JABATAN']."</option>";
        }
    }
}

function getFacultiesByRole($dbh3, $facultyid, $role_no)
{
    $allowedroles = array(2, 3);
    $data = ["facultyid" => $facultyid];
    if($role_no == 1 || $role_no == 4) //sys admin, hea
    {
        $sql = "SELECT * FROM myra";
        $stmt = $dbh3->prepare($sql);
        $stmt->execute();
    }
    else if(in_array($role_no, $allowedroles)) //kpp, koordinator
    {
        $sql = "SELECT * FROM myra WHERE JA_KOD_JABATAN = :facultyid";
        $stmt = $dbh3->prepare($sql);
        $stmt->execute($data);    
    }
    
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<option value='".$d['JA_KOD_JABATAN']."'>".$d['JABATAN']."</option>";    
        }
    }
}

function checkReportsFacultyByRole($dbh, $month, $year, $facultyid)
{
    $found = false;
    $data = ["month" => $month, "year" => $year, "facultyid" => $facultyid];
    $sql = "SELECT u.USER_ID, u.USER_NAME, f.JABATAN, r.reportcourse, c.name_course_eng, r.reportgroup, m.monthtitle, r.reportyear, r.reporttoken
    FROM myra.reports r 
    JOIN classbook_backup_jengka.vw_staff_phg u ON r.USER_ID = u.USER_ID 
    JOIN myra f ON u.JA_KOD_JABATAN = f.JA_KOD_JABATAN 
    JOIN classbook_backup_jengka.vw_active_courses c ON r.reportcourse = c.course_id
    JOIN myra.months m ON r.reportmonth = m.monthid
    WHERE r.reportmonth = :month AND r.reportyear = :year AND r.deleted_at IS NULL AND u.USER_DEPTCAMPUS = :facultyid";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $found = true;
    }
    return $found;
}

function getReportsFacultyByRole($dbh, $month, $year, $facultyid)
{
    $allowedroles = array(2, 3);
    $data = ["month" => $month, "year" => $year, "facultyid" => $facultyid];
    $sql = "SELECT u.USER_ID, u.USER_NAME, f.JABATAN, r.reportcourse, c.name_course_eng, r.reportgroup, m.monthtitle, r.reportyear, r.reporttoken
    FROM myra.reports r 
    JOIN classbook_backup_jengka.vw_staff_phg u ON r.USER_ID = u.USER_ID 
    JOIN myra f ON u.USER_DEPTCAMPUS = f.JA_KOD_JABATAN 
    JOIN classbook_backup_jengka.vw_active_courses c ON r.reportcourse = c.course_id
    JOIN myra.months m ON r.reportmonth = m.monthid
    WHERE r.reportmonth = :month AND r.reportyear = :year AND r.deleted_at IS NULL AND u.USER_DEPTCAMPUS = :facultyid 
    ORDER BY 2 ASC, 4 ASC, 6 ASC";

    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $count = 0;
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['USER_NAME']."</td>";
                echo "<td>".$d['JABATAN']."</td>";
                echo "<td>".$d['reportcourse']." - ".$d['name_course_eng']."</td>";
                echo "<td>".$d['reportgroup']."</td>";
                echo "<td>".$d['monthtitle']." ".$d['reportyear']."</td>";
                echo "<td><button type='button' name='view' class='btn btn-info' title='View' onclick='window.location=\"viewreport.php?id=".$d['reporttoken']."\";'><i class='fa fa-eye'></i></button></td>";
            echo "</tr>";
            $count++;
        }
    }
}

function getFacultyId($dbh3, $USER_ID)
{
    $data = ["USER_ID" => $USER_ID];
    $sql = "SELECT u.JA_KOD_JABATAN FROM classbook_backup_jengka.vw_staff_phg u WHERE u.USER_ID = :USER_ID";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['JA_KOD_JABATAN'];
    }
}

function getFacultyTitle($dbh3, $facultyid)
{
    $data = ["facultyid" => $facultyid];
    $sql = "SELECT f.JABATAN FROM myra f WHERE f.JA_KOD_JABATAN = :facultyid";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['JABATAN'];
    }
}

function getSemesterActive($dbh3)
{
    $sql = "SELECT SEMESTER_ID FROM classbook_backup_jengka.semester WHERE SEMESTER_ACTIVE = 1";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['SEMESTER_ID'];
    }
}
///////end: general functions//////

/////start: ptft functions/////

function checkPtftStaffNo($dbh, $staffno)
{
    $found = false;
    $data = ["id" => $staffno];
    $sql = "SELECT * FROM dbptftphg.ptfts WHERE PTFT_ID = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $found = true;    
    }
    
    return $found;
}

function savePtft($dbh, $USER_ID, $name, $icnum, $faculty, $uitmemail, $altemail, $contactnum, $password, $isactive, $token)
{
    $save = false;
    //$created_at = getTimestamp();
    $data = ["staffno" => $USER_ID, "name" => $name, "icnum" => $icnum, "faculty" => $faculty, "uitmemail" => $uitmemail, "altemail" => $altemail, "contactnum" => $contactnum, "password" => $password, "faculty" => $faculty, "isactive" => $isactive, "token" => $token];
    $sql = "INSERT INTO dbptftphg.ptfts (PTFT_ID, fullname, icnum, password, emailaddressuitm, emailaddressalternate, contactnum, isactive, JA_KOD_JAB, ptfttoken) VALUES (:staffno, :name, :icnum, :password, :uitmemail, :altemail, :contactnum, :isactive, :faculty, :token)";
    $stmt = $dbh->prepare($sql);
    //$stmt->execute($data);
    
    if($stmt->execute($data) == true)
        $save = true;
    
    return $save;
}

function checkResetPassword($dbh, $USER_ID, $email)
{
    $found = false;
    $data = ["ptftid" => $USER_ID, "email" => $email];
    $sql = "SELECT * FROM dbptftphg.ptfts WHERE PTFT_ID = :ptftid AND emailaddressuitm = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $found = true;    
    }
    
    return $found;
}

function checkPtftLogin($dbh, $dbh3, $USER_ID, $password)
{
    $found = false;
    $data = ["USER_ID" => $USER_ID, "password" => $password];
    $sql = "SELECT * FROM classbook_backup_jengka.user a WHERE a.USER_ID = :USER_ID 
    AND a.USER_ICNO = :password AND a.USER_SIRSACCESS = 1";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        session_start(); 
        $_SESSION['userislogged'] = 1; // 1 - user is successfully logged
        $_SESSION['USER_ID'] = $USER_ID;
        $_SESSION['userfullname'] = $d['USER_NAME'];
        
        //get role or assign default role
        //if(!is_null($d['role_no']))
        //{
        //    $role_no = $d['role_no'];
        //    $_SESSION['role_no'] = $d['role_no'];
        //}
        //else
        //{
            $role_no = 56; //55->56
            $_SESSION['role_no'] = 56; //default - ptft
        //}
        
        $logintoken = generateToken(32);
        $_SESSION['logintoken'] = $logintoken;
        $ipaddress = getIPAddress();
        $logintimestamp = getTimestamp();
        
        //get location
        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ipaddress"));
        $country = $geo["geoplugin_countryName"];
        $city = $geo["geoplugin_city"];
        $region = $geo["geoplugin_region"];
        $loginlocation = "{$country}/{$region}/{$city}";
        
        //audit login
        saveAuditLogin($dbh, $USER_ID, $role_no, $ipaddress, $loginlocation, $logintimestamp, $logintoken);
        
        $found = true;
    }
    
    return $found;
}

function saveauditemail($dbh, $USER_ID, $receiveremail, $status, $type)
{
    $timestamp = getTimestamp();
    $data = ["ptftid" => $USER_ID, "receiver" => $receiveremail, 
            "type" => $type, "status" => $status, "timestamp" => $timestamp];
    $sql = "INSERT INTO myra.auditemails (PTFT_ID, receiveremail, sendstatus, type, created_at) VALUES (:ptftid, :receiver, :status, :type, :timestamp)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
}

function saveauditreset($dbh2, $USER_ID, $uitmemail, $status)
{
    $timestamp = getTimestamp();
    $data = ["ptftid" => $USER_ID, "receiver" => $uitmemail, 
            "status" => $status, "timestamp" => $timestamp];
    $sql = "INSERT INTO myra.auditresetpassword (PTFT_ID, receiveremail, sendstatus, created_at) VALUES (:ptftid, :receiver, :status, :timestamp)";
    $stmt = $dbh2->prepare($sql);
    $stmt->execute($data);
}

function resetPtftPassword($dbh2, $USER_ID, $temppassword)
{
    $status = false;
    $timestamp = getTimestamp();
    $data = ["USER_ID" => $USER_ID, "tmppass" => $temppassword, "timestamp" => $timestamp];
    $sql = "UPDATE dbptftphg.ptfts SET password = :tmppass, updated_at = :timestamp WHERE PTFT_ID = :USER_ID";
    $stmt = $dbh2->prepare($sql);
    
    if($stmt->execute($data))
        $status = true;
    
    return $status;
}

function updatePtftProfile($dbh2, $id, $name, $icnum, $uitmemail, $altemail, $contact)
{
    $status = false;
    $timestamp = getTimestamp();
    $data = ["id" => $id, "name" => $name, "icnum" => $icnum, "uitmemail" => $uitmemail, "altemail" => $altemail, "contact" => $contact, "timestamp" => $timestamp];
    $sql = "UPDATE dbptftphg.ptfts SET fullname = :name, icnum = :icnum, 
            emailaddressuitm = :uitmemail, emailaddressalternate = :altemail, 
            contactnum = :contact, updated_at = :timestamp WHERE PTFT_ID = :id";
    $stmt = $dbh2->prepare($sql);
    
    if($stmt->execute($data))
        $status = true;
    
    return $status;
}

function changePtftPassword($dbh2, $id, $curpassword, $newpassword)
{
    $status = false;
    $timestamp = getTimestamp();
    $curpassword = md5($curpassword);
    $newpassword = md5($newpassword);
    $data = ["id" => $id, "curpassword" => $curpassword, "newpassword" => $newpassword, "timestamp" => $timestamp];
    $sql = "UPDATE dbptftphg.ptfts SET password = :newpassword, updated_at = :timestamp WHERE PTFT_ID = :id AND password = :curpassword";
    $stmt = $dbh2->prepare($sql);
    
    if($stmt->execute($data))
        $status = true;
    
    return $status;
}
/////end: ptft functions/////

/////start: permanent staff/ptft functions/////

function checkStaffLogin($dbh, $USER_ID)
{
    $found = false;
    $data = ["USER_ID" => $USER_ID];
    $sql = "SELECT * FROM myra.user_assigned ua JOIN myra.user u ON ua.USER_ID = u.USER_ID WHERE u.USER_ID='".$USER_ID."'AND ua.access_no=1 ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        session_start(); 
        $_SESSION['userislogged'] = 1; // 1 - user is successfully logged
        $_SESSION['USER_ID'] = $USER_ID;
        $_SESSION['USER_NAME'] = $d['USER_NAME'];
        
        //get role or assign default role
        if(!is_null($d['role_no']) && $d['allowedaccess'] != 0)
        {
            $role_no = $d['role_no'];
            $_SESSION['role_no'] = $d['role_no'];
        }
        else
        {
            $role_no = 55;
            $_SESSION['role_no'] = 55; //default - lecturer
        }
        
        $logintoken = generateToken(32);
        $_SESSION['logintoken'] = $logintoken;
        $ipaddress = getIPAddress();
        $logintimestamp = getTimestamp();
        
        //get location
        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ipaddress"));
        $country = $geo["geoplugin_countryName"];
        $city = $geo["geoplugin_city"];
        $region = $geo["geoplugin_region"];
        $loginlocation = "{$country}/{$region}/{$city}";
        
        //audit login
        saveAuditLogin($dbh, $USER_ID, $role_no, $ipaddress, $loginlocation, $logintimestamp, $logintoken);
        
        $found = true;
    }
    
    return $found;
}

function savemyreport($dbh, $semester, $rptrefno, $USER_ID, $month, $year, $course, $group, $numstudent, $participation, $assessment, $submission, $action, $remarks, $notify, $students)
{
    $status = false;
    $reporttoken = generateToken(32);
    $data = ["semester" => $semester, "rptrefno" => $rptrefno, "id" => $USER_ID, "month" => $month, "year" => $year, "course" => $course, "group" => $group, "numstudent" => $numstudent, "participation" => $participation, "assessment" => $assessment, "submission" => $submission, "action" => $action, "remarks" => $remarks, "notify" => $notify, "students" => $students, "token" => $reporttoken];
    $sql = "INSERT INTO myra.reports (reportrefno, SEMESTER_ID, USER_ID, reportmonth, reportyear, reportcourse, reportgroup, reportnumstudent, reportparticipation, reportassessment, reportsubmission, reportaction, reportremarks, reporthea, reportstudents, reporttoken) VALUES (:rptrefno, :semester, :id, :month, :year, :course, :group, :numstudent, :participation, :assessment, :submission, :action, :remarks, :notify, :students, :token)";
    
    $stmt = $dbh->prepare($sql);
    //$stmt->execute($data);
    if($stmt->execute($data))
        $status = true;
    
    return $status;
}

function updatemyreport($dbh, $rpttoken, $rptrefno, $month, $year, $course, $group, $numstudent, $participation, $assessment, $submission, $action, $remarks, $notify, $students)
{
    $timestamp = getTimestamp();
    $data = ["rpttoken" => $rpttoken, "rptrefno" => $rptrefno, "month" => $month, "year" => $year, "course" => $course, "group" => $group, "numstudent" => $numstudent, "participation" => $participation, "assessment" => $assessment, "submission" => $submission, "action" => $action, "remarks" => $remarks, "notify" => $notify, "students" => $students, "timestamp" => $timestamp];
    $sql = "UPDATE myra.reports SET reportmonth = :month, reportyear = :year, reportcourse = :course, reportgroup = :group, reportnumstudent = :numstudent, reportparticipation = :participation, reportassessment = :assessment, reportsubmission = :submission, reportaction = :action, reportremarks = :remarks, reporthea = :notify, reportstudents = :students, updated_at = :timestamp WHERE reporttoken = :rpttoken AND reportrefno = :rptrefno";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
}

function deletemyreport($dbh, $rpttoken)
{
    $status = false;
    $timestamp = getTimestamp();
    $data = ["rpttoken" => $rpttoken, "timestamp" => $timestamp];
    $sql = "UPDATE myra.reports SET deleted_at = :timestamp WHERE BINARY reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    
    if($stmt->execute($data))
        $status = true;
    
    return $status;
}

function saveevidence($dbh, $rptrefno, $filename, $filetype, $filesize)
{
    $data = ["rptrefno" => $rptrefno, "evidencefilename" => $filename, "evidencefiletype" => $filetype, "evidencefilesize" => $filesize];
    $sql = "INSERT INTO myra.evidences (reportrefno, evidencefilename, evidencefiletype, evidencefilesize) VALUES (:rptrefno, :evidencefilename, :evidencefiletype, :evidencefilesize)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    
    /*if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
    }*/
}

function getmonths($dbh)
{
    $sql = "SELECT * FROM myra.months ORDER BY monthid";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<option value='".$d['monthid']."'>".$d['monthtitle']."</option>";    
        }
    }
}

function getmonthtitle($dbh, $month)
{
    $data = ["month" => $month];
    $sql = "SELECT * FROM myra.months WHERE monthid = :month";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['monthtitle'];    
    }
}

function getselectedmonths($dbh, $month)
{
    $sql = "SELECT * FROM myra.months ORDER BY monthid";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            if($d['monthtitle'] == $month)
                echo "<option value='".$d['monthid']."' selected>".$d['monthtitle']."</option>";
            else
                echo "<option value='".$d['monthid']."'>".$d['monthtitle']."</option>";
        }
    }
}

function getListYears()
{
    $currentYear = date("Y");
    $startYear = $currentYear - 5;
    $endYear = $currentYear + 5;
    for($i = $startYear; $i < $endYear; $i++)
        echo "<option value='".$i."'>".$i."</option>";
}

function getSelectedListYears($year)
{
    $currentYear = date("Y");
    $startYear = $currentYear - 5;
    $endYear = $currentYear + 5;
    for($i = $startYear; $i < $endYear; $i++)
    {
        if($i == $year)
            echo "<option value='".$i."' selected>".$i."</option>";
        else
            echo "<option value='".$i."'>".$i."</option>";
    }
}

function getcourses($dbh)
{
    $sql = "SELECT * FROM classbook_backup_jengka.vw_active_courses";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<option value='".$d['course_id']."'>".$d['code_course']." - ".$d['name_course_eng']."</option>";    
        }
    }
}

function getselectedcourses($dbh, $courseid)
{
    $sql = "SELECT * FROM classbook_backup_jengka.vw_active_courses";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            if($d['course_id'] == $courseid)
            {
                echo "<option value='".$d['course_id']."' selected>".$d['code_course']." - ".$d['name_course_eng']."</option>"; 
            }   
            else
            {
                echo "<option value='".$d['course_id']."'>".$d['code_course']." - ".$d['name_course_eng']."</option>";    
            }
        }
    }
}

function listmyreports($dbh, $USER_ID)
{
    $data = ["id" => $USER_ID];
    $sql = "SELECT * FROM myra.reports a JOIN myra.months m ON a.reportmonth = m.monthid WHERE a.USER_ID = :id AND a.deleted_at IS NULL";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $count = 0;
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['reportcourse']."</td>";
                echo "<td>".$d['reportgroup']."</td>";
                echo "<td>".$d['monthtitle']." ".$d['reportyear']."</td>";           echo "<td align='center'>";
                    if($d['reporthea'] == 1)
                    {
                        echo "<i class='fas fa-bullhorn'></i>";
                    }
                echo "</td>";
                echo "<td>".$d['created_at']."</td>";
                echo "<td><button type='button' name='view' class='btn btn-info' title='View' onclick='window.location=\"viewmyreport.php?id=".$d['reporttoken']."\";'><i class='fa fa-eye'></i></button> <button type='button' name='edit' class='btn btn-info' title='Edit' onclick='window.location=\"editmyreport.php?id=".$d['reporttoken']."\";'><i class='fas fa-pencil-alt'></i></button> <button class='btn btn-danger' data-href='listmyreports.php?id=".$d['reporttoken']."' data-toggle='modal' data-target='#confirm-edit'><i class='far fa-trash-alt'></i></button></td>";
            echo "</tr>";
            $count++;
        }
    }
}

function listallreports($dbh, $facultyid, $role_no)
{
    $allowedroles = array(2, 3);
    $data = ["facultyid" => $facultyid];
    if($role_no == 1 || $role_no == 4) //sys admin, hea
    {
        $sql = "SELECT u.USER_NAME, a.reportcourse, a.reportgroup, m.monthid, m.monthtitle, a.reportyear, a.reporthea, a.reporttoken, a.created_at 
        FROM classbook_backup_jengka.vw_staff_phg u 
        JOIN myra.reports a ON u.USER_ID = a.USER_ID 
        JOIN months m ON a.reportmonth = m.monthid 
        WHERE a.deleted_at IS NULL";   
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
    }
    else if(in_array($role_no, $allowedroles)) //kpp, koordinator
    {
        $sql = "SELECT u.USER_NAME, a.reportcourse, a.reportgroup, m.monthid, m.monthtitle, a.reportyear, a.reporthea, a.reporttoken, a.created_at 
        FROM classbook_backup_jengka.vw_staff_phg u 
        JOIN myra.reports a ON u.USER_ID = a.USER_ID 
        JOIN months m ON a.reportmonth = m.monthid 
        WHERE u.USER_DEPTCAMPUS = :facultyid AND a.deleted_at IS NULL";  

        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
    }    
    
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $count = 0;
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['USER_NAME']."</td>";
                echo "<td>".$d['reportcourse']."</td>";
                echo "<td>".$d['reportgroup']."</td>";
                echo "<td>".$d['monthtitle']." ".$d['reportyear']."</td>";
                echo "<td align='center'>";
                    if($d['reporthea'] == 1)
                    {
                        echo "<i class='fas fa-bullhorn'></i>";
                    }
                echo "</td>";
                echo "<td>".$d['created_at']."</td>";
                echo "<td><button type='button' name='view' class='btn btn-info' title='View' onclick='window.location=\"viewreport.php?id=".$d['reporttoken']."\";'><i class='fa fa-eye'></i></button></td>";
            echo "</tr>";
            $count++;
        }
    }
}

function listactionreports($dbh, $facultyid, $role_no)
{
    $allowedroles = array(2, 3);
    $data = ["facultyid" => $facultyid];
    if($role_no == 1 || $role_no == 4) //sys admin, hea
    {
        $sql = "SELECT u.USER_NAME, a.reportrefno, a.reportcourse, a.reportgroup, m.monthid, m.monthtitle, a.reportyear, a.reporthea, a.reporttoken, a.created_at 
        FROM classbook_backup_jengka.vw_staff_phg u 
        JOIN myra.reports a ON u.USER_ID = a.USER_ID 
        JOIN months m ON a.reportmonth = m.monthid 
        WHERE a.reporthea = 1 AND a.deleted_at IS NULL";   
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
    }
    else if(in_array($role_no, $allowedroles)) //kpp, koordinator
    {
        $sql = "SELECT u.USER_NAME, a.reportrefno, a.reportcourse, a.reportgroup, m.monthid, m.monthtitle, a.reportyear, a.reporthea, a.reporttoken, a.created_at 
        FROM classbook_backup_jengka.vw_staff_phg u 
        JOIN myra.reports a ON u.USER_ID = a.USER_ID 
        JOIN months m ON a.reportmonth = m.monthid 
        WHERE u.USER_DEPTCAMPUS = :facultyid AND a.reporthea = 1 AND a.deleted_at IS NULL";  
        
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
    }    
    
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $count = 0;
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['reportrefno']."</td>";
                echo "<td>".$d['USER_NAME']."</td>";
                echo "<td>".$d['reportcourse']."</td>";
                echo "<td>".$d['reportgroup']."</td>";
                echo "<td>".$d['monthtitle']." ".$d['reportyear']."</td>";
                echo "<td align='center'>";
                    if($d['reporthea'] == 1)
                    {
                        echo "<i class='fas fa-bullhorn'></i>";
                    }
                echo "</td>";
                echo "<td>".$d['created_at']."</td>";
                echo "<td><button type='button' name='view' class='btn btn-info' title='View' onclick='window.location=\"viewreport.php?id=".$d['reporttoken']."\";'><i class='fa fa-eye'></i></button></td>";
            echo "</tr>";
            $count++;
        }
    }
}


function getReportRefNo($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT r.reportrefno FROM myra.reports r WHERE BINARY r.reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportrefno'];
    }
}

function getReportSemester($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT r.SEMESTER_ID FROM myra.reports r WHERE BINARY r.reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['SEMESTER_ID'];
    }
}

function getReportMonth($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT m.monthtitle FROM myra.reports r JOIN myra.months m ON r.reportmonth = m.monthid WHERE BINARY r.reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['monthtitle'];
    }
}

function getReportYear($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT r.reportyear FROM myra.reports r WHERE BINARY r.reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportyear'];
    }
}

function getReportLecturer($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT u.USER_NAME FROM myra.reports r JOIN classbook_backup_jengka.vw_staff_phg u ON r.USER_ID = u.USER_ID WHERE BINARY r.reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['USER_NAME'];
    }
}

function getReportCourse($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT r.reportcourse, c.name_course_eng FROM myra.reports r JOIN classbook_backup_jengka.vw_active_courses c ON r.reportcourse = c.course_id WHERE BINARY r.reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportcourse'] . " - " . $d['name_course_eng'];
    }
}

function getReportCourseId($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT r.reportcourse FROM myra.reports r JOIN classbook_backup_jengka.vw_active_courses c ON r.reportcourse = c.course_id WHERE BINARY r.reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportcourse'];
    }
}


function getReportGroup($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT reportgroup FROM myra.reports WHERE reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportgroup'];
    }
}

function getReportNumStudent($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT reportnumstudent FROM myra.reports WHERE BINARY reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportnumstudent'];
    }
}


function getReportParticipation($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT reportparticipation FROM myra.reports WHERE BINARY reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportparticipation'];
    }
}

function getReportAssessment($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT reportassessment FROM myra.reports WHERE BINARY reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportassessment'];
    }
}

function getReportSubmission($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT reportsubmission FROM myra.reports WHERE BINARY reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportsubmission'];
    }
}

function getReportAction($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT reportaction FROM myra.reports WHERE BINARY reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportaction'];
    }
}

function getReportRemarks($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT reportremarks FROM myra.reports WHERE BINARY reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportremarks'];
    }
}

function getReportNotify($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT reporthea FROM myra.reports WHERE BINARY reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reporthea'];
    }
}

function getReportStudents($dbh, $rpttoken)
{
    $data = ["rpttoken" => $rpttoken];
    $sql = "SELECT reportstudents FROM myra.reports WHERE BINARY reporttoken = :rpttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['reportstudents'];
    }
}

function getequationevidencelist($dbh, $rpttoken)
{
    $data = ["reporttoken" => $rpttoken]; 
    $sql = "SELECT * FROM reports e JOIN evidences ee ON e.reportrefno = ee.reportrefno WHERE BINARY e.reporttoken = :reporttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $count = 0;
        $dir = "../evidences/";
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['evidencefilename']."</td>";
                echo "<td><i class='".displayfiletypeicon($d['evidencefiletype'])."'></i> ".$d['evidencefiletype']."</td>";
                echo "<td><button type='button' name='view' class='btn btn-info' title='View' onclick=\"window.open('".$dir.$d['evidencefilename']."', '_blank');\"><i class='fa fa-eye'></i></button></td>";
            echo "</tr>";
            $count++;
        }
    }
}

function getequationevidencelistedit($dbh, $rpttoken)
{
    $data = ["reporttoken" => $rpttoken]; 
    $sql = "SELECT * FROM reports e JOIN evidences ee ON e.reportrefno = ee.reportrefno WHERE BINARY e.reporttoken = :reporttoken";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $count = 0;
        $dir = "../evidences/";
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['evidencefilename']."</td>";
                echo "<td><i class='".displayfiletypeicon($d['evidencefiletype'])."'></i> ".$d['evidencefiletype']."</td>";
                echo "<td><button type='button' name='view' class='btn btn-info' title='View' onclick=\"window.open('".$dir.$d['evidencefilename']."', '_blank');\"><i class='fa fa-eye'></i></button> <button type='button' name='btndelete' id='btndelete' class='btn btn-danger confirm-delete'   title='Delete' data-href='editmyreport.php?id=".$rpttoken."&rid=".$d['reportrefno']."&eid=".$d['evidenceid']."&success' data-toggle='modal' data-target='#delete'><i class='far fa-trash-alt'></i></button></td>";
            echo "</tr>";
            $count++;
        }
    }
}

function getevidencefilename($dbh, $evidenceid)
{
    $data = ["evidenceid" => $evidenceid];
    $sql = "SELECT evidencefilename FROM evidences WHERE evidenceid = :evidenceid";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['evidencefilename'];
    }
}

function deletereportevidence($dbh, $reportrefno, $evidenceid)
{
    $opdelete = false;
    $path = "../evidences/";
    $data = ["rptrefno" => $reportrefno, "evidenceid" => $evidenceid];
    $evidencefilename = getevidencefilename($dbh, $evidenceid);
    $sql = "DELETE FROM evidences WHERE reportrefno = :rptrefno AND evidenceid = :evidenceid";
    
    if(unlink($path.$evidencefilename))
    {
        $stmt = $dbh->prepare($sql);
        if($stmt->execute($data))
        {
            $opdelete = true;
        }    
    }    
        
    return $opdelete;        
}
/////end: permanent staff functions/////

/////start: system administrator functions/////
function listusers($dbh)
{
    $sql = "SELECT u.USER_ID, u.USER_NAME, u.JABATAN, r.role_name, IF(s.allowedaccess = 1, 'YES', 'NO') as isallowed, s.created_at, s.userrole_no FROM classbook_backup_jengka.vw_staff_phg u JOIN myra.user_role s ON u.USER_ID = s.USER_ID JOIN myra.roles r ON s.role_no = r.role_no LEFT JOIN myra j ON u.USER_DEPTCAMPUS = j.JA_KOD_JABATAN";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $count = 0;
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['USER_ID']."</td>";
                echo "<td>".$d['USER_NAME']."</td>";
                echo "<td>".$d['JABATAN']."</td>";
                echo "<td>".$d['role_name']."</td>";                
                echo "<td>".$d['isallowed']."</td>";
                echo "<td>".$d['created_at']."</td>";
                echo "<td><button type='button' name='view' class='btn btn-info' title='View' onclick='window.location=\"viewuser.php?id=".$d['userrole_no']."\";'><i class='fa fa-eye'></i></button> <button type='button' name='edit' class='btn btn-info' title='Edit' onclick='window.location=\"edituser.php?id=".$d['userrole_no']."\";'><i class='fas fa-pencil-alt'></i></button></td>";
            echo "</tr>";
            $count++;
        }
    }
}

function listlecturers($dbh)
{
    $sql = "SELECT u.USER_ID, u.USER_NAME, u.JABATAN, u.USER_DEPTCAMPUS, f.JABATAN AS JAB_KAMPUS, c.JABATAN AS CAMPUS
    FROM classbook_backup_jengka.vw_staff_phg u 
    JOIN myra f ON u.USER_DEPTCAMPUS = f.JA_KOD_JABATAN
    JOIN classbook_backup_jengka.jabatan c ON u.USER_DEPARTMENT = c.JA_KOD_JAB
    WHERE u.JA_KOD_JABATAN NOT IN ('C0001', 'C0010')
    ORDER BY u.JA_KOD_JABATAN ASC, u.USER_NAME ASC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $count = 0;
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['USER_ID']."</td>";
                echo "<td>".$d['USER_NAME']."</td>";
                echo "<td>".$d['JABATAN']."</td>";
                echo "<td>".$d['JAB_KAMPUS']."</td>";
                echo "<td>".$d['CAMPUS']."</td>";
                echo "<td><button type='button' name='edit' class='btn btn-info' title='Edit' onclick='window.location=\"editlecturer.php?id=".$d['USER_ID']."\";'><i class='fas fa-pencil-alt'></i></button></td>";
            echo "</tr>";
            $count++;
        }
    }
}

function updatelecturer($dbh3, $staffno, $deptcampus)
{
    $data = ["USER_ID" => $staffno, "deptcampus" => $deptcampus];
    $sql = "UPDATE vw_staff_phg SET USER_DEPTCAMPUS = :deptcampus WHERE
        USER_ID = :USER_ID";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
}

function listptfts($dbh, $dbh3, $facultyid, $role_no)
{
    $allowedallptfts = array(1, 4); //sys admin, hea
    $allowedselectedptfts = array(2, 3); //kpp, koordinator
    //$allowedroles = array(2, 3);
    //$sql = "SELECT p.*, j.*, IF(p.isactive = 1, 'YES', 'NO') as isactive FROM dbptftphg.ptfts p JOIN myra j ON p.JA_KOD_JAB = j.JA_KOD_JABATAN";
    $sql = "SELECT U.*, IF(U.USER_SIRSACCESS = 1, 'YES', 'NO') AS USER_SIRSACCESS
            FROM classbook_backup_jengka.vw_staff_phg U 
            WHERE U.USER_KODJAWATAN = 'Z110'";
    if(in_array($role_no, $allowedallptfts)) //sysadmin, hea
    {
        $sql = $sql;
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
    }
    else if(in_array($role_no, $allowedselectedptfts))//kpp, koordinator
    {
        $data = ["facultyid" => $facultyid];
        //$sql = $sql . " WHERE p.JA_KOD_JAB = :facultyid"; //asal
        $sql = $sql . " AND U.USER_DEPTCAMPUS = :facultyid"; //new
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
    }    
    
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $count = 0;
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            /*echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['PTFT_ID']."</td>";
                echo "<td>".$d['fullname']."</td>";
                echo "<td>".$d['icnum']."</td>";
                echo "<td>".$d['JABATAN']."</td>";
                echo "<td>".$d['isactive']."</td>";
                echo "<td>".$d['created_at']."</td>";
                echo "<td><button type='button' name='view' class='btn btn-info' title='View' onclick='window.location=\"viewptft.php?id=".$d['ptfttoken']."\";'><i class='fa fa-eye'></i></button> <button type='button' name='edit' class='btn btn-info' title='Edit' onclick='window.location=\"editptft.php?id=".$d['ptfttoken']."\";'><i class='fas fa-pencil-alt'></i></button></td>";
            echo "</tr>";*/
            echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['USER_ID']."</td>";
                echo "<td>".$d['USER_NAME']."</td>";
                echo "<td>".$d['USER_ICNO']."</td>";
                echo "<td>".getDepartmentTitle($dbh3, $d['JA_KOD_JABATAN'])."</td>";
                echo "<td>".getDepartmentTitle($dbh3, $d['USER_DEPTCAMPUS'])."</td>";
                echo "<td>".getDepartmentTitle($dbh3, $d['USER_DEPARTMENT'])."</td>";
                 echo "<td>".$d['USER_SIRSACCESS']."</td>";
                echo "<td><button type='button' name='view' class='btn btn-info' title='View' onclick='window.location=\"viewptft.php?id=".$d['USER_ID']."\";'><i class='fa fa-eye'></i></button> <button type='button' name='edit' class='btn btn-info' title='Edit' onclick='window.location=\"editptft.php?id=".$d['USER_ID']."\";'><i class='fas fa-pencil-alt'></i></button></td>";
            echo "</tr>";
            $count++;
        }
    }
}

function checkuserrecord($dbh, $id)
{
    $found = false;
    $data = ["id" => $id];
    $sql = "SELECT * FROM classbook_backup_jengka.vw_staff_phg u JOIN myra.user_role a ON u.USER_ID = a.USER_ID WHERE a.userrole_no = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $found = true;    
    }
    
    return $found;
}

function checklecturerrecord($dbh, $id)
{
    $found = false;
    $data = ["id" => $id];
    $sql = "SELECT * FROM classbook_backup_jengka.vw_staff_phg u  WHERE u.USER_ID = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $found = true;    
    }
    
    return $found;
}

function getstaffno($dbh, $userrole_no)
{
    $data = ["role_no" => $userrole_no];
    $sql = "SELECT * FROM myra.user_role WHERE role_no = :role_no";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['USER_ID'];
    }   
}

function getstaffname($dbh3, $USER_ID)
{
    $data = ["USER_ID" => $USER_ID];
    $sql = "SELECT * FROM classbook_backup_jengka.vw_staff_phg WHERE USER_ID = :USER_ID";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['USER_NAME'];
    }
}

function getstafffacultyid($dbh3, $USER_ID)
{
    $data = ["USER_ID" => $USER_ID];
    $sql = "SELECT USER_DEPTCAMPUS FROM classbook_backup_jengka.vw_staff_phg WHERE USER_ID = :USER_ID";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['USER_DEPTCAMPUS'];
    }
}

function getstafffaculty($dbh3, $USER_ID)
{
    $data = ["USER_ID" => $USER_ID];
    $sql = "SELECT JABATAN FROM classbook_backup_jengka.vw_staff_phg WHERE USER_ID = :USER_ID";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['JABATAN'];
    }
}

function getrole_name($dbh, $userrole_no)
{
    $data = ["role_no" => $userrole_no]; 
    $sql = "SELECT * FROM myra.user_role a /* JOIN myra.roles r ON a.role_no = r.role_no */ WHERE a.role_no = :role_no";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['role_name'];
    }
}

function getAccessIsAllowed($dbh, $userrole_no)
{
    $data = ["role_no" => $userrole_no]; 
    $sql = "SELECT * FROM myra.user_role a WHERE a.role_no = :role_no";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['access_no'];
    }
}

function saveuser($dbh, $staffno, $role_no, $isallowed)
{
    $created_at = getTimestamp();
    $token = generateToken(32);
    $data = ["staffno" => $staffno, "role_no" => $role_no, "isallowed" => $isallowed, "token" => $token, "created_at" => $created_at];
    $sql = "INSERT INTO myra.user_role (USER_ID, role_no, allowedaccess, accesstoken, created_at) VALUES (:staffno, :role_no, :isallowed, :token, :created_at)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
}

function getrolelist($dbh)
{
    $sql = "SELECT * FROM myra.roles ORDER BY role_name ASC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<option value='".$d['role_no']."'>".$d['role_name']."</option>";
        }
    }
}

function getSelectedRolesList($dbh, $role_no)
{
    $sql = "SELECT * FROM myra.roles ORDER BY role_name ASC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            if($d['role_no'] == $role_no)
            {
                echo "<option value='".$d['role_no']."' selected>".$d['role_name']."</option>";
            }
            else
            {
                echo "<option value='".$d['role_no']."'>".$d['role_name']."</option>";
            }          
        }
    }
}

function getrole_no($dbh, $userrole_no)
{
    $data = ["userrole_no" => $userrole_no]; 
    $sql = "SELECT * FROM myra.user_role a WHERE a.userrole_no = :userrole_no";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['role_no'];
    }
}

function updateuser($dbh, $userrole_no, $role_no, $isallowed)
{
    $updated_at = getTimestamp();
    $data = ["userrole_no" => $userrole_no, "role_no" => $role_no, 
            "isallowed" => $isallowed, "updated_at" => $updated_at];
    $sql = "UPDATE myra.user_role SET role_no = :role_no, allowedaccess = :isallowed, updated_at = :updated_at
            WHERE userrole_no = :userrole_no";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
}

function checkptftrecord($dbh2, $id)
{
    $found = false;
    $data = ["id" => $id];
    $sql = "SELECT * FROM classbook_backup_jengka.user WHERE BINARY USER_ID = :id";
    $stmt = $dbh2->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $found = true;    
    }
    
    return $found;
}

//will be removed
function getptftid($dbh2, $id)
{
    $data = ["id" => $id];
    $sql = "SELECT * FROM dbptftphg.ptfts WHERE BINARY ptfttoken = :id";
    $stmt = $dbh2->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['PTFT_ID'];
    }
}

//will be removed
function getptfttoken($dbh2, $id)
{
    $data = ["id" => $id];
    $sql = "SELECT * FROM dbptftphg.ptfts WHERE BINARY PTFT_ID = :id";
    $stmt = $dbh2->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['ptfttoken'];
    }
}

function getptftname($dbh3, $id)
{
    $data = ["id" => $id];
    $sql = "SELECT * FROM classbook_backup_jengka.vw_staff_phg WHERE BINARY USER_ID = :id";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['USER_NAME'];
    }
}

function getptfticnum($dbh3, $id)
{
    $data = ["id" => $id];
    $sql = "SELECT * FROM classbook_backup_jengka.vw_staff_phg WHERE BINARY USER_ID = :id";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['USER_ICNO'];
    }
}

function getptftfaculty($dbh3, $id)
{
    $data = ["id" => $id];
    $sql = "SELECT * FROM classbook_backup_jengka.vw_staff_phg WHERE BINARY USER_ID = :id";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['USER_DEPTCAMPUS'];
    }
}

function getptftuitmemail($dbh3, $id)
{
    $data = ["id" => $id];
    $sql = "SELECT * FROM classbook_backup_jengka.vw_staff_phg WHERE BINARY USER_ID = :id";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['USER_EMAIL'];
    }
}

//will be removed
/*function getptftaltemail($dbh2, $id)
{
    $data = ["id" => $id];
    $sql = "SELECT * FROM dbptftphg.ptfts WHERE BINARY ptfttoken = :id";
    $stmt = $dbh2->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['emailaddressalternate'];
    }
}

function getptftcontactnum($dbh2, $id)
{
    $data = ["id" => $id];
    $sql = "SELECT * FROM classbook_backup_jengka.user WHERE BINARY USER_ID = :id";
    $stmt = $dbh2->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['USER_MOBNO'];
    }
}*/

function getPtftIsActive($dbh2, $id)
{
    $data = ["id" => $id]; 
    $sql = "SELECT * FROM classbook_backup_jengka.user WHERE BINARY USER_ID = :id";
    $stmt = $dbh2->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['USER_SIRSACCESS'];
    }
}

function updateptft($dbh3, $ptftid, $ptftfaculty, $isallowed)
{
    $updated_at = getTimestamp();
    $data = ["ptftid" => $ptftid, "faculty" => $ptftfaculty, 
             "isallowed" => $isallowed, "isallowed" => $isallowed, "updated_at" => $updated_at];
    $sql = "UPDATE classbook_backup_jengka.user SET 
            USER_DEPTCAMPUS = :faculty,
            USER_SIRSACCESS = :isallowed, 
            USER_DATEUPDATE = :updated_at
            WHERE BINARY USER_ID = :ptftid";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
}

function getDepartmentTitle($dbh3, $jakodjab)
{
    $data = ["jakodjab" => $jakodjab]; 
    $sql = "SELECT * FROM classbook_backup_jengka.jabatan j WHERE BINARY j.JA_KOD_JAB = :jakodjab";
    $stmt = $dbh3->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['JABATAN'];
    }
}
/////end: system administrator functions/////

/////start: dashboard info except for hea/////
function displayReportsOnDashboard($dbh, $USER_ID)
{
    $data = ["USER_ID" => $USER_ID];
    $sql = "SELECT r.reporttoken, s.SEMESTER_ID, s.SEMESTER_NAME, c.code_course, c.name_course_eng, r.reportgroup, m.monthtitle, r.reportyear, r.reporthea, r.created_at 
            FROM myra.reports r
            JOIN myra.months m ON r.reportmonth = m.monthid
            JOIN classbook_backup_jengka.semester s ON r.SEMESTER_ID = s.SEMESTER_ID
            JOIN classbook_backup_jengka.vw_active_courses c ON r.reportcourse = c.course_id
            WHERE s.SEMESTER_ACTIVE = 1 AND r.USER_ID = :USER_ID
            ORDER BY m.monthid DESC, r.reportyear DESC, c.code_course ASC, r.reportgroup ASC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $count = 0;
        while($d = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>";
                echo "<td>".($count + 1)."</td>";
                echo "<td>".$d['SEMESTER_ID']." - ".$d['SEMESTER_NAME']."</td>";
                echo "<td>".$d['code_course']." - ".$d['name_course_eng']."</td>";
                echo "<td>".$d['reportgroup']."</td>";
                echo "<td>".$d['monthtitle']." ".$d['reportyear']."</td>";
                echo "<td align='center'>";
                    if($d['reporthea'] == 1)
                    {
                        echo "<i class='fas fa-bullhorn'></i>";
                    }
                echo "</td>";
                echo "<td>".$d['created_at']."</td>";
                echo "<td><button type='button' name='view' class='btn btn-info' title='View' onclick='window.location=\"../myreports/viewmyreport.php?id=".$d['reporttoken']."\";'><i class='fa fa-eye'></i></button></td>";
            echo "</tr>";
            $count++;
        }
    }
}

function getTotalReport($dbh, $USER_ID)
{
    $data = ["USER_ID" => $USER_ID];
    $sql = "SELECT COUNT(*) AS TOTAL FROM myra.reports WHERE USER_ID = :USER_ID";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['TOTAL'];
    }
}

function getTotalReportThisSemester($dbh, $USER_ID)
{
    $data = ["USER_ID" => $USER_ID];
    $sql = "SELECT COUNT(*) AS TOTAL 
            FROM myra.reports r 
            JOIN classbook_backup_jengka.semester s ON r.SEMESTER_ID = s.SEMESTER_ID
            WHERE r.USER_ID = :USER_ID and s.SEMESTER_ACTIVE = 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $rowCount = $stmt->rowCount();
    if($rowCount > 0)
    {
        $d = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $d['TOTAL'];
    }
}
/////end: dashboard info except for hea/////
?>
