(function initConsoleLogDiv() {
    'use strict';


    if (console.log.toDiv) {
        return;
    }

    function toString(x) {
        return typeof x === 'string' ? x : JSON.stringify(x);
    }

    var log = console.log.bind(console);
    var error = console.error.bind(console);
    var warn = console.warn.bind(console);
    var table = console.table ? console.table.bind(console) : null;
    var id = 'console-log-div';

    function createOuterElement() {
        var outer = document.getElementById(id);
        if (!outer) {
            outer = document.createElement('fieldset');
            outer.id = id;
            document.body.appendChild(outer);
        }
        outer.classList.add(id);

        var style = outer.style;
        style.whiteSpace = 'pre';

        return outer;
    }

    var logTo = (function createLogDiv() {
        var outer = createOuterElement();
        var div = document.createElement('div');
        div.id = 'console-log-text';
        outer.appendChild(div);

        return div;
    }());

    function printToDiv() {
        try{
            var msg = Array.prototype.slice.call(arguments, 0)
                .map(toString)
                .join('\n ');
            var text = logTo.textContent;
            logTo.textContent = text + msg + '\n';
        }catch(err){
            // do nothing
        }
    }

    function logWithCopy() {
        log.apply(null, arguments);
        printToDiv.apply(null, arguments);
    }

    console.log = logWithCopy;
    console.log.toDiv = true;

    console.error = function errorWithCopy() {
        error.apply(null, arguments);
        var args = Array.prototype.slice.call(arguments, 0);
        args.unshift('ERROR: ');
        printToDiv.apply(null, args);
    };

    console.warn = function logWarning() {
        warn.apply(null, arguments);
        var args = Array.prototype.slice.call(arguments, 0);
        args.unshift('WARNING: ');
        printToDiv.apply(null, args);
    };

    function printTable(objArr, keys) {

        var numCols = keys.length;
        var len = objArr.length;
        var $table = document.createElement('table');
        $table.style.width = '100%';
        $table.setAttribute('border', '1');
        var $head = document.createElement('thead');
        var $tdata = document.createElement('td');
        $tdata.innerHTML = 'Index';
        $head.appendChild($tdata);

        for (var k = 0; k < numCols; k++) {
            $tdata = document.createElement('td');
            $tdata.innerHTML = keys[k];
            $head.appendChild($tdata);
        }
        $table.appendChild($head);

        for (var i = 0; i < len; i++) {
            var $line = document.createElement('tr');
            $tdata = document.createElement('td');
            $tdata.innerHTML = i;
            $line.appendChild($tdata);

            for (var j = 0; j < numCols; j++) {
                $tdata = document.createElement('td');
                $tdata.innerHTML = objArr[i][keys[j]];
                $line.appendChild($tdata);
            }
            $table.appendChild($line);
        }
        var div = document.getElementById('console-log-text');
        div.appendChild($table);
    }

    console.table = function logTable() {
        if (typeof table === 'function') {
            table.apply(null, arguments);
        }

        var objArr = arguments[0];
        var keys;

        if (typeof objArr[0] !== 'undefined') {
            keys = Object.keys(objArr[0]);
        }
        printTable(objArr, keys);
    };

    window.addEventListener('error', function (err) {
        printToDiv( 'EXCEPTION: ', err.message + '\n    ' + err.filename, err.lineno + ':' + err.colno);
    });

}());
