
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DOM XSS Test</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; max-width: 600px; margin: auto; padding: 20px; border-radius: 5px; border: 1px solid #ddd; }
        h2 { margin-top: 0; color: #333; }
        textarea { width: 100%; height: 80px; padding: 10px; margin-bottom: 10px; box-sizing: border-box; font-family: monospace; }
        button { padding: 10px 15px; margin: 0 5px 10px 0; color: white; border: none; border-radius: 3px; cursor: pointer; }
        .btn-red { background: #d9534f; }
        .btn-green { background: #5cb85c; }
        .btn-blue { background: #0275d8; }
        .btn-gray { background: #666; }
        .output-box { border: 1px solid #ccc; background: #fafafa; padding: 15px; min-height: 60px; margin-top: 15px; }
        p { color: #666; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <h2>DOM XSS Test Tool</h2>
    <p>Enter text or HTML code to see how it gets rendered.</p>

    <textarea id="userInput" placeholder="Type something here..."></textarea>

    <div>
        <button class="btn-red" onclick="showUnsafe()">Unsafe (innerHTML)</button>
        <button class="btn-green" onclick="showSafe()">Safe (textContent)</button>
        <button class="btn-blue" onclick="showDom()">Safe (createElement)</button>
        <button class="btn-gray" onclick="insertPayload()">Insert Test Payload</button>
        <button class="btn-gray" onclick="clearOutput()">Clear</button>
    </div>

    <div class="output-box" id="output">
        Result will appear here...
    </div>
</div>

<script>
    function showUnsafe() {
        var text = document.getElementById("userInput").value;
        document.getElementById("output").innerHTML = text;
    }

    function showSafe() {
        var text = document.getElementById("userInput").value;
        document.getElementById("output").textContent = text;
    }

    function showDom() {
        var text = document.getElementById("userInput").value;
        var box = document.getElementById("output");
        
        box.innerHTML = "";
        var newNode = document.createTextNode(text);
        box.appendChild(newNode);
    }

    function insertPayload() {
        document.getElementById("userInput").value = "<img src=x onerror=alert(1)>";
        document.getElementById("output").textContent = "Payload inserted. Click a button to test.";
    }

    function clearOutput() {
        document.getElementById("userInput").value = "";
        document.getElementById("output").textContent = "Result will appear here...";
    }
</script>

</body>
</html>
