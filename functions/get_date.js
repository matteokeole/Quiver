function get_date() {
	var D = new Date(),
		year = D.getFullYear(),
		month = D.getMonth() + 1,
		day = D.getDate(),
		hour = D.getHours(),
		minute = D.getMinutes(),
		second = D.getSeconds(),
		date = year + "-" + month + "-" + day + "-" + hour + "-" + minute + "-" + second;
	return date
}