<html>
	<head>
		<title>
			sdf2svg - open source tool to convert sdf or mol file to svg - scalable graphics. 
		</title>
		<script>
			uploadMolAndDisplaySVG = function()
			{
				document.getElementById( 'download' ).value=0;
				document.getElementById('displayName').value=1;
				document.theForm.submit();
			}
			
			uploadMolAndDownloadSVG = function()
			{
				document.getElementById('download').value=1;
				document.getElementById('displayName').value=1;
				document.theForm.submit();
			}
			getDrawingAndDisplaySVG = function()
			{
				document.getElementById('moldata').value=document.editor.molFile();
				document.getElementById('molfile').value="";
				document.getElementById('download').value=0;
				document.getElementById('displayName').value=0;
				document.theForm.submit();
			}
			getDrawingAndDownloadSVG = function()
			{
				document.getElementById('moldata').value=document.editor.molFile();
				document.getElementById('download').value=1;
				document.getElementById('molfile').value="";
				document.getElementById('displayName').value=0;
				document.theForm.submit();
			}
					</script>
		<style>
			*{font-family:arial;color:#666666;}
			body{margin:0px;padding:0px;}
			div{padding-left:5px;padding-bottom:5px;padding-top:5px;margin-bottom:10px;}
			button,input{margin-top:10px;}
			li{margin-bottom:20px;}
		</style>
	</head>
	<body>
		<div style="background:#666666;font-size:20px;margin-bottom:20px;color:white;height:27">
			sdf2svg - Converting .mol or .sdf files to SVG
		</div>
		<div style="float:left;margin-right:20px">
			<div style="background-color:#666666;color:white;">Upload a mol or sdf file</div>
		<form  name="theForm" enctype='multipart/form-data' action='outputsvg.php' method='POST' target="output">
			<input type='hidden' name='max_file_size' value='9000000'>
			<input type='hidden' name='sizex' value='500'>
			<input type='hidden' name='sizey' value='500'>
			<input type='hidden' name='displayName' id='displayName' value='1'>
			<input type='hidden' name='moldata' id="moldata" value=''>
			<input type='hidden' name='molname'  value='uploaded'>
			<input type='hidden' name='download' id='download'  value='0'>
			
			Choose mol or sdf file:<br>
			<input type="file" name="molfile" id="molfile"><br>
			
			<button onClick="uploadMolAndDisplaySVG();">Display mol or sdf file</button>
			<button onClick="uploadMolAndDownloadSVG();">Download as SVG</button>
		</form>
		
		<iframe style="border-style:solid;border-color:#666666;float:left;margin-right:20px;margin-bottom:20px" src="outputsvg.php" width=500 height=500 name="output">
		</iframe>
		<div style="background:#666666;font-size:20px;margin-top:20px;margin-bottom:20px;color:white;clear:both">
			About
		</div>
			Sdf2svg is developed and maintained by <a href="mailto:ronnie@binofo.com">Ronnie Persson</a> - <a href="http://www.binofo.com/">Binofo</a>. Source is available at: <a href="http://sourceforge.net/projects/sdf2svg/">http://sourceforge.net/projects/sdf2svg/</a>
			        <script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("UA-1664386-7");
			pageTracker._trackPageview();
			} catch(err) {}</script>
		
	</body>
</html>