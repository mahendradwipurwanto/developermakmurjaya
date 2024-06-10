<?php

include_once 'helpers/functions.php';

?>

<nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
    <div class="container">
        <ul class="navbar-nav mx-auto mt-2 mt-lg-0 d-contents" id="navbar-example">
            <li class="nav-item">
                <a class="nav-link <?= active('/');?>" href="<?= base_url();?>">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= active('/press-release');?>" href="<?= base_url();?>press-release">Press Release</a>
            </li>
        </ul>
    </div>
</nav>