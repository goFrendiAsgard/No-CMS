//Microsoft.Glimmer.OneWay
//<AnimationCollection FilePath="G:\myprojects\tribe2\glimmer\Glimmer\glimmerUI\glimmerUI\samples\samples\js\freestyle.html.glimmer.js" xmlns="clr-namespace:GlimmerLib;assembly=GlimmerLib" xmlns:x="http://schemas.microsoft.com/winfx/2006/xaml"><Animation Name="planeFly" EventType="load" Trigger="{x:Null}"><Animation.Targets><Target Name="#plane" Duration="5000" Easing="easeInElastic" Callback="null"><Target.Effects><YTranslationEffect CSSName="top" DisplayName="Y Position Animation" MaxValue="5000" MinValue="-5000" From="0" To="-180" IsStartValue="True" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="False" JQueryPluginURI="" /><XTranslationEffect CSSName="left" DisplayName="X Position Animation" MaxValue="5000" MinValue="-5000" From="0" To="820" IsStartValue="True" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="planedropTimer" EventType="load" Trigger="{x:Null}"><Animation.Targets><Target Name="#plane" Duration="1000" Easing="linear" Callback="null"><Target.Effects><TimerEffect CSSName="planeDrop" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="5000" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="planeDrop" EventType="load" Trigger="{x:Null}"><Animation.Targets><Target Name="#plane" Duration="300" Easing="easeOutBounce" Callback="null"><Target.Effects><YTranslationEffect CSSName="top" DisplayName="Y Position Animation" MaxValue="5000" MinValue="-5000" From="0" To="0" IsStartValue="False" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="cloudsMove" EventType="mouseover" Trigger="#clouds"><Animation.Targets><Target Name="#clouds" Duration="1000" Easing="linear" Callback="null"><Target.Effects><EffectCollection /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="frogIn" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#frog" Duration="300" Easing="swing" Callback="null"><Target.Effects><TimerEffect CSSName="conversation" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="1000" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><YTranslationEffect CSSName="top" DisplayName="Y Position Animation" MaxValue="5000" MinValue="-5000" From="0" To="5" IsStartValue="False" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="False" JQueryPluginURI="" /><XTranslationEffect CSSName="left" DisplayName="X Position Animation" MaxValue="5000" MinValue="-5000" From="0" To="15" IsStartValue="False" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="visible" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="frogInTimer" EventType="load" Trigger="{x:Null}"><Animation.Targets><Target Name="" Duration="1000" Easing="linear" Callback="null"><Target.Effects><TimerEffect CSSName="frogIn" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="6500" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="conversation" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#bubble" Duration="1000" Easing="linear" Callback="null"><Target.Effects><TimerEffect CSSName="howdyOut" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="2000" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="visible" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="howdyOut" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#bubble" Duration="1000" Easing="linear" Callback="null"><Target.Effects><TimerEffect CSSName="okIn" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="1000" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="hidden" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="okIn" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#bubble2" Duration="1000" Easing="easeInBack" Callback="null"><Target.Effects><TimerEffect CSSName="okOut" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="2000" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="visible" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="okOut" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#bubble2" Duration="1000" Easing="linear" Callback="null"><Target.Effects><TimerEffect CSSName="sureIn" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="1000" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="hidden" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="sureIn" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#bubble" Duration="1000" Easing="linear" Callback="null"><Target.Effects><TimerEffect CSSName="mushroom" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="2000" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="background-image" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="url('sure.png')" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="visible" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="mushroom" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#bubble" Duration="1000" Easing="linear" Callback="null"><Target.Effects><TimerEffect CSSName="logoIn" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="3000" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="background-image" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="url('mushroom.png')" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="logoIn" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#mixLogo" Duration="1000" Easing="easeOutElastic" Callback="null"><Target.Effects><TimerEffect CSSName="textIn" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="2000" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="visible" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><XTranslationEffect CSSName="left" DisplayName="X Position Animation" MaxValue="5000" MinValue="-5000" From="1400px" To="0" IsStartValue="True" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="littlePlane" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#plane2" Duration="12000" Easing="linear" Callback="null"><Target.Effects><TimerEffect CSSName="download" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="2000" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><XTranslationEffect CSSName="left" DisplayName="X Position Animation" MaxValue="5000" MinValue="-5000" From="-200" To="1400" IsStartValue="True" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="visible" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="textIn" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#topInfo" Duration="1000" Easing="easeOutElastic" Callback="null"><Target.Effects><XTranslationEffect CSSName="left" DisplayName="X Position Animation" MaxValue="5000" MinValue="-5000" From="0" To="0" IsStartValue="True" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="False" JQueryPluginURI="" /><TimerEffect CSSName="download" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="500" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><TimerEffect CSSName="littlePlane" DisplayName="Timer Effect" MaxValue="10000" MinValue="1" From="0" To="500" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="clearTimeout(timer);&#xD;&#xA;     timer = setTimeout(eval({1}),{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="visible" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target><Target Name="#bubble" Duration="1000" Easing="linear" Callback="null"><Target.Effects><OpacityEffect CSSName="opacity" DisplayName="Opacity Animation" MaxValue="1" MinValue="0" From="0" To="0" IsStartValue="False" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target><Target Name="#bottomInfo" Duration="1000" Easing="linear" Callback="null"><Target.Effects><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="visible" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="dayToNight" EventType="load" Trigger="{x:Null}"><Animation.Targets><Target Name="#sky" Duration="1000" Easing="linear" Callback="null"><Target.Effects><ColorEffect CSSName="color" DisplayName="Color Animation" MaxValue="1" MinValue="0" From="#ccffff" To="&quot;darkblue&quot;" IsStartValue="True" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="True" JQueryPluginURI="effects.core.js" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="download" EventType="[none]" Trigger="{x:Null}"><Animation.Targets><Target Name="#download" Duration="1000" Easing="linear" Callback="null"><Target.Effects><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="visible" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="planeHover" EventType="mouseover" Trigger="#plane"><Animation.Targets><Target Name="#plane" Duration="1000" Easing="linear" Callback="null"><Target.Effects><OpacityEffect CSSName="opacity" DisplayName="Opacity Animation" MaxValue="1" MinValue="0" From="0" To="0" IsStartValue="False" IsActive="True" IsAnimatable="True" IsExpression="False" FormatString="" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="howdy2" EventType="mouseover" Trigger="#littleMushroomGuy"><Animation.Targets><Target Name="#howdy_bottom" Duration="1000" Easing="linear" Callback="null"><Target.Effects><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="visible" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation><Animation Name="howdy2_out" EventType="mouseout" Trigger="#littleMushroomGuy"><Animation.Targets><Target Name="#howdy_bottom" Duration="1000" Easing="easeOutElastic" Callback="null"><Target.Effects><ModifyCSSEffect CSSName="visibility" DisplayName="Modify CSS Effect" MaxValue="5000" MinValue="-5000" From="0" To="collapse" IsStartValue="False" IsActive="True" IsAnimatable="False" IsExpression="False" FormatString="$({0}).css({1},{2});&#xD;&#xA;" RequiresJQueryPlugin="False" JQueryPluginURI="" /></Target.Effects></Target></Animation.Targets></Animation></AnimationCollection>
jQuery(function($) {
var timer;
function planeFly(event)
{
     $("#plane").css("top","0");
     $("#plane").css("left","0");
    $("#plane").animate({"top":-180,"left":820},5000, "easeInElastic", null);
}

function planedropTimer(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("planeDrop"),"5000");
}

function planeDrop(event)
{
    $("#plane").animate({"top":0},300, "easeOutBounce", null);
}

function cloudsMove(event)
{
}

function frogIn(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("conversation"),"1000");
     $("#frog").css("visibility","visible");
    $("#frog").animate({"top":5,"left":15},300, "swing", null);
}

