function testFunction( testInt ){
	document.getElementById('printArea').value=testInt;
}

function javaTest(){
	$.post( "thresholdPage.php", { threshold: "document.getElementById( 'threshold2' ).value" } );
}

