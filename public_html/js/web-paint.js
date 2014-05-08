/*
 * Web paint js library
 */
var Paint = {
    "c2d": false,
    "isMouseDown": false,
};

Paint.getInstrument = function()
{
    return $('#paint-instrument-current').text();
}

Paint.getBgColor = function()
{
    return $('#paint-color-bg').val();
}

Paint.getFgColor = function()
{
    return $('#paint-color-fg').val();
}

Paint.getLineWidth = function()
{
    return $('#paint-line-width').val();
}

Paint.getLineCap = function()
{
    return $('#paint-line-cap').val();
}

Paint.getRectangleType = function()
{
    return $('#paint-rectangle-type').val();
}

Paint.getRectangleBegin = function()
{
    return {
        "x": $('#rect-x').val(),
        "y": $('#rect-y').val()
    };
}

Paint.setInstrument = function(instrument)
{
    $('#paint-instrument-current').text(instrument);
}

Paint.setRectangleBegin = function(begin)
{
    $('#rect-x').val(begin.x);
    $('#rect-y').val(begin.y);
}

Paint.init = function()
{
    Paint.c2d = document.getElementById('paint').getContext('2d');
    
    $('#paint').parent().append(
            '<input type="hidden" id="rect-x" />' +
            '<input type="hidden" id="rect-y" />');
    
    $('#paint-instrument-brush').bind('click', function() { Paint.setInstrument('Brush'); });
    $('#paint-instrument-line').bind('click', function() { Paint.setInstrument('Line'); });
    $('#paint-instrument-rectangle').bind('click', function() { Paint.setInstrument('Rectangle'); });
    $('#paint').bind('mousemove', mousemoveAction);
    $('#paint').bind('mousedown', mousedownAction);
    $('#paint').bind('mouseup', mouseupAction);
    //$('#paint-file-new').bind('click', fileNewAction);
    //$('#paint-file-open').bind('click', fileOpenAction);
    //$('#paint-file-save').bind('click', fileSaveAction);
    //$('#paint-file-exit').bind('click', fileExitAction);
    //$('#paint-instrument-brush').bind('click', instrumentBrushAction);
    //$('#paint-instrument-line').bind('click', instrumentLineAction);
    //$('#paint-instrument-rectangle').bind('click', instrumentRectangleAction);
    //$('#paint-instrument-circle').bind('click', instrumentCircleAction);
}

// canvas actions
function mousemoveAction(event)
{    
    if (Paint.isMouseDown)
    {
        switch(Paint.getInstrument())
        {
            case 'Brush':
                Paint.c2d.lineTo(
                        event.pageX - $('#paint').offset().left,
                        event.pageY - $('#paint').offset().top);
                Paint.c2d.strokeStyle = Paint.getBgColor();
                Paint.c2d.lineWidth = Paint.getLineWidth();
                Paint.c2d.lineCap = Paint.getLineCap();
                Paint.c2d.stroke();
                break;
        }
    }
}

function mousedownAction(event)
{
    var x = event.pageX - $('#paint').offset().left;
    var y = event.pageY - $('#paint').offset().top;
    
    Paint.isMouseDown = true;

    switch (Paint.getInstrument())
    {
        case 'Brush':
            Paint.c2d.beginPath();
            Paint.c2d.moveTo(x, y);
            break;
        case 'Line':
            Paint.c2d.beginPath();
            Paint.c2d.moveTo(x, y);
            break;
        case 'Rectangle':
            Paint.c2d.beginPath();
            Paint.setRectangleBegin({"x": x, "y": y});
            break;
    }
}

function mouseupAction(event)
{
    var x = event.pageX - $('#paint').offset().left;
    var y = event.pageY - $('#paint').offset().top;
    
    Paint.isMouseDown = false;
    
    Paint.c2d.strokeStyle = Paint.getBgColor();
    Paint.c2d.fillStyle = Paint.getFgColor();
    Paint.c2d.lineWidth = Paint.getLineWidth();
    Paint.c2d.lineCap = Paint.getLineCap();
    
    switch (Paint.getInstrument())
    {
        case 'Brush':
            Paint.c2d.closePath();
            break;
        case 'Line':
            Paint.c2d.lineTo(x, y);
            Paint.c2d.stroke();
            Paint.c2d.closePath();
            break;
        case 'Rectangle':
            var begin = Paint.getRectangleBegin();
            var type  = Paint.getRectangleType();

            if (type == 'stroke')
            {
                Paint.c2d.strokeRect(begin.x, begin.y, x-begin.x, y-begin.y);
            }
            else if (type == 'fill')
            {
                Paint.c2d.fillRect(begin.x, begin.y, x-begin.x, y-begin.y);
            }
            else if (type == 'rect')
            {
                Paint.c2d.strokeRect(begin.x, begin.y, x-begin.x, y-begin.y);
                Paint.c2d.fillRect(begin.x, begin.y, x-begin.x, y-begin.y);
            }
            
            Paint.c2d.closePath();
            break;
    }
}

$('document').ready(function() {
    Paint.init();
});