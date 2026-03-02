'use strict';
const modal = document.getElementById("modal");
const overlay = document.querySelector(".tpModalOverlay");
const closeBtn = document.querySelector(".modalClose");

// すべてのボタンを取得
const buttons = document.querySelectorAll(".tpNightmarketBtn");

// モーダル内の要素
const modalImg = document.getElementById("tpModalImg");
const modalTitle = document.getElementById("tpModalTitle");
const modalDesc = document.getElementById("tpModalDesc");
const modalHours = document.getElementById("tpModalHours");

// 各夜市のデータ
const marketData = {
  ningxia: {
    title: "寧夏夜市",
    img: "images/tpNightmarketMordal01.jpg",
    desc: "寧夏路夜市は台湾伝統の屋台料理やB級グルメがメインの夜市です。特に大同区の圓環付近には懐かしいグルメがたくさん集まっていますので、思う存分味わってください。また、ここの夜市は歩道と車道が分かれているので、食事やショッピングに便利です。食の夜市とも言われる寧夏路夜市には毎日、大勢の人々が訪れています。",
    hours: [
      "日曜 17:00 - 22:00",
      "月曜 17:00 - 22:00",
      "火曜 17:00 - 22:00",
      "水曜 17:00 - 22:00",
      "木曜 17:00 - 22:00",
      "金曜 17:00 - 22:00",
      "土曜 17:00 - 22:00"
    ]
  },
  raohe: {
    title: "饒河街観光夜市",
    img: "images/tpNightmarketMordal02.jpg",
    desc: "饒河街観光夜市は、屋台料理から雑貨や生活用品も扱う夜市です。その手ごろな値段が魅力的で、多くの人々で賑わいます。最も観光客に人気があるのは「藥燉排骨」「胡椒餅」「水煎包」「蚵仔麵線」など行列ができる人気料理と、「麻辣臭豆腐」「牛肉麵」「天婦羅」など台湾の伝統的な屋台料理も定番です。",
    hours: [
      "日曜 17:00 - 25:00",
      "月曜 17:00 - 25:00",
      "火曜 17:00 - 25:00",
      "水曜 17:00 - 25:00",
      "木曜 17:00 - 25:00",
      "金曜 17:00 - 25:00",
      "土曜 17:00 - 25:00"
    ]
  },
  shilin: {
    title: "士林夜市",
    img: "images/tpNightmarketMordal03.jpg",
    desc: "ここは市内で最も規模が大きく知名度の高い夜市で、台湾のおいしい屋台グルメからユニークな雑貨まで、ありとあらゆるものが売られています。その種類の豊富さ、敷地の広さ、歴史、そして夜遊びスポットとしての人気度と、士林夜市の魅力は何から何まで台北ナンバーワン。台北観光では絶対にはずせない魅惑スポットです。",
    hours: [
      "日曜 17:00 - 23:00",
      "月曜 17:00 - 23:00",
      "火曜 17:00 - 23:00",
      "水曜 17:00 - 23:00",
      "木曜 17:00 - 23:00",
      "金曜 17:00 - 23:00",
      "土曜 17:00 - 23:00"
    ]
  },
  tonghua: {
    title: "通化夜市",
    img: "images/tpNightmarketMordal04.jpg",
    desc: "台北の他の夜市と比べると小規模ではあるものの、食べ物においてはどの夜市にも決して劣りません。有名な駱記小炒(炒め物)、裕品元の氷火湯円、平価鉄板焼、通化夜市の揚げサツマイモボールは、ぜひとも賞味したい特色的な伝統軽食です。マッサージ店もたくさんあり、1日の終わりに最適な夜市です。",
    hours: [
      "日曜 16:00 - 00:00",
      "月曜 16:00 - 00:00",
      "火曜 16:00 - 00:00",
      "水曜 16:00 - 00:00",
      "木曜 16:00 - 00:00",
      "金曜 16:00 - 00:00",
      "土曜 16:00 - 00:00"
    ]
  }
};

// ボタンを押したときの処理
buttons.forEach(btn => {
  btn.addEventListener("click", () => {
    const key = btn.dataset.modal; // 例："ningxia"

    const data = marketData[key];

    // モーダルの中身を入れ替え
    modalImg.src = data.img;
    modalImg.alt = data.title;
    modalTitle.textContent = data.title;
    modalDesc.textContent = data.desc;

    // 営業時間リストを作り直す
    modalHours.innerHTML = "";
    data.hours.forEach(h => {
      const li = document.createElement("li");
      li.textContent = h;
      modalHours.appendChild(li);
    });

    // モーダル表示
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
  });
});

// 閉じる処理
closeBtn.addEventListener("click", closeModal);
overlay.addEventListener("click", closeModal);

function closeModal() {
  modal.style.display = "none";
  document.body.style.overflow = "auto";
}

// ページトップへボタンディレイ表示
const topBtn = document.querySelector('.goTotop');

const heroHeight = document.querySelector('.tpHeaderContainer').offsetHeight;

window.addEventListener('scroll', () => {
  if (window.scrollY > heroHeight) {
    topBtn.classList.add('is-show');
  } else {
    topBtn.classList.remove('is-show');
  }
});


/*-------------------------------こっからローディングjs----------------------------------*/
$(window).on('load', function() {
    setTimeout(function() {
        $('#loadingLayer').addClass('loaded');
    }, 1500); 
});