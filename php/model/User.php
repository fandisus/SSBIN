<?php
namespace SSBIN;
use Trust\Model;
use Trust\DB;

class User extends Model {
  static protected $table_name = "users";
  static protected $json_columns = ['biodata','data_info','login_info'];
  static public $lama_berlaku = 3; //masa berlaku kode aktivasi 3 hari
  //if want to override, beware of new static at Model::find and Model::all
  public function __construct($arrProps, $new=false) {
    if ($new) {
      $this->username = null;
      $this->password = null;
      $this->biodata = json_encode([
          "city"=>null,"state"=>null,
          "name"=>null, "phone"=>[],"email"=>null,
          "gender"=>null,"dob"=>null,'profile_pic'=>null
      ]);
      $this->category=null;
      $this->organization=null;
      $now = date("Y-m-d H:i:s");
      $this->login_info = json_encode([
          "join_date"=>$now,"last_login"=>$now,
          "activation_code"=>hash('haval192,5',time()), "code_expiry"=>(time()+self::$lama_berlaku*84600)
      ]);
      $this->active = 0;
    }
    parent::__construct($arrProps);
  }
  public function imageIcon() {
    if ($this->biodata->profile_pic != null) return "/images/userpic/icon".$this->biodata->profile_pic;
    if ($this->biodata->gender == "Male") return "/images/user-male.png";
    if ($this->biodata->gender == "Female") return "/images/user-female.png";
  }
  public function profilePic() {
    if ($this->biodata->profile_pic != null) return "/images/userpic/pict".$this->biodata->profile_pic;
    if ($this->biodata->gender == "Male") return "/images/user-male.png";
    if ($this->biodata->gender == "Female") return "/images/user-female.png";
  }
  public static function getOrganizations() {
    //TODO: Get organizations from database
  }
  public static function findByEmailOrUsername($input, $cols="*") {
    $sql = "SELECT $cols FROM \"".static::$table_name."\" WHERE biodata->>'email'=:input OR username=:input";
    $read = DB::get($sql, ['input'=>$input]);
    if (count($read)) return new static($read[0]);
    return null;
  }
  public static function findByActivationCode($kode, $cols="*") {
    $sql = "SELECT $cols FROM \"".static::$table_name."\" WHERE login_info->>'activation_code'=:kode";
    $read = DB::get($sql, ['kode'=>$kode]);
    if (count($read)) return new static($read[0]);
    return null;
  }
  public static function findByCookies($cols = "*") {
    if (!isset($_COOKIE['login'])) return null;
    $sql = "SELECT $cols FROM \"".static::$table_name."\" WHERE login_info->>'remember_token'=:token";
    $read = DB::get($sql, ['token'=>$_COOKIE['login']]);
    if (count($read)) {
      $p = new static($read[0]);
      if ($p->login_info->remember_expiry < time()) {
        //ini akan menyebabkan login_info tidak punya remember_token dan remember_expiry
        unset($p->login_info->remember_token, $p->login_info->remember_expiry);
        $p->save();
        return null;
      }
      return ($p);
    }
    return null;
  }
  public static function findByForgotToken($token, $cols="*") {
    $sql = "SELECT $cols FROM \"".static::$table_name."\" WHERE login_info->>'forgot_token'=:token";
    $read = DB::get($sql, ['token'=>$token]);
    if (count($read)) {
      $p = new static($read[0]);
      if ($p->login_info->forgot_expiry < time()) {
        unset ($p->login_info->forgot_token, $p->login_info->forgot_expiry);
        $p->save();
        return null;
      }
      return $p;
    }
    return null;
  }
  public function login($expiry = 0) { //simpan remember token di db bila minta remember
    $this->login_info->last_login = date('Y-m-d H:i:s');
    unset ($this->login_info->forgot_token, $this->login_info->forgot_expiry);
    if ($expiry > 0) {
      $rememberToken = hash('sha256',rand(0,PHP_INT_MAX));
      setcookie("login", $rememberToken, $expiry);
      $this->login_info->remember_token = $rememberToken;
      $this->login_info->remember_expiry = $expiry;
    } else {
      //ini akan menyebabkan login_info tidak punya remember_token dan remember_expiry
      unset ($this->login_info->remember_token, $this->login_info->remember_expiry);
    }
    $_ENV['USER'] = $this->username;
    $this->save();
    unset ($this->password);
    $_SESSION['login'] = $this;
  }
  public function sendActivationEmail() {
    $body = $this->buildActivationEmail();
    if (!\Trust\Mail::sendMail($this->biodata->email, "[".APPNAME."] Account Activation", $body)) return false;
    return true;
  }
  public function sendForgotEmail() {
    $this->login_info->forgot_token = hash('sha256',rand(0,PHP_INT_MAX)."basing");
    $this->login_info->forgot_expiry = time() + 84600;
    $this->save();
    $body = $this->buildForgotEmail();
    if (!\Trust\Mail::sendMail($this->biodata->email, "[".APPNAME."] Forgot Password", $body)) return false;
    return true;
  }
  
  public static function validateUsername($user) {
    $err = \Trust\Basic::validateUsername($user);
    if (count($err)) return $err;
    $p = self::findByEmailOrUsername($user, "id");
    if ($p != null) return ["Username not available"];
    return [];
  }
  
  private function buildActivationEmail() { ob_start(); ?><html><body>
    <style>
      body { background: #DDD; max-width: 500px; font-family: verdana; font-size: 0.8em;}
      .title-bar { padding:5px 10px;  }
      .black {color:#FFF; background:#000; }
      .round-top { border-radius: 5px 5px 0 0; }

      .content { padding: 10px; background: #FFF;}
      .center-block {
        display:block; margin: 10px auto; width: 100%; height: 35px; border-radius: 5px; text-align:center;
      }
      .center-block a {
        display:inline-block; text-decoration: none; background: #05B2D2; color: #FFF;
        line-height: 35px;
        width: 150px; height: 35px; border-radius: 5px;
      }
      .name-block {display:inline-block; background: #000; color: #FFF; width: 190px; height: 35px; line-height: 35px;}
      .flex {display:flex;}
      .flex-vcenter {align-items:center;}

      .footer { padding: 10px; background: #888; color: #FFF;}
      .round-bot { border-radius: 0 0 5px 5px;}
    </style>
    <div class="title-bar black round-top">
      Hi <?= $this->biodata->name ?>!<br />
      Welcome to <?= APPNAME ?>!<br />
    </div>
    <div class="content"><?php $link = DOMAIN."/activation?c=".$this->login_info->activation_code; ?>
      You still need to activate your account by clicking the link below:

      <div class="center-block">
        <a href="<?= $link ?>">Activate Account</a>
      </div>
      
      Or copy the following link to your browser url:<br />
      <?=$link ?><br /><br />
    </div>

    </body></html><?php return ob_get_clean();
  }
  
  private function buildForgotEmail() { ob_start();
  $link = DOMAIN."/forgot?k=".$this->login_info->forgot_token;
  ?><html><body>
      <p>Hi <?= $this->biodata->name ?>.</p>
      <p>Somebody had requested a password reset on your account at <?= APPNAME ?></p>
      <p>If it is truly You who asked for the password reset, click or copy the link below to reset your password:</p>
      <p><a href="<?= $link ?>"><?= $link ?></a></p>
      <p>Otherwise just ignore this email</p>
      <p>This link will only be valid for 24 hours and can only be used once.</p>
    </body></html><?php return ob_get_clean();
  }
}
