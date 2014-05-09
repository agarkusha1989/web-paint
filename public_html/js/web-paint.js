function WPMenuBar(wp)
{
    var canvas = document.getElementById(wp.canvasId);
    var idPref = wp.canvasId;
    var fileId = undefined;
    
    // menu bar element
    var divMenuBar = document.createElement('div');
    divMenuBar.setAttribute("id", idPref + "-menu");
    canvas.parentNode.insertBefore(divMenuBar, canvas);
    
    // new button
    var buttonNew = document.createElement('button');
    buttonNew.setAttribute("id", this.canvasId + "-new");
    buttonNew.innerHTML = "New";
    divMenuBar.appendChild(buttonNew);
     
    // save button
    var buttonSave = document.createElement('button');
    buttonSave.setAttribute("id", this.canvasId + "-save");
    buttonSave.innerHTML = "Save";
    divMenuBar.appendChild(buttonSave);
    
    // save as button
    var buttonSaveAs = document.createElement('button');
    buttonSaveAs.setAttribute("id", this.canvasId + "-save-as");
    buttonSaveAs.innerHTML = "Save As";
    divMenuBar.appendChild(buttonSaveAs);
        
    // download button
    var buttonDownload = document.createElement('button');
    buttonDownload.setAttribute("id", this.canvasId + "-download");
    buttonDownload.innerHTML = "Download";
    divMenuBar.appendChild(buttonDownload);
        
    // select tool
    var labelTool = document.createElement('label');
    labelTool.setAttribute("for", this.canvasId + "-select-tool");
    labelTool.style.paddingLeft = "10px";
    labelTool.style.paddingRight = "4px";
    labelTool.innerHTML = "Tool:";
    divMenuBar.appendChild(labelTool);

    var selTool = document.createElement('select');
    selTool.id = this.canvasId + "-select-tool";
    selTool.style.marginRight = "10px";
    selTool.options[0] = new Option("Brush", "brush", true, true);
    selTool.options[1] = new Option("Line", "line");
    selTool.options[2] = new Option("Rectangle", "rect");
    selTool.addEventListener("change", function()
    {
        var tool = this.options[this.selectedIndex].value;

        if (tool == 'rect')
        {
            this.nextSibling.options[0] = new Option("Rect", "rect");
            this.nextSibling.options[1] = new Option("Fill", "fill");
            this.nextSibling.options[2] = new Option("Stroke", "stroke");
            this.nextSibling.style.display = "inline-block";
        }
        else
        {
            this.nextSibling.options.length = 0;
            this.nextSibling.style.display = "none";
        }
    });
    divMenuBar.appendChild(selTool);

    // select rect type
    var selType = document.createElement('select');
    selType.setAttribute("id", this.canvasId + "-select-type");
    selType.style.display = 'none';
    selType.style.marginRight = "10px";
    divMenuBar.appendChild(selType);

    // select colors
    var labelColor = document.createElement('label');
    labelColor.style.paddingRight = "4px";
    labelColor.innerHTML = "Color:";
    divMenuBar.appendChild(labelColor);

    colorBg = document.createElement('input');
    colorBg.setAttribute("type", "color");
    colorBg.setAttribute("id", this.canvasId + "-color-bg");
    colorBg.value = "#000000";
    divMenuBar.appendChild(colorBg);

    colorFg = document.createElement('input');
    colorFg.setAttribute("type", "color");
    colorFg.setAttribute("id", this.canvasId + "-color-fg");
    colorFg.style.marginRight = "10px";
    colorFg.value = "#ffffff";
    divMenuBar.appendChild(colorFg);

    // select width
    var labelWidth = document.createElement('label');
    labelWidth.style.paddingRight = "4px";
    labelWidth.innerHTML = "Width:";
    divMenuBar.appendChild(labelWidth);

    selWidth = document.createElement('select');
    selWidth.id = this.canvasId + "-select-width";
    selWidth.style.marginRight = "10px";
    selWidth.options[0] = new Option("1 px", "1", true, true);
    selWidth.options[1] = new Option("2 px", "2");
    selWidth.options[2] = new Option("3 px", "3");
    selWidth.options[3] = new Option("5 px", "5");
    selWidth.options[4] = new Option("7 px", "7");
    selWidth.options[5] = new Option("10 px", "10");
    selWidth.options[6] = new Option("12 px", "12");
    divMenuBar.appendChild(selWidth);
    
    // colors
    this.getColorBg = function()
    {
        return colorBg.value;
    }
    this.getColorFg = function()
    {
        return colorFg.value;
    }
    
    // tool
    this.getTool = function()
    {
        return selTool.options[selTool.selectedIndex].value;
    }
    
    this.getType = function()
    {
        return selType.options[selType.selectedIndex].value;
    }
    
    // width
    this.getWidth = function()
    {
        return selWidth.options[selWidth.selectedIndex].value;
    }
    
    this.getFileId = function()
    {
        return fileId;
    }
    
    // enets 
    // new
    
    buttonNew.addEventListener('click', function()
    {
        // clear canvas
        // prompt filename and size
        /*
        if (prompt("There are unsaved changes to save?"))
        {
            buttonSave.click();
        }
        */
        wp.canvas.clear();
        fileId = undefined;
        
        while(undefined == (width = prompt("Image width"))) ;
        while(undefined == (height = prompt("Image height"))) ;
        
        wp.canvas.setSize({"width": width, "height": height});
    });
    // download
    buttonDownload.addEventListener('click', function()
    {
        console.log('download');
    });
    // save
    buttonSave.addEventListener('click', function()
    {
        if (fileId != undefined)
        {
            ajax = new XMLHttpRequest();
            ajax.open("POST", "/paint/save?id=" + fileId, false);
            ajax.setRequestHeader("Content-type", "application/upload");
            ajax.onreadystatechange = function()
            {
                if (ajax.readyState == 4)
                {
                    var json = eval( '(' + ajax.responseText + ')');
                    
                    if (json == undefined)
                    {
                        alert('Save file error');
                    }
                    else if (json.error != undefined)
                    {
                        alert('Save file error: ' + json.error);
                    }
                }
            }
            ajax.send(wp.canvas.getData());
        }
        else
        {
            buttonSaveAs.click();
        }
    });
    // save as
    buttonSaveAs.addEventListener('click', function()
    {
        while(undefined == (filename = prompt("Enter new filename"))) ;
        
        ajax = new XMLHttpRequest();
        ajax.open("POST", "/paint/saveas?filename=" + filename, false);
        ajax.setRequestHeader("Content-type", "application/upload");
        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                var json = eval(' (' + ajax.responseText + ')');
                
                if (json == undefined)
                {
                    alert('Save new file error');
                }
                else if (json.error != undefined)
                {
                    alert('Save file error: ' + json.error);
                }
                else if (json.id == undefined)
                {
                    alert('Save file error, insert id is null');
                }
                
                fileId = json.id;
            }
        }
        ajax.send(wp.canvas.getData());
    });
}

