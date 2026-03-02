'use strict';

// function convert() {
//   const input = document.getElementById("number").value;
//   const num = Number(input);
//   const binary = num.toString(2);
//   const hex = num.toString(16);

//   document.getElementById("binary-result").textContent = binary;
//   document.getElementById("hexadecimal-result").textContent = hex;
// }
// document.getElementById("convert-btn").addEventListener("click", convert);

// 10進数を2進数、8進数、16進数に変換する関数
function toBinary(num) {
  if(num === 0) return "0";
  let result = "";
  let i =0;
  while (num > 0) {
    i++;
    const remainder = num % 2;
    console.log(`${num}を２で割る: ${i}回目`);
    console.log(`割る前: ${num}`);
    console.log(`割った余り: ${remainder}`);
    result = remainder + result;
    console.log(`割った余りを覚えてるやつ: ${result}`);
    num = Math.floor(num / 2);

    console.log(`割った回数、商: ${num}`);
    console.log("-----");
  }
  console.log(`最終result: ${result}`);
  return result;
}
function toOctal(num) {
  if(num === 0) return "0";
  let result = "";
  while (num > 0) {
    const remainder = num % 8; 
    result = remainder + result;
    num = Math.floor(num / 8);
  }
  return result;
}
function toHexadecimal(num) {
  if(num === 0) return "0";
  const hexDigits = "0123456789ABCDEF";
  let result = "";
  while (num > 0) {
    console.log(`現在の数値(16): ${num}`);
    const remainder = num % 16;
    console.log(`割った余り(16): ${remainder} → ${hexDigits[remainder]}`);
    // hexDigitsの型は本来文字列型だが、配列のようにインデックスでアクセスできるため、
    // hexDigits[remainder]で対応する16進数の文字を取得できる。
    result = hexDigits[remainder] + result;

    console.log(`16進数の桁: ${hexDigits[remainder]}`);
    num = Math.floor(num / 16);
  }
  return result;
}
// convert-btnをクリックしたときの処理
document.getElementById("convert-btn").addEventListener("click", () => {
  const num = Number(document.getElementById("number").value);
  const binary = toBinary(num);
  const octal = toOctal(num);
  const hexadecimal = toHexadecimal(num);
  // それぞれの結果を各コンテントへ表示
  document.getElementById("binary-result").textContent = binary;
  document.getElementById("octal-result").textContent = octal;
  document.getElementById("hexadecimal-result").textContent = hexadecimal;
});


// 2進数を10進数へ
function binaryToDecimal(binaryString) {
  let decimal = 0;
  for (let i = 0; i < binaryString.length; i++) {
    console.log(`現在の読み取り累積(10進数): ${decimal}　　次の2進数の桁: ${binaryString[i]}`);

    decimal = decimal * 2 + parseInt(binaryString[i], 2);
    console.log(` 加算される値？${decimal}`);
    console.log(`現在の読み取り累積(10進数): ${decimal}`);
    console.log("-----");
  }
  return decimal;
}
let decimal = document.getElementById("binary-to-decimal-result");
// イベントリスナー
document.getElementById("binary-to-decimal-btn").addEventListener("click", () => {
  const binaryInput = document.getElementById("binary-input").value;
  decimal.textContent = binaryToDecimal(binaryInput);
});

// 8進数を10進数へ
function octalToDecimal(octalString) {
  const octalDigits = "01234567"; // 8進数の有効な文字を定義(ガード用)
  let decimal = 0;
  for (let i = 0; i < octalString.length; i++) {
    const char = octalString[i]; // 一ったん文字として受け取る

    // ガード：辞書（octalDigits）に載っていない文字ならエラー
    // まず「文字」として正しいかガード
    if (!octalDigits.includes(octalString[i])) {
      console.error(`エラー: "${octalString[i]}" は8進数ではありません。`);
      return NaN;
    }
    console.log(`--- 桁の処理 [${i + 1}文字目: "${octalString[i]}"] ---`);
    console.log(`シフト前: ${decimal}`);
    console.log(`8倍にシフト: ${decimal * 8}`);
    console.log(`加算する値: ${currentDigitValue}`);
    
    // 合格したので、安心して「数値」に変換
    const currentDigitValue = parseInt(char);

    // 計算実行
    // 元が8進数なので、前の桁の値を8倍してから、現在の桁の値を加算する。
    decimal = (decimal * 8) + currentDigitValue;
    
    console.log(`計算後の蓄積: ${decimal}`);
  }
  return decimal;
}
let octalDecimal = document.getElementById("octal-to-decimal-result");
// イベントリスナー
document.getElementById("octal-to-decimal-btn").addEventListener("click", () => {
  const octalInput = document.getElementById("octal-input").value;
  octalDecimal.textContent = octalToDecimal(octalInput);
});



// 16進数を10進数へ
// 関数
function hexToDecimal(hexString) {
  // ガード：0-9, A-F, a-f 以外の文字が1つでも入っていたら即エラー
  // ^ は先頭、$ は末尾、[ ] は「この中のどれか」、i は大文字小文字無視
  const hexPattern = /^[0-9A-F]+$/i;

  if (!hexPattern.test(hexString)) {
    console.error("エラー: 無効な16進数形式です。");
    return NaN;
  }

  // ここからは安心して計算に集中できる
  return parseInt(hexString, 16);
}

let hexDecimal = document.getElementById("hex-to-decimal-result");
// イベントリスナー
document.getElementById("hex-to-decimal-btn").addEventListener("click", () => {
  const hexInput = document.getElementById("hex-input").value;
  hexDecimal.textContent = hexToDecimal(hexInput);
});




// n進数からn進数への変換は、まずn進数から10進数へ変換してから、10進数から目的のn進数へ変換する
/**
 * @param {string} value - 変換したい値（例: "1010"）
 * @param {number} fromBase - 元の進数（例: 2）
 * @param {number} toBase - 変換したい進数（例: 16）
 */
function convertBase(value, fromBase, toBase) {
  const digits = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

  // 10進数(Number型)へ変換 
  let decimalValue = 0;
  const upperValue = value.toString().toUpperCase();

  for (let i = 0; i < upperValue.length; i++) {
    const char = upperValue[i];
    const charValue = digits.indexOf(char);

    // ガード：元の進数で許されない文字があればエラー
    if (charValue === -1 || charValue >= fromBase) {
      return `エラー: "${char}" は ${fromBase}進数では無効です。`;
    }
    decimalValue = decimalValue * fromBase + charValue;
  }

  if (toBase === 10) return decimalValue.toString();

  // 10進数からターゲットの進数(String型)へ変換 
  if (decimalValue === 0) return "0";
  
  let result = "";
  let tempNum = decimalValue;

  while (tempNum > 0) {
    const remainder = tempNum % toBase;
    result = digits[remainder] + result;
    tempNum = Math.floor(tempNum / toBase);
  }

  return result;
}
// イベントリスナー
document.getElementById("base-convert-btn").addEventListener("click", () => {
  const value = document.getElementById("base-input").value;
  const fromBase = Number(document.getElementById("from-base-input").value);
  const toBase = Number(document.getElementById("to-base-input").value);

  const result = convertBase(value, fromBase, toBase);
  document.getElementById("base-convert-result").textContent = result;
});

// 実行例
console.log(convertBase("1010", 2, 16));  // 2進数 "1010" → 16進数 "A"
console.log(convertBase("77", 8, 2));    // 8進数 "77" → 2進数 "111111"
console.log(convertBase("FF", 16, 10));  // 16進数 "FF" → 10進数 "255"
