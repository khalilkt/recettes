<html>
<head>
    <script>
        function getCount(){
            var comboBoxes = document.querySelectorAll("select");
            var selected = [];
            for(var i=0,len=comboBoxes.length;i<len;i++)
                {
                    var combo = comboBoxes[i];
                    var options = combo.children;
                    for(var j=0,length=options.length;j<length;j++){
                        var option = options[j];
                        if(option.selected){
                            selected.push(option.text);
                            //alert(option.text)
                        }
                    }
                }
            alert("Selected Options '" + selected + "' Total Count "+ selected.length);
        }
    </script>
</head>
<body>
<select  onchange="getCount()">
    <option>Google</option>
    <option>Yahoo</option>
    <option>Microsoft</option>
</select>
<select  onchange="getCount()">
    <option>Apple</option>
    <option>Banana</option>
    <option>Guava</option>
</select>
<select  onchange="getCount()">
    <option>ghhg</option>
    <option>jkk</option>
    <option>ppppp</option>
</select>
<select  onchange="getCount()">
    <option>ii</option>
    <option>uuu</option>
    <option>ppiuiuui</option>
</select>
</body>
</html>