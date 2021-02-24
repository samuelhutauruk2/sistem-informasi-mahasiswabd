// // Dikodekan oleh : Samuel Hutauruk

// // event pada saat menu di klik

// $('.page-scroll').on('click', function(e){

//     // ambil isi atribut href
//     var tujuan = $(this).attr('href');

//     // tangkap elemen
//     var elemenTujuan = $(tujuan);

//     // pindahkan scroll
//     $('body').animate({
//         scrollTop: elemenTujuan.offset().top - 50
//     }, 1250, 'swing');

//     e.preventDefault();

// })