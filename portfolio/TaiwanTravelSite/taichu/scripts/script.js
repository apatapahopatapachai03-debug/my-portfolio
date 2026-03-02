'use strict'
/*林さんゾーン*/
/*こっからローディングjs*/
$(window).on('load', function() {
    setTimeout(function() {
        $('#loadingLayer').addClass('loaded');
    }, 1500); 
});
$(function() {
   

    // 2. ドロワーメニュー
    $('.drawer-btn').on('click', function() {
    // ボタンに .open を付けて、CSSで×印に変身させる
        $(this).toggleClass('open');
        // メニューに .active を付けて、横からスライドさせる
        $('.drawer-menu').toggleClass('active');
    });

    

    // リンクをクリックしたら閉じる（便利機能）
    $('.drawer-menu a').on('click', function() {
        $('.drawer-btn').removeClass('open');
        $('.drawer-menu').removeClass('active');
    });

    // 3. 観光名所のスライダー
    const swiper = new Swiper('.slider-attraction', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: {
            delay: 3000,
        },
    });

    $(window).on('scroll', function() {
        // 10px以上スクロールしたら .hidden を追加して消す
        if ($(this).scrollTop() > 10) {
            $('.pc-nav').addClass('hidden');
        } else {
            // 最上部（10px以内）に戻ったら表示する
            $('.pc-nav').removeClass('hidden');
        }
    });

    // 5. Museum詳細データ
    const museumDetails = {
        m1: {
            title: "新天地西洋博物館",
            img1: "images/14.jpg",
            img2: "images/15.jpg",
            time: "10:00 - 18:00 (月曜定休)",
            address: "台中市東区旱溪東路一段456号"
        },
        m2: {
            title: "アジア近代美術館",
            img1: "images/16.jpg",
            img2: "images/17.jpg",
            time: "全日：09:30〜17:00",
            address: "413台湾台中市霧峰区柳豊路500号"
        },
        m3: {
            title: "勤美美術館",
            img1: "images/18.jpg",
            img2: "images/19.jpg",
            time: "全日：09:00〜17:00",
            address: "台中市西区館前路71号"
        },
        m4: {
            title: "台中文学館",
            img1: "images/20.jpg",
            img2: "images/21.jpg",
            time: "全日：09:00〜17:00",
            address: "台中市楽群街38号"
        }
    };

    // 6. モーダルを開く処理
    $('.museum-card, .modal-open').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); // 重複イベント防止

        const $card = $(this).closest('.museum-card');
        const id = $card.data('modal');
        const data = museumDetails[id];

        if (data) {
            $('#modal-title').text(data.title);
            $('#modal-img-1').attr('src', data.img1);
            $('#modal-img-2').attr('src', data.img2);
            $('#modal-time').text(data.time);
            $('#modal-address').text(data.address);

            $('#modal-container').fadeIn().css('display', 'flex');
        }
    });

    // 7. モーダルを閉じる処理
    $('.modal-close, #modal-container').on('click', function(e) {
        if (e.target === this || $(e.target).hasClass('modal-close')) {
            $('#modal-container').fadeOut();
        }
    });

   var topBtn = $('#page-top');
    topBtn.hide(); // 最初は隠しておく

    // スクロールして200pxを超えたら表示
    $(window).scroll(function () {
        if ($(this).scrollTop() > 200) {
            topBtn.fadeIn();
        } else {
            topBtn.fadeOut();
        }
    });

    // クリックしたら一気に（またはスルスルと）上に戻る
    topBtn.click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500); // 500ミリ秒（0.5秒）かけて戻る
        return false;
    });

});