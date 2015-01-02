<?php

$friendsArr = array();
$screenname1 = $_GET['twittername1'];
$screenname2 = $_GET['twittername2'];

$protected_screenname1 = check_is_protected($screenname1);
$protected_screenname2 = check_is_protected($screenname2);

if ($protected_screenname1 && $protected_screenname2) {
    print("<div class='error' data-ptd='1'>Both accounts are protected!<br> Protected accounts do not publicly share their follows.</div>");
} else if ($protected_screenname1) {
    print("<div class='error' data-ptd='2'>This user has a protected account!<br> Protected accounts do not publicly share their follows.</div>");
} else if ($protected_screenname2) {
    print("<div class='error' data-ptd='3'>This user has a protected account!<br> Protected accounts do not publicly share their follows.</div>");
} else {

    $person1 = get_friends($screenname1);
    $person2 = get_friends($screenname2);


//vergelijken van de 2 strings en de gelijke waarden in een nieuwe array plaatsen
    $result = array_intersect($person1, $person2);

//controle of de array met gemeenschappelijke follows leeg is.
    if (empty($result)) {
        print("<p>There are no common friends!</p>");
    } else {
        //we controleren of het aantal gelijke waarden meer is dan de requestlimiet toelaat.
        $result = split_into_requestLimit($result);

        $userList = array();

        foreach ($result as $requestList) {
            $requestList = implode(",", $requestList);
            array_push($userList, $requestList);
        }


        //Get data of twitter friends
        $infoList = get_userInfo($userList);
        $value = 0;

        foreach ($infoList as $users) {
            foreach ($users as $user) {

                $screenname = $user['screen_name'];
                $pic = $user['profile_image_url'];

                $friendsArr[$value] = generate_userlist($screenname, $pic);
                ++$value;
            }
        }

        showList($friendsArr);
    };
};

function check_is_protected($twittername) {
    //checking if the twitteraccount is protected
    $json = file_get_contents('https://api.twitter.com/1/users/lookup.json?screen_name=' . $twittername);
    $info = (array) json_decode($json, true);
    return ($info[0]['protected']);
}

function get_friends($twittername) {
    //Getting the friendIdList
    $json = file_get_contents('https://api.twitter.com/1/friends/ids.json?cursor=-1&screen_name=' . $twittername);
    //$json = file_get_contents('ids_' . $twittername . '.json');
    $friends = (array) json_decode($json);
    //we only need the id's
    $friends = ($friends['ids']);

    return $friends;
}

function get_picture($twittername) {
    $pic = "http://api.twitter.com/1/users/profile_image?screen_name=" . $twittername . "&size=bigger";

    return $pic;
}

function get_userInfo($list) {
    //looking up the twitter data for all the people in the idlist that's being passed on
    $arrList = array();

    foreach ($list as $userList) {

        $jsonUser = file_get_contents('https://api.twitter.com/1/users/lookup.json?user_id=' . $userList);
        $userArr = (array) json_decode($jsonUser, true);

        array_push($arrList, $userArr);
    }
    return $arrList;
}

function split_into_requestLimit($userList) {
// checkup to count the number of request that have to be made
// twitter has a limit of 100 users per request.

    $splittedList = array();

    $amount = count($userList);
    $parts = $amount / 100;

    if ($parts > 0) {
        $splittedList = array_chunk($userList, 100);
    } else {
        array_push($splittedList, $userList);
    }
    return $splittedList;
}

function generate_userlist($screenname, $pic) {
    return array($screenname, $pic);
}

function showList($friends) {
    $i = 0;

    foreach ($friends as $friend) {
        $html = '';

        if ($i === 0) {
            $html .= '<table><tbody><tr>';
        }
        '<a href="http://twitter.com/' . $friend[0] . '"/> </a>';
        $html .= '<td>' . '<a href="http://twitter.com/' . $friend[0] . '" target="_blank"> <img title="' . $friend[0] . '" src="' . $friend[1] . '"/></a>' . '</td>';

        if (($i + 1) % 6 === 0 && $i > 0) {
            $html .= '</tr><tr>';
        }

        if ($i === count($friends) - 1) {
            $html .= '</tr></tbody></table>';
        }

        print($html);

        ++$i;
    }
}

?>
