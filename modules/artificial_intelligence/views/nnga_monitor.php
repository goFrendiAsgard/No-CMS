<script type="text/javascript" src ="<?php echo base_url().'assets/jquery.js';?>"></script>
<script type="text/javascript">
    //below are the global variables
    var CANVAS_WIDTH=500;
    var CANVAS_HEIGHT=600;
    var NN_HEIGHT = 300;
    var MSE_GRAPH_HEIGHT = 100;
    var FITNESS_GRAPH_HEIGHT = 100;
    var CANVAS;
    var CONTEXT;
    var RESPONSE = "";
    var WATCH = true;
    
    function getNodeX(layer, totalLayer){
        return 10+(layer+1) * (CANVAS_WIDTH-20)/(totalLayer+2);
    }
    
    function getNodeY(neuron, totalNeuron){
        return (neuron+1) * NN_HEIGHT/(totalNeuron+2);
    }
    
    function drawLabel(x,y, label){
        CONTEXT.fillStyle    = '#0000FF';
        CONTEXT.font         = 'bold 10px sans-serif';
        CONTEXT.textBaseline = 'top';
        CONTEXT.fillText  (label, x, y);        
    }
    
    function drawLine(x1,y1, x2,y2){        
        CONTEXT.strokeStyle = '#FF0000';            
        //CONTEXT.lineWidth = 2;
        CONTEXT.moveTo(x1, y1);
        CONTEXT.lineTo(x2, y2);
        CONTEXT.stroke();
    }
    
    function drawLabelBetween(x1, y1, x2, y2, label){
        var xBetween = (x1*3+x2)/4;
        var yBetween = (y1*3+y2)/4;
        drawLabel(xBetween, yBetween, label);
    }
    
    function drawCircle(x,y, r){
        CONTEXT.strokeStyle = "#FF0000";
        CONTEXT.fillStyle = "#FF0000";
        CONTEXT.beginPath();
        CONTEXT.arc(x,y,r,0,Math.PI*2,true);
        CONTEXT.closePath();
        CONTEXT.stroke();
        CONTEXT.fill();
    }
    
    function adjustCanvasSize(){
        $("#canvas").width(CANVAS_WIDTH);
        $("#canvas").height(CANVAS_HEIGHT);
    }
    
    function updateInfo(){
        if(!WATCH) return 0;
        $.ajax({
            url:'<?php echo base_url()?>index.php/artificial_intelligence/nnga/currentState',
            dataType:'json',
            async: false,
            success:function(response){
                if (JSON.stringify(RESPONSE) != JSON.stringify(response)){
                    RESPONSE = response;
                    
                    var nn = response["nn"];
                    var ga = response["ga"];
                    var ds = nn.nn_dataset;
                    var neuronCount = nn.nn_neuronCount;
                    var layerCount = neuronCount.length;
                    var weights = nn.nn_weights;
                    var MSE = nn.nn_MSE;
                    var fitness = ga.ga_bestFitness;
                    
                    adjustCanvasSize();
                    CANVAS = document.getElementById('canvas');
                    CONTEXT = CANVAS.getContext('2d');
                    CONTEXT.clearRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
                    CONTEXT.save();
                    
                    //input to input neuron
                    for(var i=0; i<neuronCount[0]; i++){
                        //weight from input to input layer
                        drawLine(getNodeX(-1,layerCount),getNodeY(i,neuronCount[0]), 
                            getNodeX(0,layerCount), getNodeY(i,neuronCount[0]));
                            
                        //bias to input layer
                        drawLine(getNodeX(-1,layerCount),getNodeY(neuronCount[0],neuronCount[0]), 
                            getNodeX(0,layerCount), getNodeY(i,neuronCount[0]));
                            
                        //input
                        drawCircle(getNodeX(-1,layerCount), getNodeY(i,neuronCount[0]), 2);
                        //neuron input layer
                        drawCircle(getNodeX(0,layerCount), getNodeY(i,neuronCount[0]), 5);
                    }
                    //bias input layer
                    drawCircle(getNodeX(-1,layerCount), getNodeY(neuronCount[0],neuronCount[0]), 3);
                    
                    //between layers
                    for(var i=0; i<layerCount-1; i++){ //fromLayer
                        for(var j=0; j<neuronCount[i+1]; j++){ //toNeuron                            
                            for(var k=0; k<neuronCount[i]; k++){ //from neuron
                                //weight
                                drawLine(getNodeX(i,layerCount),getNodeY(k,neuronCount[i]),
                                    getNodeX(i+1,layerCount), getNodeY(j,neuronCount[i+1]));
                            }
                            //neuronTo
                            drawCircle(getNodeX(i+1,layerCount), getNodeY(j,neuronCount[i+1]), 5);
                            
                            drawLine(getNodeX(i,layerCount),getNodeY(neuronCount[i],neuronCount[i]),
                                getNodeX(i+1,layerCount), getNodeY(j,neuronCount[i+1]));
                            
                        }
                        //bias
                        drawCircle(getNodeX(i,layerCount), getNodeY(neuronCount[i],neuronCount[i]), 3);
                        
                    }
                    
                    for(var i=0; i<neuronCount[layerCount-1]; i++){
                         drawLine(getNodeX(layerCount-1,layerCount),getNodeY(i,neuronCount[layerCount-1]), 
                            getNodeX(layerCount,layerCount), getNodeY(i,neuronCount[layerCount-1]));
                            
                         drawCircle(getNodeX(layerCount,layerCount), getNodeY(i,neuronCount[layerCount-1]), 2);
                    }
                    
                    //the weights
                    for(var i=0; i<weights.length; i++){
                         var weight = weights[i];
                         var fromLayer = weight["fromLayer"];
                         var fromNeuron = weight["fromNeuron"];
                         var toNeuron = weight["toNeuron"];
                         var value = weight["value"];
                         
                         var toLayer = fromLayer+1;
                         var fromLayerNeuronCount = 0;
                         var toLayerNeuronCount = neuronCount[toLayer];
                         if(fromLayer==-1){
                             fromLayerNeuronCount = neuronCount[0];
                         }else{
                             fromLayerNeuronCount = neuronCount[fromLayer];
                         }
                         drawLabelBetween(getNodeX(fromLayer, layerCount),getNodeY(fromNeuron, fromLayerNeuronCount), 
                            getNodeX(toLayer, layerCount),getNodeY(toNeuron, toLayerNeuronCount), Math.round(value*10000)/10000);
                    }
                    
                    //draw best fitness
                    var step = 1;
                    var fitnessCount = fitness.length;
                    var maxFitness = 0;
                    var minFitness = 0;
                    var top = NN_HEIGHT + 20;
                    var bottom = top + FITNESS_GRAPH_HEIGHT;
                    //determine step
                    if(fitnessCount>CANVAS_WIDTH){
                        step = Math.ceil(fitnessCount/CANVAS_WIDTH);
                    }
                    //determine min and max fitness
                    for(var i=0; i<fitnessCount; i++){
                        if(i==0){
                            minFitness = fitness[i];
                            maxFitness = fitness[i];
                        }
                        if(fitness[i]>maxFitness) maxFitness = fitness[i];
                        if(fitness[i]<minFitness) minFitness = fitness[i];
                    }                    
                    //draw axis
                    drawLine(1,top, 1,bottom);
                    drawLine(1,bottom, CANVAS_WIDTH,bottom);
                    for(var i=0; i<fitnessCount-step; i+=step){
                        drawLine(CANVAS_WIDTH * i/fitnessCount, bottom-(fitness[i]*FITNESS_GRAPH_HEIGHT/maxFitness), 
                            CANVAS_WIDTH * (i+step)/fitnessCount, bottom-(fitness[i+step]*FITNESS_GRAPH_HEIGHT/maxFitness));
                    }
                    if(fitnessCount>0){
                        drawLabel(CANVAS_WIDTH-175,top, "Max Fitness = "+maxFitness);
                        drawLabel(CANVAS_WIDTH-175,top+10, "Min Fitness = "+minFitness);
                        drawLabel(CANVAS_WIDTH-175,top+20, "Current Fitness = "+fitness[fitnessCount-1]);
                        drawLabel(CANVAS_WIDTH-175,top+30, "Current Loop = "+ga.ga_loop);
                        drawLabel(CANVAS_WIDTH-175,top+40, "Time = "+ga.ga_time);
                    }
                    
                    
                    
                    //draw MSE
                    step = 1;
                    var MSECount = MSE.length;
                    var maxMSE = 0;
                    var minMSE = 0;
                    var top = NN_HEIGHT+FITNESS_GRAPH_HEIGHT+ 40;
                    var bottom = top + MSE_GRAPH_HEIGHT;
                    //determine step
                    if(MSECount>CANVAS_WIDTH){
                        step = Math.ceil(MSECount/CANVAS_WIDTH);
                    }
                    //determine min and max fitness
                    for(var i=0; i<MSECount; i++){
                        if(i==0){
                            minMSE = MSE[i];
                            maxMSE = MSE[i];
                        }
                        if(MSE[i]>maxMSE) maxMSE = MSE[i];
                        if(MSE[i]<minMSE) minMSE = MSE[i];
                    }                    
                    //draw axis
                    drawLine(1,top, 1,bottom);
                    drawLine(1,bottom, CANVAS_WIDTH,bottom);
                    for(var i=0; i<MSECount-step; i+=step){
                        drawLine(CANVAS_WIDTH * i/MSECount, bottom-(MSE[i]*MSE_GRAPH_HEIGHT/maxMSE), 
                            CANVAS_WIDTH * (i+step)/MSECount, bottom-(MSE[i+step]*MSE_GRAPH_HEIGHT/maxMSE));
                    }
                    if(MSECount>0){
                        drawLabel(CANVAS_WIDTH-175,top, "Max MSE = "+maxMSE);
                        drawLabel(CANVAS_WIDTH-175,top+10, "Min MSE = "+minMSE);
                        drawLabel(CANVAS_WIDTH-175,top+20, "Current MSE = "+MSE[MSECount-1]);
                        drawLabel(CANVAS_WIDTH-175,top+30, "Current Loop = "+nn.nn_loop);
                        drawLabel(CANVAS_WIDTH-175,top+40, "Time = "+nn.nn_time);
                    }
                    
                    
                    if(ds.length>0){
                        var str = "";
                        var inputCount = ds[0].input.length;
                        var targetCount = ds[0].target.length;
                        var outputCount = targetCount;
                        str+= '<table>';
                        str+= '<tr><td colspan="'+inputCount+'">Input</td><td colspan="'+targetCount+'">Target</td><td colspan="'+outputCount+'">Output</td></tr>';
                        for(var i=0; i<ds.length; i++){
                            str+= '<tr>';
                            for(var j=0; j<inputCount; j++){
                                str+= '<td>'+ds[i].input[j]+'</td>';
                            }
                            for(var j=0; j<targetCount; j++){
                                str+= '<td>'+ds[i].target[j]+'</td>';
                            }
                            for(var j=0; j<outputCount; j++){
                                str+= '<td>'+ds[i].output[j]+'</td>';
                            }
                            str+= '</tr>';
                        } 
                        str+= '</table>';
                        $("div#output").html(str);
                    }
                              
                    
                    
                    
                    
                }
            }
        });
    }
    
    $(document).ready(function(){
        
        $("#btn_train_nn").click(function(){
            $("input[type=button]").attr("disabled", "disabled");
            $.ajax({
                url:'<?php echo base_url()?>index.php/artificial_intelligence/nnga/trainNN',
                success : function(response){
                    $("input[type=button]").removeAttr("disabled");
                }
            });
        });
        
        $("#btn_train_nnga").click(function(){
            $("input[type=button]").attr("disabled", "disabled");
            $.ajax({
                url:'<?php echo base_url()?>index.php/artificial_intelligence/nnga/trainNNGA',
                success : function(response){
                    $("input[type=button]").removeAttr("disabled");
                }
            });
        });
        
        $("#chk_watch").change(function(){
            if($(this).attr("checked")){
                WATCH = true;
            }else{
                WATCH = false;
            }
        });
        
        
        
        setInterval(updateInfo,200);
    });
</script>
<div id="control">
    <input type="button" id="btn_train_nn" value="Train Neural Network" />
    <input type="button" id="btn_train_nnga" value="Train Neural Network with Genetics Algorithm" />
    <input type="checkbox" id="chk_watch" checked="true" />
    <label>Watch the progress</label>        
</div>
<div id="graphic">
    <canvas id="canvas" width="500px" height="600px"></canvas>
</div>
<div id="output">
</div>