function WPCanvas(wp)
{
    // canvas dom object
    var canvas = document.getElementById(wp.canvasId);
    var isMouseDown = false;
    
    // get canvas 2d context
    this.get2d = function()
    {
        return canvas.getContext('2d');
    }
    
    this.getData = function()
    {
        return canvas.toDataURL("image/png");
    }
    
    this.clear = function()
    {
        var width = this.getSize().width;
        var height = this.getSize().height;
        var c = this.get2d();
        
        c.beginPath();
        c.clearRect(0, 0, width, height);
        c.closePath();
    }
    
    this.getSize = function()
    {
        return {
            "width": canvas.getAttribute("width"),
            "height": canvas.getAttribute("height")
        };
    }
    
    // set new canvas size
    this.setSize = function(size)
    {
        if (size.width != undefined)
        {
            canvas.setAttribute("width", size.width);
        }
        if (size.height != undefined)
        {
            canvas.setAttribute("height", size.height);
        }
    }
    
    this.getCanvasPoint = function(e)
    {
        return {
            "x": event.pageX - canvas.offsetLeft,
            "y": event.pageY - canvas.offsetTop
        };
    }
    
    // event mousedown
    canvas.addEventListener('mousedown', function(e)
    {
        event       = event || window.event;
        isMouseDown = true;
        
        var point   = wp.canvas.getCanvasPoint(e);
        var tool   = wp.menuBar.getTool();
        
        switch (tool)
        {
            case 'brush':
                wp.brushStart(point);
                break;
            case 'line':
                wp.lineStart(point);
                break;
            case 'rect':
                wp.rectStart(point);
        }
    });
    
    // event mouseup
    canvas.addEventListener('mouseup', function(e)
    {
        event       = event || window.event;    
        isMouseDown = false;
        
        var point   = wp.canvas.getCanvasPoint(e);
        var tool   = wp.menuBar.getTool();
        
        switch (tool)
        {
            case 'brush':
                wp.brushStop();
                break;
            case 'line':
                wp.lineStop(point);
                break;
            case 'rect':
                wp.rectStop(point);
        }
    });
    
    // event mousemove
    canvas.addEventListener('mousemove', function(e)
    {
        event       = event || window.event;    
        
        var point   = wp.canvas.getCanvasPoint(e);
        var tool    = wp.menuBar.getTool();
        
        //console.log(point);
        
        switch (tool)
        {
            case 'brush':
                if (isMouseDown) wp.brushDraw(point);
                break;
            case 'line':
            case 'rect':
        }
    });
        
}

