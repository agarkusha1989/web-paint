/*
 * Web paint js library
 */
var Paint = {
    "c2d": false,
    "instrument": "bush",
    "bgColor": "#000000",
    "fgColor": "#ffffff",
    "isMouseDown": false,
    "line": {
        "startX": 0,
        "startY": 0,
        "color": "#000000",
        "endX": 0,
        "endY": 0
    }
};

function paintInit()
{
    Paint.c2d = document.getElementById('paint').getContext('2d');
    
    $('#paint').bind('mousemove', mousemoveAction);
    $('#paint').bind('mousedown', mousedownAction);
    $('#paint').bind('mouseup', mouseupAction);
    $('#paint-file-new').bind('click', fileNewAction);
    $('#paint-file-open').bind('click', fileOpenAction);
    $('#paint-file-save').bind('click', fileSaveAction);
    $('#paint-file-exit').bind('click', fileExitAction);
    $('#paint-instrument-brush').bind('click', instrumentBrushAction);
    $('#paint-instrument-line').bind('click', instrumentLineAction);
    $('#paint-instrument-rectangle').bind('click', instrumentRectangleAction);
    $('#paint-instrument-circle').bind('click', instrumentCircleAction);
    $('#paint-color-bg').bind('change', colorBgAction);
    $('#paint-color-fg').bind('change', colorFgAction);
    $('#paint-color-bg').val(Paint.bgColor);
    $('#paint-color-fg').val(Paint.fgColor);
}

// canvas actions
function mousemoveAction(event)
{    
    if (Paint.isMouseDown)
    {
        switch(Paint.instrument)
        {
            case 'brush':
                Paint.c2d.lineTo(
                        event.pageX - $('#paint').offset().left,
                        event.pageY - $('#paint').offset().top);
                Paint.c2d.strokeStyle = Paint.bgColor;
                Paint.c2d.stroke();
                break;
        }
    }
    else
    {
        
    }
}

function mousedownAction(event)
{
    Paint.isMouseDown = true;
    
    switch (Paint.instrument)
    {
        case 'brush':
            Paint.c2d.moveTo(
                    event.pageX - $('#paint').offset().left,
                    event.pageY - $('#paint').offset().top);
            break;
    }
}

function mouseupAction(event)
{
    Paint.isMouseDown = false;
    
    switch (Paint.instrument)
    {
        case 'brush':
            Paint.c2d.save();
            break;
    }
}

// Main menu actions
function fileNewAction(event)
{
    
}
function fileOpenAction(event)
{
    
}
function fileSaveAction(event)
{
    
}
function fileExitAction(event)
{
    
}
function instrumentBrushAction()
{
    Paint.instrument = "brush";
}
function instrumentLineAction(event)
{
    Paint.instrument = "line";
}
function instrumentRectangleAction(event)
{
    Paint.instrument = "rectangle";
}
function instrumentCircleAction(event)
{
    Paint.instrument = "circle";
}
function colorBgAction(event)
{
    Paint.bgColor = $('#paint-color-bg').val();
}
function colorFgAction(event)
{
    Paint.fgColor = $('#paint-color-fg').val();
}

$('document').ready(function() {
    paintInit();
});