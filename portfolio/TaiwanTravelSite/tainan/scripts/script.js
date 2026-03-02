'use strict'
/*林さんゾーン*/
/*こっからローディングjs*/
$(window).on('load', function() {
    setTimeout(function() {
        $('#loadingLayer').addClass('loaded');
    }, 1500); 
});


//ここからハンバーガーメニュー
// ========== 要素を取得 ==========
const hamburger = document.getElementById('tnHamburger');
// HTMLの中から id="tnHamburger" の要素を探して、hamburgerという名前で使えるようにする
const menu = document.getElementById('tnMenu');
// HTMLの中から id="tnMenu" の要素を探して、menuという名前で使えるようにする
const tnOverlay = document.getElementById('tnOverlay');
// HTMLの中から id="tnOverlay" の要素を探して、tnOverlayという名前で使えるようにする
// 追加したハンバーガーメニューのアイコンを画像にするやつ
const hamburgerIcon = document.getElementById('tnHamburgerIcon');

// ========== ハンバーガーアイコンをクリックした時 ==========
hamburger.addEventListener('click', function() {
  // addEventListener = 「イベントを監視する」という意味
  // 'click' = クリックされた時
  // function() { ... } = クリックされた時に実行する処理
   const isActive = hamburger.classList.toggle('active'); // ← この1行に変更
  // classList.toggle('active') = activeクラスをつけたり外したりする
  // すでにactiveがついていたら外す、ついていなかったらつける
  
// ← ここから下の6行を追加
  if (isActive) {
    hamburgerIcon.src = 'images/tnClose.svg';
    hamburgerIcon.alt = 'メニューを閉じる';
  } else {
    hamburgerIcon.src = 'images/tnOpen.svg';
    hamburgerIcon.alt = 'メニュー';
  }
  // ← ここまで追加

  menu.classList.toggle('active');
  // メニューにもactiveクラスをつけたり外したり
  
  tnOverlay.classList.toggle('active');
  // オーバーレイにもactiveクラスをつけたり外したり
});
// → ハンバーガーをクリックするたびに、3つの要素にactiveがついたり外れたりする
//   = メニューが開いたり閉じたりする


// ========== オーバーレイ（暗い背景）をクリックした時 ==========
  tnOverlay.addEventListener('click', function() {
  // オーバーレイがクリックされた時
  
  hamburger.classList.remove('active');
  // classList.remove('active') = activeクラスを外す（消す）
  // → ハンバーガーアイコンが×印から三本線に戻る

  
// ← ここから下の2行を追加
  hamburgerIcon.src = 'images/tnClose.svg';
  hamburgerIcon.alt = 'メニュー';
  // ← ここまで追加


  
  menu.classList.remove('active');
  // メニューからactiveクラスを外す
  // → メニューが画面外にスライドして隠れる
  
  tnOverlay.classList.remove('active');
  // オーバーレイからactiveクラスを外す
  // → 暗い背景が消える
});
// → 暗い背景をクリックするとメニューが閉じる



/*ここからトップに戻る*/

// トップに戻るボタンの要素を取得
const backToTopButton = document.getElementById('backToTop');

// ボタンクリック時にページトップへスムーズスクロール
backToTopButton.addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});



//　ここからモーダルウィンドウ

// DOMの読み込み完了後に実行
document.addEventListener('DOMContentLoaded', function() {
    

    
    // 要素の取得
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeBtn = document.querySelector('.closeBtn');
    const modalOverlay = document.querySelector('.modalOverlay');
    const viewImageLinks = document.querySelectorAll('.view-image');
        
    // 「画像を見る」リンクをクリックしたとき
    viewImageLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // リンクのデフォルト動作を防ぐ
            
            // data-image属性から画像のパスを取得
            const imagePath = this.getAttribute('data-image');
            
            // モーダル内の画像のsrcを設定
            modalImage.src = imagePath;
            
            // モーダルを表示
            openModal();
        });
    });
    
    // 閉じるボタンをクリックしたとき
    closeBtn.addEventListener('click', function() {
        closeModal();
    });
    
    // オーバーレイ（背景）をクリックしたとき
    modalOverlay.addEventListener('click', function() {
        closeModal();
    });
    
    // Escキーでモーダルを閉じる
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
    
    // モーダルを開く関数
    function openModal() {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; // 背景のスクロールを無効化
    }
    
    // モーダルを閉じる関数
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = ''; // スクロールを元に戻す
    }
});

