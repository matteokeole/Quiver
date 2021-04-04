function convert_date(date) {
	date = date.split("-");
	var new_date = date[2] + "/" + date[1] + "/" + date[0] + " a " + date[3] + ":" + date[4] + ":" + date[5];
	return new_date
}