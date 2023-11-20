let scrollPercent = 0;

document.body.onscroll = () => {
	scrollEvent();
};

document.body.onload = () => {
	scrollEvent();
	set_human_pos();
};

// プレイヤーが指定の位置に到着した時の処理
$(".player").not(".walk").on("animationend", () => {
	$(".update").css("display", "block");
});

//
$(".sun" + ".night-out").on("animationend", () => {
	$("body").css("overflow", "visible");
	$(".preview-zone").removeClass("night-bg");
	$(".back-ground").removeClass("night-sky");
	$(".sun").removeClass("night-out");
	$(".moon").removeClass("night-in");
	$(".back").removeClass("night");
    $(".front").removeClass("night");
    $(".field").removeClass("night");
    $(".player").removeClass("walk");
    $(".player").on("animationend", () => {
        $(".update").css("display", "block");
    });
	// ここで順位ごとの値を入れる
	set_human_pos();	
});

let set_human_pos = () => {
	if(ranking_data.length == 0 || ranking_data[0]["PAGE"] == 0) {return false};
	
	switch(ranking_data.length) {
		default:
			$(":root").css("--player-position-3", ranking_data[2]["PAGE"] * 100 / ranking_data[0]["PAGE"]);
		case 2:
			$(":root").css("--player-position-2", ranking_data[1]["PAGE"] * 100 / ranking_data[0]["PAGE"]);
		case 1:
			$(":root").css("--player-position-1", 100);
			let user_index = -1;
			for(let i = 0; i < ranking_data.length; i++) {
				if(ranking_data[i]["ID"] == user_id) {
					user_index = i;
					break;
				}
			}
			$(":root").css("--player-position-4", ranking_data[user_index]["PAGE"] * 100 / ranking_data[0]["PAGE"]);
			break;
	}
}

let scrollEvent = () => {
	//現在のスクロール率をパーセントで計算する
	scrollPercent =
		(document.documentElement.scrollTop /
			(document.documentElement.scrollHeight - document.documentElement.clientHeight)) *
		100;

	let preview_zone =
		(($(".preview-zone").height() - $(".marathon").height()) /
			(document.documentElement.scrollHeight - document.documentElement.clientHeight)) *
		100;

	let previewPercent =
		(document.documentElement.scrollTop / 
            ($(".preview-zone").height() - $(".marathon").height())) * 
        100;

	if (previewPercent > 100) {
		$(".marathon").removeClass("fadein");
		$(".marathon").addClass("fadeout");
	} else {
		$(".marathon").removeClass("fadeout");
		$(".marathon").addClass("fadein");
		$(".sun").css("bottom", 30 + 40 * ((100 - previewPercent) / 100) + "%");
		$(".moon").css("bottom", 30 + 40 * ((100 - previewPercent) / 100) + "%");
		$(".back").css("bottom", 3 + 4 * ((100 - previewPercent) / 100) + "vw");
	}
};
