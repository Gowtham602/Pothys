// // Flash Messages
// document.addEventListener('DOMContentLoaded', function () {

//     if (typeof window.flashMessages !== 'undefined') {

//         const { success, error, validationErrors } = window.flashMessages;

//         if (validationErrors && validationErrors.length > 0) {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Validation Error',
//                 html: validationErrors.join('<br>'),
//                 timer: 3000,
//                 showConfirmButton: false
//             });
//         }

//         if (error) {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error',
//                 text: error,
//                 timer: 2500,
//                 showConfirmButton: false
//             });
//         }

//         if (success) {
//             Swal.fire({
//                 icon: 'success',
//                 title: 'Success',
//                 text: success,
//                 timer: 2000,
//                 showConfirmButton: false
//             });
//         }
//     }

// });







// // document.addEventListener('DOMContentLoaded', function () {

// //     // DataTable
// //     if ($('#imageTable').length) {
// //         $('#imageTable').DataTable({
// //             pageLength: 10,
// //             lengthMenu: [5, 10, 25, 50],
// //             ordering: true
// //         });
// //     }

// //     // Copy Link
// //     window.copyLink = function (link) {
// //         navigator.clipboard.writeText(link);
// //         Swal.fire({
// //             icon: 'success',
// //             title: 'Copied',
// //             text: 'Short URL copied to clipboard',
// //             timer: 1500,
// //             showConfirmButton: false
// //         });
// //     };

// //     // Form Validation
// //     let form = document.getElementById('uploadForm');

// //     if (form) {
// //         form.addEventListener('submit', function (e) {

// //             let name = document.querySelector('input[name="image_name"]').value.trim();
// //             let file = document.querySelector('input[name="image"]').files[0];
// //             let files = document.querySelectorAll('input[name="images[]"]');

// // if (files.length === 0 || !files[0].files.length) {
// //     e.preventDefault();
// //     Swal.fire({
// //         icon: 'error',
// //         title: 'Please select at least one image'
// //     });
// //     return;
// // }

// //             if (name === '') {
// //                 e.preventDefault();
// //                 Swal.fire({
// //                     icon: 'error',
// //                     title: 'Image name is required'
// //                 });
// //                 return;
// //             }

// //             if (!file) {
// //                 e.preventDefault();
// //                 Swal.fire({
// //                     icon: 'error',
// //                     title: 'Please select an image'
// //                 });
// //                 return;
// //             }

// //             if (!['image/jpeg', 'image/jpg'].includes(file.type)) {
// //                 e.preventDefault();
// //                 Swal.fire({
// //                     icon: 'error',
// //                     title: 'Only JPEG/JPG allowed'
// //                 });
// //                 return;
// //             }

// //             if (file.size > 2048 * 1024) {
// //                 e.preventDefault();
// //                 Swal.fire({
// //                     icon: 'error',
// //                     title: 'File must be less than 2MB'
// //                 });
// //                 return;
// //             }

// //         });
// //     }

// // });