function WPFileSaver(wp)
{
    this.save = function()
    {
        
    }
    
    this.download = function()
    {
        
    }
}

function WP()
{
    this.canvasId = "web-paint";
    
    this.canvas = new WPCanvas(this);
    this.menuBar = new WPMenuBar(this);
    this.fileSaver = new WPFileSaver(this);
    
    var cntx2d = this.canvas.get2d();
    
    // public methods
    // brush
    this.brushStart = function(point)
    {
        cntx2d.beginPath();
        cntx2d.moveTo(point.x, point.y);
    }
    this.brushDraw = function(point)
    {
        cntx2d.lineTo(point.x, point.y);
        
        // set line style
        cntx2d.strokeStyle = this.menuBar.getColorBg();
        cntx2d.lineWidth = this.menuBar.getWidth();

        // draw 
        cntx2d.stroke();
    }
    this.brushStop = function()
    {
        cntx2d.closePath();
    }
    
    // line
    this.lineStart = function(point)
    {   
        cntx2d.beginPath();
        cntx2d.moveTo(point.x, point.y);
    }
    this.lineStop = function(point)
    {
        cntx2d.lineTo(point.x, point.y);
        
        // set line style
        cntx2d.strokeStyle = this.menuBar.getColorBg();
        cntx2d.lineWidth = this.menuBar.getWidth();
        
        // draw 
        cntx2d.stroke();
        cntx2d.closePath();
    }
    
    // rect
    this.rectPoint = {"x": 0, "y": 0};
    this.rectStart = function(point)
    {
        this.rectPoint = point;
        cntx2d.beginPath();
    }
    this.rectStop = function(point)
    {
        var width = Math.abs(point.x - this.rectPoint.x);
        var height = Math.abs(point.y - this.rectPoint.y);
        var type = this.menuBar.getType();
        
        // set line and fill style
        cntx2d.strokeStyle = this.menuBar.getColorBg();
        cntx2d.lineWidth = this.menuBar.getWidth();
        cntx2d.fillStyle = this.menuBar.getColorFg();
        
        //console.log('Rect type: ' + type);
        
        cntx2d.rect(this.rectPoint.x, this.rectPoint.y, width, height);
        
        if (type == 'fill' || type == 'rect')
        {
            cntx2d.fill();
        }
        if (type == 'stroke' || type == 'rect')
        {
            cntx2d.stroke();
        }
        cntx2d.closePath();
    }
    
}

WP();