<!DOCTYPE html>
<html>
<head>
    <title>Show Preview</title>
    <script>
        function previewPDF() {
            var win = window.open('/preview-pdf', '_blank');
            win.focus();
        }
    </script>
</head>
<body>
    <h1>Preview PDF</h1>
    <button onclick="previewPDF()">Preview PDF</button>
</body>
</html>