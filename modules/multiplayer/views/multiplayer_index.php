<head>
    <style type="text/css">
        #board{
            border:1px solid black;
        }
    </style>
    
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            //posisi semua player
            var POSITION;
            var WIDTH = 500;
            var HEIGHT = 300;
            //dapatkan DOM dari sang canvas
            var CANVAS = document.getElementById('board');
            //dapatkan context 
            var CONTEXT = CANVAS.getContext('2d');
            
            $("#button_change_color").click(function(){
                var r = $("#r").val();
                var g = $("#g").val(); 
                var b = $("#b").val();
                changeColor(r,g,b);
            });
            
            
            // event penekanan tombol, kirim request ke server
            $(document).keydown(function(event){
                var deltaX = 0;
                var deltaY = 0;
                switch(event.keyCode){
                    case 37 : //kiri
                        deltaX--;
                        break;
                    case 38 : //atas
                        deltaY--;
                        break;
                    case 39 : //kanan
                        deltaX++;
                        break;
                    case 40 : //bawah
                        deltaY++;
                        break;
                }
                movePosition(deltaX, deltaY);
            });
            
            //timer, fetch posisi semua player dari server
            setInterval(drawCanvas,100);
            
            function drawCanvas(){
                $.ajax({
                    'url' : 'multiplayer/get_position',
                    'dataType' : 'json',
                    'success' : function(response){
                        
                        if(POSITION != response){
                            POSITION = response;
                            
                            CONTEXT.clearRect(0, 0, WIDTH, HEIGHT);
                            CONTEXT.save();
                            
                            for(var i = 0; i<POSITION.length; i++){
                            
                                position = POSITION[i];
                                

                                // style                           
                                CONTEXT.fillStyle = 'rgb('+position['r']+','+position['g']+','+position['b']+')';
                                CONTEXT.strokeStyle = '#000';
                                CONTEXT.lineWidth = 2;

                                // begin path
                                CONTEXT.beginPath();

                                // changing necessary points to simulate 3d rotating
                                CONTEXT.moveTo( position['x']*5, position['y']*5);
                                CONTEXT.lineTo( position['x']*5+5, position['y']*5);
                                CONTEXT.lineTo( position['x']*5+5, position['y']*5+5);
                                CONTEXT.lineTo( position['x']*5, position['y']*5+5);
                                CONTEXT.lineTo( position['x']*5, position['y']*5);

                                // add shadow to object
                                CONTEXT.shadowOffsetX = 5;
                                CONTEXT.shadowOffsetY = 5;
                                CONTEXT.shadowBlur    = 4;
                                CONTEXT.shadowColor   = 'rgba(180, 180, 180, 0.8)';

                                // fill shape, draw stroke
                                CONTEXT.fill();
                                CONTEXT.stroke();
                                CONTEXT.closePath();
                            }

                            CONTEXT.restore();
                        }
                        
                    }
                })
            }
            
            function movePosition(deltaX, deltaY){
                $.ajax({
                    'url' : 'multiplayer/set_position',
                    'type' : 'POST',
                    'data' : {
                        'deltaX' : deltaX,
                        'deltaY' : deltaY
                    }
                });
            }
            
            function changeColor(r, g, b){
                $.ajax({
                    'url' : 'multiplayer/set_color',
                    'type' : 'POST',
                    'data' : {
                        'r' : r,
                        'g' : g,
                        'b' : b
                    }
                });
            }
            
        })        
    </script>        
</head>
<body>
    <canvas id="board" width="500px" height="300px"></canvas><br />
    R : <input id="r" type="text" value="255" />
    G : <input id="g" type="text" value="0" />
    B : <input id="b" type="text" value="0" />
    <input id="button_change_color" type="button" value="Change Color" />
</body>
