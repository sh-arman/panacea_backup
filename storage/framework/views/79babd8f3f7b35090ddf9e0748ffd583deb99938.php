<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots">
    <title>Ooops!</title>
<body>
<h3>
    Hi, something went wrong. Could you try doing what you were doing once again. <br>If that didn't work, please write
    to us about the problem to <a href="mailto:hello@panacea.live" style="color: #FFFFFF">hello@panacea.live</a>
</h3>
<?php if(strpos(Request::url(), 'codes') or strpos(Request::url(), 'panalytics')): ?>
    <br>
    <br>
    <h3 style="font-size: medium">You may also call:</h3>
    <h3 style="font-size: medium">Abdullah MD Taqi Uddeen, Sales & Marketing Head: 880 1648 141 011 <br>
        Soumik Aswad, CEO: 880 1676 291 391</h3>
    <br>
<?php endif; ?>
<br>
<br>
<h3>
</h3>

</body>
<style type="text/css">
    @import  url('https://fonts.googleapis.com/css?family=Magra');

    *, *::after, *::before {
        box-sizing: border-box;
    }

    html {

        background: #000;
        font-family: "Magra", Arial, "Helvetica Neue", Helvetica, sans-serif;
    }

    head {
        display: block;
        position: relative;
        width: 200px;
        margin: 10% auto 0;
        -webkit-animation: shvr 0.2s infinite;
        animation: shvr 0.2s infinite;
    }

    head::after {
        content: '';
        width: 20px;
        height: 20px;
        background: #000;
        position: absolute;
        top: 30px;
        left: 25px;
        border-radius: 50%;
        box-shadow: 125px 0 0 #000;
        -webkit-animation: eye 2.5s infinite;
        animation: eye 2.5s infinite;
    }

    meta {
        position: relative;
        display: inline-block;
        background: #fff;
        width: 75px;
        height: 80px;
        border-radius: 50% 50% 50% 50%/45px 45px 45% 45%;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    meta::after {
        content: '';
        position: absolute;
        border-bottom: 2px solid #fff;
        width: 70px;
        height: 50px;
        left: 0px;
        bottom: -10px;
        border-radius: 50%;
    }

    meta::before {
        bottom: auto;
        top: -100px;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
        left: 0;
    }

    meta + meta {
        float: right;
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg);
    }

    meta + meta::after {
        left: 5px;
    }

    body {
        margin-top: 100px;
        text-align: center;
        color: #fff;
    }

    @-webkit-keyframes eye {
        0%, 30%, 55%, 90%, 100% {
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
        }
        10%, 25% {
            -webkit-transform: translate(0, 20px);
            transform: translate(0, 20px);
        }
        65% {
            -webkit-transform: translate(-20px, 0);
            transform: translate(-20px, 0);
        }
        80% {
            -webkit-transform: translate(20px, 0);
            transform: translate(20px, 0);
        }
    }

    @keyframes  eye {
        0%, 30%, 55%, 90%, 100% {
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
        }
        10%, 25% {
            -webkit-transform: translate(0, 20px);
            transform: translate(0, 20px);
        }
        65% {
            -webkit-transform: translate(-20px, 0);
            transform: translate(-20px, 0);
        }
        80% {
            -webkit-transform: translate(20px, 0);
            transform: translate(20px, 0);
        }
    }

    @-webkit-keyframes shvr {
        0% {
            -webkit-transform: translate(1px, 1em);
            transform: translate(1px, 1em);
        }
        50% {
            -webkit-transform: translate(0, 1em);
            transform: translate(0, 1em);
        }
        100% {
            -webkit-transform: translate(-1px, 1em);
            transform: translate(-1px, 1em);
        }
    }

    @keyframes  shvr {
        0% {
            -webkit-transform: translate(1px, 1em);
            transform: translate(1px, 1em);
        }
        50% {
            -webkit-transform: translate(0, 1em);
            transform: translate(0, 1em);
        }
        100% {
            -webkit-transform: translate(-1px, 1em);
            transform: translate(-1px, 1em);
        }
    }

    @-webkit-keyframes text-show {
        to {
            text-indent: -373px;
        }
    }

    @keyframes  text-show {
        to {
            text-indent: -373px;
        }
    }


</style>

</head>

<body>


</body>
</html>
