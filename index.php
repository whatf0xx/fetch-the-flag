<?php
function getFlag($db) {
  $stmt = $db->prepare("SELECT * FROM flag");
  if (!$stmt) {
    die('An error occured');
  }

  if (!$stmt->execute()) {
    die('An error occured in query');
  }

  $results = $stmt->fetchAll();
  return $results[0]['flag'];
}

function searchUserByName($username, $db) {
  $stmt = $db->prepare("SELECT * FROM users WHERE username=:user");
  $params = ['user' => $username];
  if (!$stmt) {
    die('An error occured');
  }

  if (!$stmt->execute($params)) {
    die('An error occured in query');
  }

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function validateCredentails($db) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  if ($username == '' || $password == '') {
    return 'No credalos provided';
  }

  $results = searchUserByName($username, $db);
  if (count($results) == 0) {
    return 'Juggalo not found!';
  }

  if (count($results) != 1) {
    return 'Oops, Something bad happend!';
  }

  if ($results[0]['password'] != substr(md5($username . $password), 0, 20)) {
    return 'Invalid credalo';
  }

  return getFlag($db);
}

$error = '';
if (isset($_POST['username']) && isset($_POST['password'])) {
  $db = new PDO('sqlite:/var/www/juggalo.db');
  $error = validateCredentails($db);
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Juggalo Central</title>
  <link rel="stylesheet" href="bootstrap.min.css">
  <link rel="stylesheet" href="main.css">
</head>
<!-- Coded with love by Mutiullah Samim - https://bootsnipp.com/snippets/3522X -->

<body>
  <div class="container h-100">
    <div class="d-flex justify-content-center h-100">
      <h2 class="rainbow" style="font-size: 4.2rem; position: absolute; top: 2.5%">
        Juggalo Central
      </h2>
      <div class="user_card">
        <div class="d-flex justify-content-center">
          <div class="brand_logo_container">
            <!-- https://codepen.io/ddietle/pen/ZpbzVw from David Dietle -->
            <div id="circus">
              <div id="poof"></div>
              <div id="hat">
                <div id="left"></div>
                <div id="right"></div>
                <div class="spot"></div>
                <div class="spot"></div>
                <div class="spot"></div>
              </div>
              <div id="hair">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
              </div>
              <div id="face">
                <div class="eye">
                  <span></span>
                  <div>
                    <div>*</div>
                  </div>
                </div>
                <div class="eye">
                  <span></span>
                  <div>
                    <div>*</div>
                  </div>
                </div>
                <div id="nose"></div>
                <div id="mouth">
                  <div></div>
                  <div></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-center form_container">
          <form method="post" action="/">
            <p class="rainbow" style="font-size: 16px !important; text-shadow: none;text-align: center;"><?php echo $error; ?></p>
            <div class="input-group mb-3">
              <input type="text" name="username" class="form-control input_user" value="" placeholder="username">
            </div>
            <div class="input-group mb-2">
              <input type="password" name="password" class="form-control input_pass" value="" placeholder="password">
            </div>
            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="customControlInline">
                <label class="custom-control-label" for="customControlInline">Remember me</label>
              </div>
            </div>
            <div class="d-flex justify-content-center mt-3 login_container">
              <button type="submit" name="button" class="btn login_btn">Login</button>
            </div>
          </form>
        </div>

        <div class="mt-4">
          <div class="d-flex justify-content-center links">
            Don't have an account? Too bad.
          </div>
          <div class="d-flex justify-content-center links">
            <a href="#">Forgot your password?</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>