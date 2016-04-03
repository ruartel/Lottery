/**
 * Created by ruartel on 1/21/16.
 */
/*
 requestAnimationFrame polyfill
 */
(function(w){
    var lastTime = 0,
        vendors = ['webkit', /*'moz',*/ 'o', 'ms'];
    for (var i = 0; i < vendors.length && !w.requestAnimationFrame; ++i){
        w.requestAnimationFrame = w[vendors[i] + 'RequestAnimationFrame'];
        w.cancelAnimationFrame = w[vendors[i] + 'CancelAnimationFrame']
        || w[vendors[i] + 'CancelRequestAnimationFrame'];
    }

    if (!w.requestAnimationFrame)
        w.requestAnimationFrame = function(callback, element){
            var currTime = +new Date(),
                timeToCall = Math.max(0, 16 - (currTime - lastTime)),
                id = w.setTimeout(function(){ callback(currTime + timeToCall) }, timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };

    if (!w.cancelAnimationFrame)
        w.cancelAnimationFrame = function(id){
            clearTimeout(id);
        };

    getPrizeName();
    //check if we have winners saved
    getWinners();
})(this);

function getWinners(){
    prizeNum = $('#lotNum').val();
    $.ajax({
        url:'winners.php',
        success:function(resp){
            obj = jQuery.parseJSON(resp);
            $.each(obj, function(){
                //add to the side the name of the winner
                str = '';
                str += '<div class="winnerBox">' +
                '<div class="bold">' + this.prize_name + '</div>' +
                '<div>שם הזוכה: ' + this.w_name + '</div>' +
                '<div>טלפון: ' + this.phone + '</div>' +
                '</div>';
                $('#winners').append(str);
            })
        }
    });
}

function getPrizeName(){
    prizeNum = $('#lotNum').val();
    $.ajax({
        url:'prizeName.php?n=' + prizeNum,
        success:function(resp){
            obj = JSON.parse(resp);
            if(obj.sponsor == null){
                $('#sponsor').text('"הפרס בהדיבות: "הילום שם');
            }else{
                txt = '"הפרס בהדיבות: "' + obj.sponsor;
                $('#sponsor').text(txt);
            }
            $('#prizeName').text(obj.name);
        }
    });
}
/*
 Slot Machine
 */
var sm = (function(undefined){

    var tMax = 3000, // animation time, ms
        height = 700,
        speeds = [],
        r = [],
        reels = [
            ['0','1','2','3','4','5','6','7','8','9'],
            ['0','1','2','3','4','5','6','7','8','9'],
            ['0','1','2','3','4','5','6','7','8','9'],
            ['0','1','2','3','4','5','6','7','8','9'],
            ['0','1','2','3','4','5','6','7','8','9']
        ],
        $reels, $msg,
        start;

    function init(){
        $reels = $('.reel').each(function(i, el){
            el.innerHTML = '<div><p>' + reels[i].join('</p><p>') + '</p></div><div><p>' + reels[i].join('</p><p>') + '</p></div>'
        });

        $msg = $('.msg');

        $('button').click(action);
    }
    var curNum;

    function action(){
        if (start !== undefined) return;

        rv = new Array();
        rv[0]=660;
        rv[1]=700;
        rv[2]=70;
        rv[3]=140;
        rv[4]=220;
        rv[5]=290;
        rv[6]=365;
        rv[7]=440;
        rv[8]=510;
        rv[9]=590;

        lotNum = $('#lotNum').val();

        $.ajax({
            url:'ajax.php?n=' + lotNum,
            success:function(num){
                for (var i = 0; i < 5; ++i) {
                    speeds[i] = Math.random() + .5;
                    //    r[i] = (Math.random() * 10 | 0) * height / 10;
                }
                curNum = num;
                var number = num,
                    output = [],
                    sNumber = number.toString();

                for (var i = 0, len = sNumber.length - 1; i < len; i += 1) {
                    output.push(+sNumber.charAt(i));
                }

                if(output.length < 5){
                    for(var i = 0; i < 5 - output.length; i++){
                        r[i]=rv[0];
                    }
                    for(ii = 0; ii < output.length; ii++){
                        r[i]=rv[output[ii]];
                        i++;
                    }
                }else{
                    for(var i = 0; i < 5; i++){
                        r[i]=rv[output[i]];
                    }
                }

                //r[0]=70;//2
                //r[1]=140;//3
                //r[2]=365;//6
                //r[3]=700;//1
                //r[4]=660;//0
                //r[4]=290;//5
                //r[4]=220;//4
                //r[4]=440;//7
                //r[4]=510;//8
                //r[4]=590;//9

            }
        });

        $msg.html('Spinning...');
        animate();
    }

    function animate(now){
        if (!start) start = now;
        var t = now - start || 0;

        for (var i = 0; i < 5; ++i)
            $reels[i].scrollTop = (speeds[i] / tMax / 2 * (tMax - t) * (tMax - t) + r[i]) % height | 0;

        if (t < tMax)
            requestAnimationFrame(animate);
        else {
            start = undefined;
            check();
        }
    }

    function check(){
        lotNum = $('#lotNum').val();
        $.ajax({
            url:'ajax2.php?n=' + lotNum + '&s=' + curNum,
            success:function(resp){
                obj = JSON.parse(resp);
                $msg.html(
                    'שם הזוכה: ' + obj[0].name
                    + '<br />' + 'טלפון: ' + obj[0].phone
                    + '<br />' + 'כתובת: ' + obj[0].address
                );
                //add to the side the name of the winner
                str = winnerLine(obj);
                $('#winners').append(str);

                //update number and text for next lottery
                ln = parseInt($('#lotNum').val());
                ln++;
                $('#lotNum').val(ln);
                getPrizeName();
            }
        });
    }

    return {init: init}

})();

function winnerLine(obj){
    str = '';
    str += '<div class="winnerBox">' +
    '<div class="bold">' + $('#prizeName').text() + '</div>' +
    '<div>שם הזוכה: ' + obj[0].name + '</div>' +
    '<div>טלפון: ' + obj[0].phone + '</div>' +
    '</div>';

    return str;
}

function cleanWinners(){
    var r = confirm("האם אתה בטוח? לא ניתן לשיחזור.");
    if (r == true) {
        $.ajax({
            url:'deleteWinners.php',
            success:function(resp){
                $('#winners').html('');
            }
        });
    } else {
    }
}

$(sm.init);