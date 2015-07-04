<!DOCTYPE html>
<html>
<head>
    <title>m64hc11 gcc explorer</title>
    <style>
        #source, #source_text {
            width: 100%;
            height: 600px;
            
            display: block;
        }
    </style>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.1.9/ace.js"></script>
</head>
<body>

<form action="compile.php" method="post">
    <textarea name="source" id="source_text"><?php readfile('template.c'); ?></textarea>
    <div id="source" style="display:none"></div>
    
    <p>
        <strong>editor</strong>
        <a href="?editor">enable</a>
        <a href="?">disable</a>
    </p>
    
    <p>
        <strong>Language</strong>
        <label>C <input type="radio" name="lang" value="c"/></label>
        <label>C++ <input type="radio" name="lang" value="c++" checked/></label>
    </p>
    
    <p>
        <strong>Optimize</strong>
        <label>0 <input type="radio" name="opt" value="0"/></label>
        <label>1 <input type="radio" name="opt" value="1"/></label>
        <label>2 <input type="radio" name="opt" value="2" checked/></label>
    </p>
    
    <p><button>compile</button></p>
</form>

<p>garciat</p>

<script>
var textarea = document.querySelector('textarea[name=source]');

function handleTextareaChange() {
    window.localStorage.setItem('source', textarea.value);
}

$(textarea).on('change keyup paste', handleTextareaChange);
var savedSource = window.localStorage.getItem('source');
if (savedSource) {
    textarea.value = savedSource;
}

if (window.location.search.indexOf('?editor') >= 0) {
    var source = document.querySelector('#source');
    
    source.style.display = 'block';
    textarea.style.display = 'none';
    
    var editor = ace.edit(source);
    editor.getSession().setMode("ace/mode/c_cpp");
    
    editor.getSession().setValue(textarea.value);
    editor.getSession().on('change', function(){
      textarea.value = (editor.getSession().getValue());
      handleTextareaChange();
    });
}
</script>
</body>
</html>