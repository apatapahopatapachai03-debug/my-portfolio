'use strict';
// drawer開く
$(document).ready(function(){
    $('#open_nav').on('click', function(){
        $('#nav').addClass('show');
    });

// 閉じる
    $('#closeNav').on('click', function(){
        $('#nav').removeClass('show');
    });
});

function confirmLogout() {
    return confirm('ログアウトしますか？');
}
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleCards');
    const cardListArea = document.getElementById('cardListArea');

    toggleButton.addEventListener('click', function () {
        cardListArea.classList.toggle('isShow');

        if (cardListArea.classList.contains('isShow')) {
            toggleButton.textContent = '登録済みクレジットカードを非表示';
        } else {
            toggleButton.textContent = '登録済みクレジットカードを表示';
        }
    });
});

// お気に入りボタンのクリックイベント（ハートのテキストバージョン）
document.addEventListener('DOMContentLoaded', function () {
    const favoriteButtons = document.querySelectorAll('.favoriteButton');

    favoriteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            this.classList.toggle('isFavorite');
        });
    });
});