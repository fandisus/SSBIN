<?php
include DIR."/php/view/tulang.php";

function htmlHead() { ?>
<style>
  .preface { margin: 0 auto; max-width: 500px; }
  .preface p {
    text-indent: 50px; text-align: justify; text-justify: inter-word;
  }
  .preface p:first-child {
    text-indent: 0;
  }
  .preface p:first-child:first-letter {
    float: left; color: #903;
    font-size: 75px; line-height: 60px;
    padding-top: 4px; padding-right: 8px; padding-left: 3px;
    font-family: Georgia;
  }

</style>
<?php }

function mainContent() { global $login; ?>
<div class="jumbotron">
    <h1>Welcome to</h1>
    <h2><strong>South Sumatera Biodiversity Information Network (SSBIN)</strong>.</h2>
    <?php if (!isset($login)) { ?>
    <p>You have not been logged in.</p>
    <a href="/login" class="btn btn-default">Login</a>
    <a href="/register" class="btn btn-success">Register</a>
    <?php } ?>
</div>
<?php }
