'use strict'

const swiper = new Swiper('.mySwiper', {
  loop: true,
  slidesPerView: 'auto',
  centeredSlides: true,
  loopedSlides: 10,

  // ★ここを追加
  breakpoints: {
    0: {        // スマホ
      spaceBetween: 24
    },
    769: {      // タブレット以上
      spaceBetween: 80
    }
  },

  pagination: {
    el: '.swiper-pagination',
    clickable: true,
    renderBullet: function (index, className) {
      if (index >= 5) return '';
      return '<span class="' + className + '"></span>';
    },
  },

  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  on: {
    slideChange: function () {
      const bullets = document.querySelectorAll('.swiper-pagination-bullet');
      bullets.forEach(b => b.classList.remove('swiper-pagination-bullet-active'));

      const activeIndex = this.realIndex % 5; 
      if (bullets[activeIndex]) {
        bullets[activeIndex].classList.add('swiper-pagination-bullet-active');
      }
    }
  }
});


/*こっからローディング*/
$(window).on('load', function() {
    // 1. 全ての読み込みが完了したら実行されますわ
    // 2. 少し余韻を残してからクラスを付与して消しますの
    setTimeout(function() {
        $('#loadingLayer').addClass('loaded');
    }, 1500); // 1.5秒間はローディングを見せる設定ですわ。お好みで調整して。
});

/*こっからハンバーガー*/
// 要素を取得いたしますわ
const menuBtn = document.getElementById('menuBtn'); // 修正完了ですわね！
const closeBtn = document.getElementById('closeBtn');
const gNav = document.getElementById('gNav');
const overlay = document.getElementById('overlay');

// メニューの開閉を切り替える魔法ですわ
function toggleMenu() {
    menuBtn.classList.toggle('is-active');
    gNav.classList.toggle('is-active');
    overlay.classList.toggle('is-active');
}

// ボタンや背景をクリックした時に魔法を発動させますの
menuBtn.addEventListener('click', toggleMenu);
closeBtn.addEventListener('click', toggleMenu);
overlay.addEventListener('click', toggleMenu);