console.log('Hello Webpack assets/js/admin.js');

import axios from 'axios';

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

import {deleteMessage, toastMessage, handleSubmit } from './app.js';

document.addEventListener('DOMContentLoaded', () => {

    deleteMessage();

    handleSubmit('post_form');

    deletePost();
});

const editorDom = document.querySelector( '.ckeditor' );

if (editorDom)
{
    ClassicEditor
        .create( editorDom )
        .then( editor => {
            console.log( editor );
        })
        .catch( error => {
            console.error( error );
        });
}

export const deletePost = () => {
    // Get all "delete button" elements
    const deleteAction = Array.prototype.slice.call(document.querySelectorAll('.deletePost'), 0);

    // Check if there are any delete button
    if (deleteAction.length > 0) {

        // Add a click event on each of them
        deleteAction.forEach( el => {
            el.addEventListener('click', () => {

                axios
                    .delete('/admin/post/delete',{
                        data: {
                            'id' : el.dataset.id,
                            '_token' : el.dataset.csrf
                        }
                    })
                    .then((response) => {
                        if (response.data.error) {
                            toastMessage(response.data.error, 'danger');
                        }

                        if (response.data.message){
                            toastMessage(response.data.message);

                            // suppression du dom
                            const tr_post = document.getElementById('post_tr_'+el.dataset.id)
                            tr_post.remove();
                        }
                    })

                return false;
            });
        });
    }
}
