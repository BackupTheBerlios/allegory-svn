function toggleDisplay(obj_id){
	if (document.getElementById){
		var obj = document.getElementById(obj_id);
		if (obj.style.display == '' || obj.style.display == 'none'){
			var state = 'block';
			} 
		else {
			var state = 'none';
			}
		obj.style.display = state;
		}
}

function knife_bgc(row, highlight) {
	var lastColorUsed;
	if (highlight) {
		lastColorUsed = row.style.background;
		if (lastColorUsed) {
			row.style.background = '';
			}
		else {
			row.style.background = '#fff9e2';
			}
		}
	else {
		row.style.background = lastColorUsed;
		}
	}