<head>
    <script type="text/javascript" src="../assets/jquery.js"></script>
    <script type="text/javascript">
        var _state = "";
        var _output = "";
        var training = "";
        var watch = true;
        var CANVAS;
        var CONTEXT;
        var WIDTH;
        var HEIGHT;
        
        $(document).ready(function(){ 
            
            //dapatkan DOM dari sang canvas
            CANVAS = document.getElementById('canvas');
            //dapatkan context 
            CONTEXT = CANVAS.getContext('2d');
            WIDTH = 500;
            HEIGHT = 500;
            
            
            $("#btnStop").click(function(){
                if($(this).val()=="Stop"){                    
                    $.ajax({
                        url : "neural_network/stop"
                    });
                    $(this).val("Start");
                }else{                    
                    $.ajax({
                        url : "neural_network/train",
                        success : function(response){
                            $("#train").html(response);
                            $("#btnStop").val("Start");
                        }
                    });
                    $(this).val("Stop");
                }
            });
            
            $("#btnWatch").click(function(){
                if($(this).val()=="Watch"){
                    watch = true;
                    $(this).val("Un-watch");                    
                }else{
                    watch = false;
                    $(this).val("Watch");
                }
            });
            
            $("#btnSet").click(function(){
                $.ajax({
                    url : "neural_network/set"
                });
            });
            
            setInterval(report,200);
        });
        
        function drawBox(x,y){
            var fillStyle = CONTEXT.fillStyle;
            var strokeStyle = CONTEXT.srokeStyle;
            var lineWidth = CONTEXT.lineWidth;
            
            CONTEXT.fillStyle = '#FF0000';
            //CONTEXT.strokeStyle = '#FF0000';            
            //CONTEXT.lineWidth = 2;

            // begin path
            CONTEXT.beginPath();

            // changing necessary points to simulate 3d rotating
            CONTEXT.moveTo(40+x+5,    y+5);
            CONTEXT.lineTo(40+x+5+10, y+5);
            CONTEXT.lineTo(40+x+5+10, y+5+10);
            CONTEXT.lineTo(40+x+5,    y+5+10);

            // fill shape, draw stroke
            //CONTEXT.stroke();
            CONTEXT.fill();
            CONTEXT.closePath();
            
            CONTEXT.fillStyle = fillStyle;
            CONTEXT.strokeStyle = strokeStyle;
            CONTEXT.lineWidth = lineWidth;            
        }
        
        function drawLine(x1,y1,x2,y2){
            var strokeStyle = CONTEXT.srokeStyle;
            var lineWidth = CONTEXT.lineWidth;
            
            CONTEXT.strokeStyle = '#FFA0A0';            
            CONTEXT.lineWidth = 2;
            CONTEXT.moveTo(40+x1+10, y1+10);
            CONTEXT.lineTo(40+x2+10, y2+10);
            CONTEXT.stroke();
            
            CONTEXT.strokeStyle = strokeStyle;
            CONTEXT.lineWidth = lineWidth;
        }
        
        function drawLabel(x,y,string){
            CONTEXT.fillStyle    = '#0000FF';
            CONTEXT.font         = 'bold 10px sans-serif';
            CONTEXT.textBaseline = 'top';
            CONTEXT.fillText  (string, 40+x, y);
        }
        function drawLabelBetween(x1,y1,x2,y2, string){
            var midX = (4*x1+x2)/5;
            var midY = (4*y1+y2)/5;
            drawLabel(midX, midY, string);
        }
        
        function getWeight(fromLayer, fromNeuron, toNeuron){
            var result = 0;
            $.ajax({
                url : 'neural_network/getWeight/'+fromLayer+'/'+fromNeuron+'/'+toNeuron,
                async : false,
                success : function(response){
                    result = response;
                    result = Math.round(result*10000)/10000;
                }                
            });
            return result;
        }
        function getBias(fromLayer, toNeuron){
            var result = 0;
            $.ajax({
                url : 'neural_network/getBias/'+fromLayer+'/'+toNeuron,
                async : false,
                success : function(response){
                    result = response;
                    result = Math.round(result*10000)/10000;
                }                
            });
            return result;
        }
        
        function report(){
            if(watch){
                $.ajax({
                    url : "neural_network/state",
                    dataType : "json",
                    success : function(response){
                        if (JSON.stringify(_state) != JSON.stringify(response)){
                            _state = response;
                            
                            var weights = response.weights;
                            var MSE = response.MSE;
                            var loop = response.loop;
                            var learningRate = response.learningRate;
                            var neuronCount = response.neuronCount;
                            var str = "";
                            
                            CONTEXT.clearRect(0, 0, WIDTH, HEIGHT);
                            CONTEXT.save();
                            //draw lines
                            for(var i=0; i<neuronCount.length; i++){
                                var n = neuronCount[i];
                                var nNext = 0;
                                var nPrev = 0;
                                if(i<neuronCount.length-1){
                                    nNext = neuronCount[i+1];
                                }
                                if(i>0){
                                    nPrev = neuronCount[i-1];
                                }
                                
                                for(var j=0; j<n; j++){
                                    
                                    if(i==0){
                                        drawLine(i*80,j*80, (i+1)*80, j*80);
                                        
                                        drawLine(i*80, n*80, (i+1)*80, j*80);
                                    }
                                    if(i<neuronCount.length-1){
                                        for(var k=0; k<nNext; k++){
                                            drawLine((i+1)*80, j*80, (i+2)*80, k*80);
                                        }
                                    }
                                    if(i==neuronCount.length-1){
                                        drawLine((i+1)*80, j*80, (i+2)*80, j*80);                                        
                                        
                                        drawLine((i+1)*80, j*80, i*80, nPrev*80);
                                    }
                                    if(i>0 && i<neuronCount.length-1){
                                        drawLine((i+1)*80, j*80, i*80, nPrev*80);
                                    }
                                    
                                }
                            }
                            
                            //draw label and box
                            for(var i=0; i<neuronCount.length; i++){
                                var n = neuronCount[i];
                                var nNext = 0;
                                var nPrev = 0; 
                                if(i<neuronCount.length-1){
                                    nNext = neuronCount[i+1];
                                }
                                if(i>0){
                                    nPrev = neuronCount[i-1];
                                }
                                
                                for(var j=0; j<n; j++){
                                    drawBox((i+1)*80, j*80);
                                    
                                    if(i==0){
                                        drawLabelBetween(i*80,j*80, (i+1)*80, j*80, getWeight(-1,j,j));
                                        
                                        drawLabelBetween(i*80,n*80, (i+1)*80, j*80, getBias(-1,j));
                                    }
                                    if(i<neuronCount.length-1){
                                        for(var k=0; k<nNext; k++){
                                            drawLabelBetween((i+1)*80, j*80, (i+2)*80, k*80, getWeight(i, j,k));
                                        }
                                    }
                                    if(i==neuronCount.length-1){
                                        drawLabelBetween(i*80, nPrev*80, (i+1)*80, j*80, getBias(i-1, j));
                                    }
                                    if(i>0 && i<neuronCount.length-1){
                                        drawLabelBetween(i*80, nPrev*80, (i+1)*80, j*80, getBias(i-1, j));
                                    }
                                }
                                
                                if(i>0){
                                    drawBox(i*80, nPrev*80);
                                    drawLabel(i*80-10, nPrev*80+20, 'BIAS (-1)');
                                }else if(i==0){
                                    drawBox(i*80, n*80);
                                    drawLabel(i*80-10, n*80+20, 'BIAS (-1)');
                                }
                                
                            }
                            //CONTEXT.restore();
                            
                            str += "<b>LOOP:</b> "+loop+"<br />";
                            str += "<b>LEARNING RATE:</b> "+learningRate+"<br />";
                            if(MSE.length>0){
                                str += "<b>MSE:</b> "+MSE[MSE.length-1]+"<br />";
                            }
                            
                            str += "<b>WEIGHT:</b><br />";
                            for(var i=0; i<weights.length; i++){
                                str += weights[i]+'<br />';
                            }
                            
                            
                            var maxMSE = 0;
                            for(var i=0; i<MSE.length; i++){
                                if(MSE[i]>maxMSE){
                                    maxMSE = MSE[i];
                                }
                            }
                            
                            CONTEXT.clearRect(0, HEIGHT-100, WIDTH, HEIGHT);
                            
                            var step = 1;
                            if(MSE.length>WIDTH){
                                step = Math.ceil(MSE.length/WIDTH);
                            }                            
                            for(var i=1; i<MSE.length; i+=step){
                                drawLine(
                                    (WIDTH * (i-1)/MSE.length)-40, (HEIGHT-(MSE[i-1]/maxMSE * 100)), 
                                    (WIDTH * i/MSE.length)-40, (HEIGHT-(MSE[i]/maxMSE * 100)) 
                                );
                            }
                            drawLabel(0-40,HEIGHT-100, 'MAX MSE = '+maxMSE);
                            drawLabel(WIDTH-150,HEIGHT-10, 'MAX LOOP = '+MSE.length);
                            
                            CONTEXT.restore();
                            
                            
                            
                            $("#state").html(str);
                            
                            
                            $.ajax({
                                url : "neural_network/output",
                                dataType : "json",
                                success : function(response){
                                    if (JSON.stringify(_output) != JSON.stringify(response)){
                                        _output = response;

                                        output = _output;

                                        $.ajax({
                                            url : "neural_network/data",
                                            dataType : "json",
                                            success : function(response){
                                                var dataSet = response;
                                                var countInput= dataSet[0][0].length;
                                                var countOutput= dataSet[0][1].length;
                                                var str = '<table border="1"><tr><td colspan="'+countInput+'">Input</td>'+
                                                    '<td colspan="'+countOutput+'">Desired Output</td>'+
                                                    '<td colspan="'+countOutput+'">Output</td></tr>';

                                                for(var i=0; i<dataSet.length; i++){
                                                    str += '<tr>';

                                                    for(var j=0; j<countInput; j++){
                                                        str += '<td>'+dataSet[i][0][j]+'</td>';
                                                    }
                                                    for(var j=0; j<countOutput; j++){
                                                        str += '<td>'+dataSet[i][1][j]+'</td>';
                                                    }
                                                    for(var j=0; j<countOutput; j++){
                                                        str += '<td>'+output[i][j]+'</td>';
                                                    } 

                                                    str += '</tr>';
                                                }
                                                str +='</table>';


                                                $("#result").html(str);
                                            }
                                        });



                                    }                        
                                }
                            });
                        }                        
                    }
                });

                
            } 
        }
    </script>
    <style type="text/css">
        .blue-border{
            border : 1px solid blue;
            display : block;
            float : left;
        }
    </style>    
</head>
<body>
    
    <canvas id="canvas" width="500px" height="500px" class="blue-border"></canvas>
    <div id="result" class="blue-border"></div>
    <input id="btnSet" value="Set/Reset" type="button"/> 
    <input id="btnStop" value="Start" type="button"/>    
    <input id="btnWatch" value="Un-watch" type="button" />
    <div id="state" class="blue-border"></div>
    <div id="train" class="blue-border"></div>
</body>

