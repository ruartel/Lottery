<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <link href="lottery.css" rel="stylesheet" >
</head>
<body>
<div class="logoWp">
    <img src="logoLottery.gif" class="logoImg">
</div>
<h2 id="sponsor"></h2>
<h1 id="prizeName"></h1>
<div id="sm">
    <div class="group lever">
        <button>Start</button>
    </div>
    <div class="group">
        <div class="reel"></div>
        <div class="reel"></div>
        <div class="reel"></div>
        <div class="reel"></div>
        <div class="reel"></div>
    </div>
    <p class="msg">Press Start</p>
</div>
<div id="winners"></div>
<div class="bottom dFlex">
    <div class="dFlex margimL20">
        <input type="text" id="lotNum" class="small-input" value="1">
        <span>מספר הפרס</span>
    </div>
    <div>
        <a href="javascript:void(0);" class="eraseLink" onclick="cleanWinners()">מחק מנצחים</a>
    </div>
</div>
</body>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="lottery.js"></script>
</html>