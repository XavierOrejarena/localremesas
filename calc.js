function myFunction() {
	if (document.getElementById('de').value == "dolares") {
		var tasa = document.getElementById('dolares').value;
	}else if (document.getElementById('de').value == "soles") {
		var tasa = document.getElementById('soles').value;
	}

	var monto = document.getElementById('monto').value;

	document.getElementById('total').value = monto*tasa;
}