function frogInTimer(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("frogIn"),"6500");
}

function conversation(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("howdyOut"),"2000");
     $("#bubble").css("visibility","visible");
}

function howdyOut(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("okIn"),"1000");
     $("#bubble").css("visibility","hidden");
}

function okIn(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("okOut"),"2000");
     $("#bubble2").css("visibility","visible");
}

function okOut(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("sureIn"),"1000");
     $("#bubble2").css("visibility","hidden");
}

function sureIn(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("mushroom"),"2000");
     $("#bubble").css("background-image","url('images/sure.png')");
     $("#bubble").css("visibility","visible");
}

function mushroom(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("logoIn"),"3000");
     $("#bubble").css("background-image","url('images/mushroom.png')");
}

function logoIn(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("textIn"),"2000");
     $("#mixLogo").css("visibility","visible");
     $("#mixLogo").css("left","1400px");
    $("#mixLogo").animate({"left":0},1000, "easeOutElastic", null);
}

function littlePlane(event)
{
     clearTimeout(timer);
     timer = setTimeout(eval("download"),"2000");
     $("#plane2").css("left","-200");
     $("#plane2").css("visibility","visible");
    $("#plane2").animate({"left":1400},12000, "linear", null);
}

function textIn(event)
{
     $("#topInfo").css("left","0");
     clearTimeout(timer);
     timer = setTimeout(eval("download"),"500");
     clearTimeout(timer);
     timer = setTimeout(eval("littlePlane"),"500");
     $("#topInfo").css("visibility","visible");
    $("#topInfo").animate({"left":0},1000, "easeOutElastic", null);
    $("#bubble").animate({"opacity":0},1000, "linear", null);
     $("#bottomInfo").css("visibility","visible");
    $("#bubble").animate({"opacity":0},1000, "linear", null);
}

function dayToNight(event)
{
     $("#sky").css("color","#ccffff");
    $("#sky").animate({"color":"darkblue"},1000, "linear", null);
}

function download(event)
{
     $("#download").css("visibility","visible");
}

function planeHover(event)
{
    $("#plane").animate({"opacity":0},1000, "linear", null);
}

function howdy2(event)
{
     $("#howdy_bottom").css("visibility","visible");
}

function howdy2_out(event)
{
     $("#howdy_bottom").css("visibility","collapse");
}

planeFly();

planedropTimer();

planeDrop();

$('#clouds').bind('mouseover', cloudsMove);


frogInTimer();










dayToNight();


$('#plane').bind('mouseover', planeHover);

$('#littleMushroomGuy').bind('mouseover', howdy2);

$('#littleMushroomGuy').bind('mouseout', howdy2_out);

});