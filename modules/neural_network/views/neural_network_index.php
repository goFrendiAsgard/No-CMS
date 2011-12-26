<head>
    <script type="text/javascript" src="../assets/jquery.js"></script>
    <script type="text/javascript">
        var state = "";
        var result = "";
        var training = "";
        var watch = true;
        
        $(document).ready(function(){            
            
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
            
            setInterval(report,100);
        });
        
        function report(){
            if(watch){
                $.ajax({
                    url : "neural_network/state",
                    //dataType : "json",
                    success : function(response){
                        if (response!=state){
                            state = response;
                            /**
                            var str = "";
                            var weights = response["weights"];
                            var weight = "";
                            for(weight in weights){
                                str += weight;
                                str += '<br />';
                            }
                            */
                            var str = state;
                            $("#state").html(str);
                        }                        
                    }
                });

                $.ajax({
                    url : "neural_network/output",
                    //dataType : "json",
                    success : function(response){
                        if (response!=result){
                            result = response;
                            /**
                            var str = "";
                            for(var i=0; i<result.length; i++){
                                for(var j=0; j<result[i].length; j++){
                                    str += result[i][j];
                                }
                                str += '<hr />';
                            }
                            */
                            var str = result;
                            $("#result").html(str);
                        }                        
                    }
                });
            } 
        }
    </script>
    <style type="text/css">
        div.blue-border{
            border : 1px solid blue;
            display : block;
            float : left;
        }
    </style>    
</head>
<body>
    <input id="btnSet" value="Set/Reset" type="button"/> 
    <input id="btnStop" value="Start" type="button"/>    
    <input id="btnWatch" value="Un-watch" type="button" />
    <div id="result" class="blue-border"></div>
    <div id="state" class="blue-border"></div>
    <div id="train" class="blue-border"></div>
</body>

