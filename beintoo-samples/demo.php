<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'variables.php';

    require '../beintoo-php-sdk/beintoo_client.php';
    // this is the id on the app side
    //$current_user_id='88531467-fbf1-4da8-aa22-a3cd5bb9a4a7:3543304037647835';
    $guid='88531467-fbf1-4da8-aa22-a3cd5bb9a4a7:3543304037647835';

     if (strcmp($_GET['action'],"logout")==0) {
    unset($_SESSION['userExt']);
    unset($guid);

    }

    if (isset($_GET['apikey']))
         $apikey=$_GET['apikey'];
    if (isset($_GET['guid']))
         $guid=$_GET['guid'];

    $sandbox = false;
if (isset($_GET['sandbox']))
    $sandbox = true;

$client = new BeintooRestClient($apikey, $sandbox);

$action = "player_login";




if (isset($_GET['userExt'])) {
    $response = $client->player_login(null, $_GET['userExt']);
    if (isset($response->user))
        $_SESSION['userExt'] = $response->user;
}

if (isset($guid) && !isset($_GET['userExt'])) {
    $response = $client->player_getplayer_byguid($guid);
    if (isset($response->user)) {
        $_SESSION['userExt'] = $response->user;
    }
    if (!isset($response->user) && $_GET['guid']!=null) {
        unset($_SESSION['userExt']);
    }
}


if (isset($_SESSION['userExt']))
    $userExt = $_SESSION['userExt']->id;




 //$userExt = $_GET['userExt'];



if (isset($_GET['action']))
    $action = $_GET['action'];


