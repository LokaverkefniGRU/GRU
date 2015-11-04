 // Checkar´hvort user se online á 1 sec fresti
setInterval("update()", 1000); // Update every 1 seconds 
function update() 
{ 
	$.post("update.php"); // Sends request to update.php 
} 