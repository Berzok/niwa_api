import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {
        console.log('hello, its me', this.element);

        // Get a reference to the file input element
        const inputElement = document.getElementById('input_file');

        /*
        // Create a FilePond instance
        const pond = FilePond.create(inputElement, {
            allowMultiple: true,
        });
         */
    }
}