/// ALL RESPONSES


    if (strcmp($action,"vgood_getvood_byguid")==0) {
          $response=$client->vgood_getvood_byguid($_GET['codeID'],$guid,
                  $_GET['latitude'],$_GET['longitude'],$_GET['radius'],$_REQUEST['REMOTE_ADDR']);

    }
    if (strcmp($action,"vgood_getvood_byuser")==0) {
          $response=$client->vgood_getvood_byuser($_GET['codeID'],$userExt,
                  $_GET['latitude'],$_GET['longitude'],$_GET['radius'],$_REQUEST['REMOTE_ADDR']);

    }

    if (strcmp($action,"beta_checkin_places")==0) {
          $response=$client->beta_checkin_places($userExt,
                  $_GET['latitude'],$_GET['longitude'],$_GET['radius'],true);

    }

    if (strcmp($action,"player_getplayer_byguid")==0) {
        $response=$client->player_getplayer_byguid($guid);

    }
    if (strcmp($action,"player_submitscore")==0) {
        $response=$client->player_submitscore($_GET['codeID'],$guid,
               $_GET['lastScore'],$_GET['balance'],
                  $_GET['latitude'],$_GET['longitude'],$_GET['radius']);

    }
    if (strcmp($action,"player_login")==0) {
        if (isset($userExt)) {
            unset($guid);
        }
        
        $response=$client->player_login($guid,$userExt);

    }

    if (strcmp($action,"app_topscore")==0) {
        $response=$client->app_topscore($guid,$_GET['rows']);

    }
    if (strcmp($action,"user_setuser")==0) {
        $response=$client->user_setuser($_GET['guid'], $_GET['email'],
                $_GET['address'], $_GET['country'], $_GET['gender'], $_GET['nickname'], $_GET['name'],
                 $_GET['password'], $_GET['sendGreetingsEmail'], $_GET['imageURL']
                );

    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>phpSDK DEMO </title>
   <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAlW1NVMthfyCA6IHAwPF0BBQ2DYW8yFv6V-5ywl80faskaNRB-hRrcA_y-0pnC4RS0VcSH1Mthb_0mA"
            type="text/javascript">
    </script>
    <script type="text/javascript">

    function MyApplication() {
      this.counter = 0;
      this.map = new GMap2(document.getElementById("map_canvas"));
      this.map.setCenter(new GLatLng(40.7219, -73.93), 5);
      var myEventListener = GEvent.bind(this.map, "click", this, function(overlay, latlng) {

           if (latlng) {
             //this.map.addOverlay(new GMarker(latlng));
            // this.counter++;

             var locDiv = document.getElementById("message");
             //locDiv.innerHTML = "cooridnates lng: "+latlng.lng()+"lat"+latlng.lat();
             var locinputlat = document.getElementById("l1");
             var locinputlong = document.getElementById("l2");
             locinputlong.value=latlng.lng();
             locinputlat.value=latlng.lat();
              locinputlat = document.getElementById("lu1");
              locinputlong = document.getElementById("lu2");
             locinputlong.value=latlng.lng();
             locinputlat.value=latlng.lat();
              locinputlat = document.getElementById("lcp1");
              locinputlong = document.getElementById("lcp2");
             locinputlong.value=latlng.lng();
             locinputlat.value=latlng.lat();

           } //else if (overlay instanceof GMarker) {
             // This code is never executed as the event listener is
             // removed the second time this event is triggered
           //  this.removeOverlay(marker)
           //}

         //  GEvent.removeListener(myEventListener);

      });
    }

    function initialize() {
      var application = new MyApplication();
    }

    </script>
  </head>

  <body   onload="initialize()" >

      <H1> PHP DEMO</H1>

      <?php
      echo " <hr/>";

        if (!isset( $_SESSION['userExt'])) {
        // if response of player_getplayer_byguid contains $response->user it is connected

        echo " Hi player (".$guid.") you are not logged in beintoo.";
        echo "<a href='".$client->getConnectUrl( $guid,$redirect_uri,NULL,NULL,$logged_uri)."' >CONNECT</a>";
        } else {
            echo "Hi user, you are connected with Beintoo with :<br/>";
            echo "<table><tr>";
            echo "<td>"."<img src='".$_SESSION['userExt']->usersmallimg."' /><br/>"."</td>";
            echo "<td><table><tr><td>".$_SESSION['userExt']->id."</td></tr><tr><td>".$_SESSION['userExt']->name."</td></tr><tr><td>".$_SESSION['userExt']->bedollars."</td></tr></table></td>";
            echo "</tr></table>";
           echo "<br/>";
        }
            if (strcmp($action,"vgood_getvood_byguid")==0 && isset($response->id)) {
                        echo "<hr/>";
                        echo"<h2>YOU WON THIS VGOOD</h2>";
                        $client->render_vgood($response,FALSE);
            }
               if (strcmp($action,"vgood_getvood_byuser")==0 && isset($response->id)) {
                        echo "<hr/>";
                        echo"<h2>YOU WON THIS VGOOD</h2>";
                        $client->render_vgood($response,FALSE);
            }
           if (strcmp($action,"app_topscore")==0 ) {
                        echo "<hr/>";
                      //$ar_1= $response['default'];
                      /*oreach ($ar_1 as $key => $value) {
                          echo $value->user->name." ".$value->playerScore->bestscore."<br/>";
                      }*/
            }
    if (strcmp($action,"player_login_exp")==0) {
        $client_exp=new BeintooRestClient("oqiaudfosdijfsvlfzxjvnc_ERROR_API_KEY",$sandbox);
        try {
        $response=$client_exp->player_login("123null","123null");
        } catch (BeintooApiException $e) {
            print("<br/>catching excep<br/>");
            var_export($e);
             print("<br/>=================<br/>");

        }
    }
        echo "<hr/>";
        if (isset($response)) {
            echo "RESPONSE TO $action :<br/>";
            echo "<pre>";
            var_export($response);
            echo "</pre>";

            }
      ?>
      <hr/>
      <div class="box"> <h3>clear all</h3><br />
    <form name="input" action="demo.php" method="get">
        <input type="submit" name="action" value="logout"  /><br /><br />
    </form>
      </div>

      <hr/>
      <div class="box"> <h3>player_submitscore</h3><br />
    <form name="input" action="demo.php" method="get">
        <br /><br />
        Sandbox: <input type="checkbox" name="sandbox" value="1" checked /><br /><br />
        Latitude: <input   type="text" name="latitude" /><br /><br />
        Longitude: <input   type="text" name="longitude" /><br /><br />
        Radius: <input    type="text" name="radius" value="1000" /><br /><br />
        lastScore: <input   type="text" name="lastScore" value="100" /><br /><br />
        balance: <input   type="text" name="balance"  /><br /><br />
        Player: <input type="text" name="guid" value="<?php echo $guid; ?>" /><br /><br />
        apikey: <input type="text" name="apikey" value="<?php echo $apikey; ?>" /><br /><br />
        codeID: <input type="text" name="codeID" value="" /><br /><br />
        <input type="submit" name="action" value="player_submitscore"  /><br /><br />
    </form>
      </div>
        <hr/>
              <div class="box"> <h3>vgood_getvood_byguid</h3><br /><br />

     vgood_getvood_byguid<br />
     <div id="map_canvas" style="width: 500px; height: 300px"></div>
    <div id="message"></div>

    <form name="input" action="demo.php" method="get">
        <br /><br />
        Sandbox: <input type="checkbox" name="sandbox" value="1" checked /><br /><br />
        Latitude: <input id="l1" type="text" name="latitude" /><br /><br />
        Longitude: <input id="l2"  type="text" name="longitude" /><br /><br />
        Radius: <input id="l3"  type="text" name="radius" value="1000" /><br /><br />
        Player: <input type="text" name="guid" value="<?php echo $guid; ?>" /><br /><br />
        apikey: <input type="text" name="apikey" value="<?php echo $apikey; ?>" /><br /><br />
        <input type="submit" name="action" value="vgood_getvood_byguid"  /><br /><br />
    </form>
     </div>
        <hr/>
    <div class="box"> <h3>vgood_getvood_byuser</h3><br /><br />

     vgood_getvood_byuser<br />
  
    <form name="input" action="demo.php" method="get">
        <br /><br />
        Sandbox: <input type="checkbox" name="sandbox" value="1" checked /><br /><br />
        Latitude: <input id="lu1" type="text" name="latitude" /><br /><br />
        Longitude: <input id="lu2"  type="text" name="longitude" /><br /><br />
        Radius: <input id="l3"  type="text" name="radius" value="1000" /><br /><br />
        apikey: <input type="text" name="apikey" value="<?php echo $apikey; ?>" /><br /><br />
        User: <input type="text" name="userExt" value="<?php if (isset($_SESSION['userExt'])) echo  $_SESSION['userExt']->id; ?>" /><br /><br />

        <input type="submit" name="action" value="vgood_getvood_byuser"  /><br /><br />
    </form>
     </div>
        <hr/>
            <div class="box"> <h3>beta_checkin_places</h3><br /><br />

     beta_checkin_places<br />

    <form name="input" action="demo.php" method="get">
        <br /><br />
        Sandbox: <input type="checkbox" name="sandbox" value="1" checked /><br /><br />
        Latitude: <input id="lcp1" type="text" name="latitude" /><br /><br />
        Longitude: <input id="lcp2"  type="text" name="longitude" /><br /><br />
        Radius: <input id="l3"  type="text" name="radius" value="1000" /><br /><br />
        apikey: <input type="text" name="apikey" value="<?php echo $apikey; ?>" /><br /><br />
        User: <input type="text" name="userExt" value="<?php if (isset($_SESSION['userExt'])) echo  $_SESSION['userExt']->id; ?>" /><br /><br />

        <input type="submit" name="action" value="beta_checkin_places"  /><br /><br />
    </form>
     </div>
        <hr/>
              <div class="box"> <h3>player_getplayer_byguid</h3><br /><br />

    <form name="input" action="demo.php" method="get">
        <br /><br />
        Sandbox: <input type="checkbox" name="sandbox" value="1" checked /><br /><br />
        Player: <input type="text" name="guid" value="<?php echo $guid; ?>" /><br /><br />
        apikey: <input type="text" name="apikey" value="<?php echo $apikey; ?>" /><br /><br />
        <input type="submit" name="action" value="player_getplayer_byguid"  /><br /><br />
    </form>
     </div>
        <hr/>
                      <div class="box"> <h3>app_topscore</h3><br /><br />

    <form name="input" action="demo.php" method="get">
        <br /><br />
        Sandbox: <input type="checkbox" name="sandbox" value="1" checked /><br /><br />
        codeID: <input type="text" name="codeID" value="<?php echo $codeID; ?>" /><br /><br />
        apikey: <input type="text" name="apikey" value="<?php echo $apikey; ?>" /><br /><br />
        rows: <input type="text" name="rows" value="20" /><br /><br />
        <input type="submit" name="action" value="app_topscore"  /><br /><br />
    </form>
     </div>
        <hr/>
              <div class="box"> <h3>player_login</h3><br /><br />

    <form name="input" action="demo.php" method="get">
        <br /><br />
        Sandbox: <input type="checkbox" name="sandbox" value="1" checked /><br /><br />
        Player: <input type="text" name="guid" value="<?php echo $guid; ?>" /><br /><br />
        apikey: <input type="text" name="apikey" value="<?php echo $apikey; ?>" /><br /><br />
              User: <input type="text" name="userExt" value="<?php if (isset($_SESSION['userExt'])) echo  $_SESSION['userExt']->id; ?>" /><br /><br />

        <input type="submit" name="action" value="player_login"  /><br /><br />
    </form>
              </div>
               <hr/>
              <div class="box"> <h3>user_setuser</h3><br /><br />

    <form name="input" action="demo.php" method="get">
        <br /><br />
        Sandbox: <input type="checkbox" name="sandbox" value="1" checked /><br /><br />
        apikey: <input type="text" name="apikey" value="<?php echo $apikey; ?>" /><br /><br />
        Player(guid): <input type="text" name="guid" value="<?php echo $guid; ?>" /><br /><br />
        email: <input   type="text" name="email"  /><br /><br />
        address: <input   type="text" name="address"  /><br /><br />
        country: <input   type="text" name="country"  /><br /><br />
        nickname: <input   type="text" name="nickname"  /><br /><br />
        name: <input   type="text" name="name"  /><br /><br />
        password: <input   type="text" name="password"  /><br /><br />
        imageURL: <input   type="text" name="imageURL"  /><br /><br />
        sendEmail: <input type="checkbox" name="sendGreetingsEmail" value="1" checked /><br /><br />
        gender: <input type="radio" name="gender" value="1" /> Male    <input type="radio" name="gender" value="2" checked /> Female <input type="radio" name="gender" value="" /> None<br /><br />

        <input type="submit" name="action" value="user_setuser"  /><br /><br />
    </form>
              </div>

                             <hr/>
    <div class="box"> <h3>generate an error</h3><br /><br />

    <form name="input" action="demo.php" method="get">
        <br /><br />
        Sandbox: <input type="checkbox" name="sandbox" value="1" checked /><br /><br />

        <input type="submit" name="action" value="player_login_exp"  /><br /><br />
    </form>
              </div>
  </body>
</html>
